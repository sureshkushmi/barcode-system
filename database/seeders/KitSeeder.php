<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kit;
use App\Models\Item;

class KitSeeder extends Seeder
{
    public function run()
    {
        $itemIds = Item::pluck('id')->toArray();

        Kit::factory()->count(5)->create()->each(function ($kit) use ($itemIds) {
            $itemsToAttach = collect($itemIds)
                ->random(rand(3, 6))
                ->all();

            foreach ($itemsToAttach as $itemId) {
                $kit->items()->attach($itemId, [
                    'quantity' => rand(1, 5)
                ]);
            }
        });
    }
}
