<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Kit;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class KitItemSeeder extends Seeder
{
    public function run()
    {
        $kitIds = Kit::pluck('id')->toArray();
        $itemIds = Item::pluck('id')->toArray();

        foreach ($kitIds as $kitId) {
            // Add 3–5 random items to each kit
            $randomItems = collect($itemIds)->random(rand(3, 5));

            foreach ($randomItems as $itemId) {
                DB::table('kit_items')->insert([
                    'kit_id' => $kitId,
                    'item_id' => $itemId,
                    'quantity' => rand(1, 10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

