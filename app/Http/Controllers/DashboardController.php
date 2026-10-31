<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Claim;
use App\Models\Item;
use App\Models\Meetup;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $open = Meetup::involving($user)->whereIn('status', ['proposed', 'changed', 'confirmed']);

        $stats = [
            'items' => $user->items()->count(),
            'claims' => $user->claims()->count(),
            'toReview' => $user->pendingReviewCount(),
            'openMeetups' => (clone $open)->count(),
            'needConfirm' => (clone $open)->whereIn('status', ['proposed', 'changed'])->where('proposed_by', '!=', $user->id)->count(),
        ];

        $recent = $user->items()->with('claims')->latest()->take(3)->get();

        return view('dashboard.index', compact('stats', 'recent'));
    }

    public function items(Request $request): View
    {
        $items = $request->user()->items()->with('claims')->withCount('claims')->latest()->paginate(10);

        return view('dashboard.items', compact('items'));
    }

    public function claims(Request $request): View
    {
        $claims = $request->user()->claims()->with('item.owner')->latest()->paginate(10);

        return view('dashboard.claims', compact('claims'));
    }

    /** Claims other people made on my items: pending ones first. */
    public function received(Request $request): View
    {
        $claims = Claim::whereHas('item', fn ($q) => $q->where('owner_id', $request->user()->id))
            ->with(['item', 'claimant'])
            ->orderByRaw("case when status = 'pending' then 0 else 1 end")
            ->latest()
            ->paginate(10);

        return view('dashboard.received', compact('claims'));
    }

    public function messages(Request $request): View
    {
        $user = $request->user();

        $threads = Message::where(fn ($q) => $q->where('from_id', $user->id)->orWhere('to_id', $user->id))
            ->with(['item', 'sender', 'recipient'])
            ->orderBy('created_at')
            ->get()
            ->groupBy(fn ($m) => $m->item_id.':'.($m->from_id === $user->id ? $m->to_id : $m->from_id))
            ->map(function ($msgs) use ($user) {
                $last = $msgs->last();

                return (object) [
                    'item' => $last->item,
                    'other' => $last->from_id === $user->id ? $last->recipient : $last->sender,
                    'last' => $last,
                    'messages' => $msgs->values(),
                ];
            })
            ->filter(fn ($t) => $t->item && $t->other)
            ->values();

        $itemId = (int) $request->query('item');
        $withId = (int) $request->query('with');
        $match = fn ($t) => $t->item->id === $itemId && $t->other->id === $withId;

        // A conversation opened from an item page that has no messages yet.
        if ($itemId && $withId && $withId !== $user->id && ! $threads->contains($match)) {
            $item = Item::find($itemId);
            $other = User::where('role', 'user')->find($withId);
            if ($item && $other) {
                $threads->push((object) ['item' => $item, 'other' => $other, 'last' => null, 'messages' => collect()]);
            }
        }

        $threads = $threads->sortByDesc(fn ($t) => $t->last?->created_at->timestamp ?? PHP_INT_MAX)->values();
        $active = $itemId && $withId ? $threads->first($match) : null;

        return view('dashboard.messages', compact('threads', 'active'));
    }

    public function sendMessage(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($msg = $user->restrictionMessage()) {
            return back()->withErrors(['restricted' => $msg]);
        }

        $data = $request->validate([
            'item_id' => ['required', 'exists:items,id'],
            'to_id' => ['required', 'exists:users,id'],
            'body' => ['required', 'string', 'max:1000'],
        ], [
            'body.required' => 'Write a message first.',
            'body.max' => 'Messages are limited to 1000 characters.',
        ]);

        $recipient = User::where('role', 'user')->find($data['to_id']);
        if (! $recipient || $recipient->id === $user->id) {
            return back()->withErrors(['to_id' => 'You cannot message that person.']);
        }

        Message::create(['item_id' => $data['item_id'], 'from_id' => $user->id, 'to_id' => $recipient->id, 'body' => $data['body']]);

        $title = Item::whereKey($data['item_id'])->value('title');
        ActivityLog::record($user, 'message.sent', "Messaged {$recipient->name} about “{$title}”.");

        return redirect()->route('dashboard.messages', ['item' => $data['item_id'], 'with' => $recipient->id]);
    }

    public function meetups(Request $request): View
    {
        $all = Meetup::involving($request->user())
            ->with(['claim.item.owner', 'claim.claimant', 'proposer'])
            ->orderByDesc('datetime')
            ->get();

        $isClosed = fn ($m) => in_array($m->status, ['completed', 'cancelled']);
        $active = $all->reject($isClosed)->values();
        $past = $all->filter($isClosed)->values();

        return view('dashboard.meetups', compact('active', 'past'));
    }
}
