<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Meetup;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $like = fn (string $v) => '%'.addcslashes($v, '%_\\').'%';

        $users = User::where('role', 'user')
            ->when($request->query('q'), fn ($q, $v) => $q->where(fn ($w) => $w->where('name', 'like', $like($v))->orWhere('email', 'like', $like($v))))
            ->when($request->query('status') === 'restricted', fn ($q) => $q->where('restricted', true))
            ->when($request->query('status') === 'active', fn ($q) => $q->where('restricted', false))
            ->withCount(['items', 'claims'])
            ->withMax('activityLogs', 'created_at')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->loadCount(['items', 'claims', 'sentMessages']);

        $items = $user->items()->with('claims')->latest()->get();
        $claims = $user->claims()->with('item.owner')->latest()->get();
        $meetups = Meetup::involving($user)->with('claim.item')->orderByDesc('datetime')->get();
        $activity = $user->activityLogs()->latest()->paginate(15);

        return view('admin.users.show', compact('user', 'items', 'claims', 'meetups', 'activity'));
    }

    public function restrict(Request $request, User $user): RedirectResponse
    {
        abort_if($user->isAdmin(), 403);

        $data = $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:300'],
        ]);

        $user->forceFill(['restricted' => true, 'restriction_reason' => $data['reason']])->save();

        // Logged on the member's own timeline so their profile tells the whole story.
        ActivityLog::record($user, 'account.restricted', 'Account restricted by an admin: '.$data['reason']);

        return back()->with('status', "{$user->name} is now restricted.");
    }

    public function unrestrict(User $user): RedirectResponse
    {
        abort_if($user->isAdmin(), 403);

        $user->forceFill(['restricted' => false, 'restriction_reason' => null])->save();

        ActivityLog::record($user, 'account.unrestricted', 'Restriction lifted by an admin.');

        return back()->with('status', "{$user->name} can use FoundIt normally again.");
    }
}
