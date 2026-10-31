<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    /** [title, category, description, verification question] */
    private const TEMPLATES = [
        ['Black leather wallet', 'Wallets & Bags', 'Black bifold wallet with a few cards inside.', 'What color is the lining of the wallet?'],
        ['Blue backpack', 'Wallets & Bags', 'Medium blue backpack with a laptop sleeve and a keychain on the zipper.', 'What is attached to the front zipper?'],
        ['iPhone with cracked screen', 'Electronics', 'Phone in a clear case, crack across the top left corner.', 'What is the lock screen wallpaper?'],
        ['Wireless earbuds case', 'Electronics', 'White charging case, scratches on the lid.', 'What sticker is on the case?'],
        ['Silver laptop', 'Electronics', '13-inch silver laptop with stickers on the lid.', 'Name one sticker on the lid.'],
        ['Set of keys', 'Keys', 'Three keys on a ring with a small red tag.', 'How many keys are on the ring?'],
        ['Car key fob', 'Keys', 'Black fob with a leather strap.', 'What brand is printed on the fob?'],
        ['Student ID card', 'Documents & Cards', 'Photo ID card in a plastic holder.', 'What is the last name on the card?'],
        ['Grey hoodie', 'Clothing', 'Grey zip-up hoodie, size M, small logo on the chest.', 'What is written inside the collar tag?'],
        ['Prescription glasses', 'Accessories', 'Black round frames in a brown case.', 'What color is the case?'],
        ['Gold bracelet', 'Jewelry', 'Thin gold chain bracelet with a small charm.', 'What shape is the charm?'],
        ['Water bottle', 'Other', 'Steel water bottle with dents and a green cap.', 'What name is engraved on the bottom?'],
        ['Umbrella', 'Other', 'Navy automatic umbrella with a wooden handle.', 'What is unusual about the handle?'],
    ];

    private const LOCATIONS = [
        'Central Library', 'Main Cafeteria', 'Bus Station', 'City Park', 'Sports Hall',
        'Shopping Mall entrance', 'Metro Station', 'Student Center', 'Parking Lot B', 'Coffee Corner',
    ];

    public function definition(): array
    {
        [$title, $category, $description, $question] = fake()->randomElement(self::TEMPLATES);
        $date = fake()->dateTimeBetween('-30 days', '-1 day');

        return [
            'owner_id' => User::factory(),
            'type' => fake()->randomElement(['lost', 'found']),
            'title' => $title,
            'category' => $category,
            'description' => $description,
            'location' => fake()->randomElement(self::LOCATIONS),
            'date' => $date,
            'image' => $this->makeImage($title, $category),
            'question' => $question,
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }

    public function lost(): static  { return $this->state(fn () => ['type' => 'lost']); }
    public function found(): static { return $this->state(fn () => ['type' => 'found']); }

    /**
     * Demo photo for an item. Uses a random real image from database/seeders/demo-images
     * (jpg, png, webp) if you put some there; otherwise draws a simple SVG placeholder.
     * Files go to the public disk, so run `php artisan storage:link` once.
     */
    private function makeImage(string $title, string $category): string
    {
        $disk = Storage::disk('public');
        $disk->makeDirectory('items');

        $real = File::glob(database_path('seeders/demo-images/*.{jpg,jpeg,png,webp}'), GLOB_BRACE);
        if ($real) {
            $file = fake()->randomElement($real);
            $path = 'items/'.Str::random(16).'.'.pathinfo($file, PATHINFO_EXTENSION);
            $disk->put($path, File::get($file));

            return $path;
        }

        $colors = ['#2563eb', '#0d9488', '#7c3aed', '#db2777', '#ea580c', '#16a34a', '#0891b2'];
        $c1 = fake()->randomElement($colors);
        $c2 = fake()->randomElement($colors);
        $t = htmlspecialchars($title, ENT_XML1);
        $cat = htmlspecialchars(strtoupper($category), ENT_XML1);

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="800" height="600" viewBox="0 0 800 600">
  <defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
    <stop offset="0" stop-color="{$c1}"/><stop offset="1" stop-color="{$c2}"/>
  </linearGradient></defs>
  <rect width="800" height="600" fill="url(#g)"/>
  <circle cx="650" cy="110" r="150" fill="#ffffff" opacity="0.12"/>
  <circle cx="120" cy="520" r="190" fill="#ffffff" opacity="0.10"/>
  <text x="400" y="280" font-family="Arial, Helvetica, sans-serif" font-size="46" font-weight="700" fill="#ffffff" text-anchor="middle">{$t}</text>
  <text x="400" y="340" font-family="Arial, Helvetica, sans-serif" font-size="24" letter-spacing="4" fill="#ffffff" opacity="0.85" text-anchor="middle">{$cat}</text>
</svg>
SVG;

        $path = 'items/'.Str::random(16).'.svg';
        $disk->put($path, $svg);

        return $path;
    }
}