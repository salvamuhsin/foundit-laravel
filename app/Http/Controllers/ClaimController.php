<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Claim;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClaimController extends Controller
{
    /** A member claims someone else's listing. */
    public function store(Request $request, Item $item): RedirectResponse
    {
        $user = $request->user();

        if ($msg = $user->restrictionMessage()) {
            return back()->withErrors(['restricted' => $msg])->withInput();
        }

        $item->load('claims');

        if ($item->owner_id === $user->id) {
            return back()->withErrors(['claim' => 'You cannot claim your own listing.']);
        }
        if ($item->status === 'returned') {
            return back()->withErrors(['claim' => 'This item has already been returned.']);
        }
        if ($item->claims->contains('claimant_id', $user->id)) {
            return back()->withErrors(['claim' => 'You have already submitted a claim for this item.']);
        }

        $data = $request->validate([
            'answer' => ['required', 'string', 'min:5', 'max:500'],
            'details' => ['required', 'string', 'min:20', 'max:2000'],
        ], [
            'answer.min' => 'Answer the verification question (at least 5 characters).',
            'details.min' => 'Add supporting details of at least 20 characters.',
        ]);

        $item->claims()->create([...$data, 'claimant_id' => $user->id, 'status' => 'pending']);

        ActivityLog::record($user, 'claim.submitted', "Claimed “{$item->title}”.");

        return back()->with('status', 'Claim submitted. The poster will review it and you will see the result here.');
    }

    /** The poster approves a claim and proposes a meetup. Other pending claims are declined. */
    public function approve(Request $request, Claim $claim): RedirectResponse
    {
        $user = $request->user();
        $claim->load(['item', 'claimant']);
        abort_unless($claim->item->owner_id === $user->id, 403);

        if ($msg = $user->restrictionMessage()) {
            return back()->withErrors(['restricted' => $msg]);
        }
        if ($claim->status !== 'pending') {
            return back()->withErrors(['claim' => 'This claim has already been decided.']);
        }

        $data = $request->validate([
            'location' => ['required', 'string', 'min:3', 'max:160'],
            'datetime' => ['required', 'date', 'after:now'],
            'note' => ['nullable', 'string', 'max:200'],
        ], [
            'location.min' => 'Suggest a meeting place.',
            'datetime.after' => 'Choose a meeting time in the future.',
        ]);

        DB::transaction(function () use ($claim, $data, $user) {
            $claim->update(['status' => 'approved']);

            Claim::where('item_id', $claim->item_id)
                ->where('id', '!=', $claim->id)
                ->where('status', 'pending')
                ->update(['status' => 'declined', 'decline_reason' => 'Another claim was approved for this item.']);

            $claim->meetup()->create([
                'location' => $data['location'],
                'datetime' => $data['datetime'],
                'note' => $data['note'] ?? null,
                'status' => 'proposed',
                'proposed_by' => $user->id,
            ]);
        });

        ActivityLog::record($user, 'claim.approved', "Approved {$claim->claimant->name}'s claim on “{$claim->item->title}” and proposed a meetup.");

        return back()->with('status', 'Claim approved and meetup proposed. The claimant must confirm it.');
    }

    public function decline(Request $request, Claim $claim): RedirectResponse
    {
        $user = $request->user();
        $claim->load(['item', 'claimant']);
        abort_unless($claim->item->owner_id === $user->id, 403);

        if ($msg = $user->restrictionMessage()) {
            return back()->withErrors(['restricted' => $msg]);
        }
        if ($claim->status !== 'pending') {
            return back()->withErrors(['claim' => 'This claim has already been decided.']);
        }

        $data = $request->validate([
            'reason' => ['required', 'string', 'min:5', 'max:300'],
        ], ['reason.min' => 'Give a short reason (at least 5 characters) so the claimant knows why.']);

        $claim->update(['status' => 'declined', 'decline_reason' => $data['reason']]);

        ActivityLog::record($user, 'claim.declined', "Declined {$claim->claimant->name}'s claim on “{$claim->item->title}”.");

        return back()->with('status', 'Claim declined.');
    }

    /** The poster marks the item as handed over, once both people confirmed the meetup. */
    public function markReturned(Request $request, Claim $claim): RedirectResponse
    {
        $user = $request->user();
        $claim->load(['item', 'meetup']);
        abort_unless($claim->item->owner_id === $user->id, 403);

        if ($msg = $user->restrictionMessage()) {
            return back()->withErrors(['restricted' => $msg]);
        }
        if ($claim->status !== 'approved') {
            return back()->withErrors(['claim' => 'Only an approved claim can be marked returned.']);
        }
        if (! $claim->meetup || $claim->meetup->status !== 'confirmed') {
            return back()->withErrors(['claim' => 'Both people must confirm the meetup before the item is marked returned.']);
        }

        DB::transaction(function () use ($claim) {
            $claim->update(['status' => 'returned']);
            $claim->meetup->update(['status' => 'completed']);
        });

        ActivityLog::record($user, 'item.returned', "Marked “{$claim->item->title}” as returned.");

        return back()->with('status', 'Marked as returned. Thank you!');
    }
}
