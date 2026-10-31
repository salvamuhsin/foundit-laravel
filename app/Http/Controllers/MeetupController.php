<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Meetup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MeetupController extends Controller
{
    public function confirm(Request $request, Meetup $meetup): RedirectResponse
    {
        $user = $request->user();
        $this->authorizeParty($meetup, $user->id);

        if ($msg = $user->restrictionMessage()) {
            return back()->withErrors(['restricted' => $msg]);
        }
        if ($meetup->proposed_by === $user->id) {
            return back()->withErrors(['meetup' => 'The other person needs to confirm your proposal.']);
        }
        if (! in_array($meetup->status, ['proposed', 'changed'])) {
            return back()->withErrors(['meetup' => 'This meetup can no longer be confirmed.']);
        }

        $meetup->update(['status' => 'confirmed']);

        ActivityLog::record($user, 'meetup.confirmed', "Confirmed the meetup for “{$meetup->claim->item->title}” at {$meetup->location}.");

        return back()->with('status', 'Meetup confirmed.');
    }

    public function change(Request $request, Meetup $meetup): RedirectResponse
    {
        $user = $request->user();
        $this->authorizeParty($meetup, $user->id);

        if ($msg = $user->restrictionMessage()) {
            return back()->withErrors(['restricted' => $msg]);
        }
        if (in_array($meetup->status, ['completed', 'cancelled'])) {
            return back()->withErrors(['meetup' => 'This meetup is closed.']);
        }

        $data = $request->validate([
            'location' => ['required', 'string', 'min:3', 'max:160'],
            'datetime' => ['required', 'date', 'after:now'],
            'note' => ['nullable', 'string', 'max:200'],
        ], [
            'location.min' => 'Suggest a meeting place.',
            'datetime.after' => 'Choose a meeting time in the future.',
        ]);

        $meetup->update([...$data, 'status' => 'changed', 'proposed_by' => $user->id]);

        ActivityLog::record($user, 'meetup.changed', "Proposed a new meetup for “{$meetup->claim->item->title}” at {$meetup->location}.");

        return back()->with('status', 'Change proposed. The other person must confirm.');
    }

    /** Only the claimant and the person who posted the item may touch a meetup. */
    private function authorizeParty(Meetup $meetup, int $userId): void
    {
        $meetup->loadMissing('claim.item');
        abort_unless(
            $meetup->claim->claimant_id === $userId || $meetup->claim->item->owner_id === $userId,
            403
        );
    }
}
