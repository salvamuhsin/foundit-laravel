<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'item_id' => Item::factory(),
            'from_id' => User::factory(),
            'to_id' => User::factory(),
            'body' => fake()->randomElement([
                'Hi, I think this might be mine. Is it still available?',
                'Thanks for reaching out. Can you tell me where you lost it?',
                'Yes, I was near the main entrance around noon.',
                'That matches. When are you free to meet?',
                'I can meet tomorrow afternoon, does that work?',
                'Great, see you then.',
            ]),
        ];
    }
}
