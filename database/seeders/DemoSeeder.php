<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\Claim;
use App\Models\ContactMessage;
use App\Models\Item;
use App\Models\Meetup;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::factory()->create(['name' => 'Demo User', 'email' => 'demo@foundit.com']);
        $members = User::factory()->count(10)->create()->prepend($demo);
        $members->push(User::factory()->restricted()->create());

        foreach ($members as $m) {
            $this->log($m, 'user.registered', "{$m->name} registered", $m->created_at);
        }

        $items = Item::factory()->count(30)->create([
            'owner_id' => fn () => $members->random()->id,
        ]);

        foreach ($items as $item) {
            $owner = $members->firstWhere('id', $item->owner_id);
            $this->log($owner, 'item.created', "Posted a {$item->type} item: {$item->title}", $item->created_at);

            // Resulting item status: open | pending | meetup | returned
            $scenario = Arr::random(['open', 'open', 'pending', 'pending', 'meetup', 'returned']);
            if ($scenario === 'open') {
                continue;
            }

            $claimants = $members->where('id', '!=', $owner->id)->shuffle()->take(rand(1, 3))->values();

            foreach ($claimants as $i => $claimant) {
                $state = match ($scenario) {
                    'pending' => 'pending',
                    'meetup' => $i === 0 ? 'approved' : 'declined',
                    'returned' => $i === 0 ? 'returned' : 'declined',
                };

                $claim = Claim::factory()->{$state}()->create([
                    'item_id' => $item->id,
                    'claimant_id' => $claimant->id,
                    'created_at' => $item->created_at->copy()->addDays(rand(1, 3)),
                ]);

                $this->log($claimant, 'claim.submitted', "Claimed \"{$item->title}\"", $claim->created_at);

                if ($state === 'declined') {
                    $this->log($owner, 'claim.declined', "Declined a claim on \"{$item->title}\"", $claim->created_at->copy()->addDay());
                }

                if (in_array($state, ['approved', 'returned'])) {
                    $this->log($owner, 'claim.approved', "Approved a claim on \"{$item->title}\"", $claim->created_at->copy()->addDay());

                    $meetup = Meetup::factory()
                        ->when($state === 'returned', fn ($f) => $f->completed())
                        ->create(['claim_id' => $claim->id, 'proposed_by' => $owner->id]);

                    $this->log($owner, 'meetup.proposed', "Proposed a meetup at {$meetup->location}", $claim->created_at->copy()->addDay());

                    if ($state === 'returned') {
                        $this->log($owner, 'item.returned', "\"{$item->title}\" was returned", $meetup->datetime);
                    }
                }

                // Short conversation between the owner and this claimant
                Message::factory()->create(['item_id' => $item->id, 'from_id' => $claimant->id, 'to_id' => $owner->id]);
                Message::factory()->create(['item_id' => $item->id, 'from_id' => $owner->id, 'to_id' => $claimant->id]);
            }
        }

        ContactMessage::factory()->count(6)->create();
    }

    private function log(?User $user, string $action, string $description, $when): void
    {
        ActivityLog::factory()->create([
            'user_id' => $user?->id,
            'action' => $action,
            'description' => $description,
            'created_at' => $when,
        ]);
    }
}
