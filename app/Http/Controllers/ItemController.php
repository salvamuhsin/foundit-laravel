<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Item;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function browse(Request $request): View
    {
        $like = fn (string $v) => '%'.addcslashes($v, '%_\\').'%';

        $items = Item::with('claims')
            ->when($request->query('type'), fn ($q, $v) => $q->where('type', $v))
            ->when($request->query('category'), fn ($q, $v) => $q->where('category', $v))
            ->withStatus($request->query('status'))
            ->when($request->query('q'), fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('title', 'like', $like($v))
                ->orWhere('description', 'like', $like($v))
                ->orWhere('location', 'like', $like($v))))
            ->orderBy('created_at', $request->query('sort') === 'old' ? 'asc' : 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('browse', compact('items'));
    }

    public function show(Request $request, Item $item): View
    {
        $item->load(['owner', 'claims.claimant']);
        $me = $request->user();

        // Only the poster sees the claims on their listing.
        $claims = $me && $me->id === $item->owner_id
            ? $item->claims->sortByDesc('created_at')->values()
            : collect();

        // Admins never claim, so they never have "my claim".
        $myClaim = $me && ! $me->isAdmin() ? $item->claims->firstWhere('claimant_id', $me->id) : null;
        $myMeetup = $myClaim?->meetup;

        return view('items.show', compact('item', 'claims', 'myClaim', 'myMeetup'));
    }

    public function create(): View
    {
        return view('items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($msg = $user->restrictionMessage()) {
            return back()->withErrors(['restricted' => $msg])->withInput();
        }

        $data = $request->validate([
            'type' => ['required', 'in:lost,found'],
            'title' => ['required', 'string', 'min:3', 'max:120'],
            'category' => ['required', Rule::in(config('foundit.categories'))],
            'description' => ['required', 'string', 'min:15', 'max:2000'],
            'location' => ['required', 'string', 'min:3', 'max:160'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'question' => ['required', 'string', 'min:8', 'max:200'],
            'image' => ['nullable', 'image', 'max:8192'],
        ], [
            'date.before_or_equal' => 'Enter a date that is today or earlier.',
            'question.min' => 'Add a verification question of at least 8 characters.',
            'description.min' => 'Describe the item in at least 15 characters.',
        ]);

        $path = $request->file('image')?->store('items', 'public');

        $item = $user->items()->create([...collect($data)->except('image')->all(), 'image' => $path]);

        ActivityLog::record($user, 'item.created', "Reported a {$item->type} item: “{$item->title}”.");

        return redirect()->route('items.show', $item)->with('status', 'Your report is published.');
    }
}
