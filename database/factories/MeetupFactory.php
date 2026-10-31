<?php

namespace Database\Factories;

use App\Models\Claim;
use App\Models\Meetup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meetup>
 */
class MeetupFactory extends Factory
{
    protected $model = Meetup::class;

    public function definition(): array
    {
        return [
            'claim_id' => Claim::factory()->approved(),
            'location' => fake()->randomElement(['Central Library desk', 'Main Cafeteria', 'Student Center lobby', 'Metro Station entrance']),
            'datetime' => fake()->dateTimeBetween('+1 day', '+10 days'),
            'note' => fake()->optional(0.6)->randomElement(['I will be wearing a red jacket.', 'Please bring a photo ID.', 'Text me when you arrive.']),
            'status' => 'proposed',
            'proposed_by' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => 'completed',
            'datetime' => fake()->dateTimeBetween('-7 days', '-1 day'),
        ]);
    }
}
