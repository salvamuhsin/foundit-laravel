<?php

namespace Database\Factories;

use App\Models\Claim;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Claim>
 */
class ClaimFactory extends Factory
{
    protected $model = Claim::class;

    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'claimant_id' => User::factory(),
            'answer' => fake()->sentence(4),
            'details' => fake()->randomElement([
                'I lost it last week and can describe exactly what was inside.',
                'This belongs to me, I can show a photo of it taken before I lost it.',
                'I recognise the description. It has a small mark that I can point out in person.',
                'I think this is mine. I was in that area at the time it went missing.',
            ]),
            'status' => 'pending',
            'decline_reason' => null,
        ];
    }

    public function pending(): static  { return $this->state(fn () => ['status' => 'pending']); }
    public function approved(): static { return $this->state(fn () => ['status' => 'approved']); }
    public function returned(): static { return $this->state(fn () => ['status' => 'returned']); }

    public function declined(): static
    {
        return $this->state(fn () => [
            'status' => 'declined',
            'decline_reason' => fake()->randomElement([
                'The answer does not match the item.',
                'Details were too vague to confirm ownership.',
            ]),
        ]);
    }
}
