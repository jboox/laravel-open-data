<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dataset;
use App\Models\DatasetValue;
use App\Models\Category;
use App\Models\Region;
use Illuminate\Support\Str;

class DummyDatasetFactorySeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada kategori
        $demografi = Category::firstOrCreate(['name' => 'Demografi'], ['slug' => Str::slug('Demografi')]);
        $ekonomi   = Category::firstOrCreate(['name' => 'Ekonomi'],   ['slug' => Str::slug('Ekonomi')]);

        // Pastikan ada region
        $regions = [
            Region::firstOrCreate(['name' => 'Sikka'], ['level' => 2]),
            Region::firstOrCreate(['name' => 'Maumere'], ['level' => 2]),
            Region::firstOrCreate(['name' => 'Nita'], ['level' => 2]),
        ];

        // Generate 5 dataset dummy
        Dataset::factory(5)->create()->each(function ($dataset) use ($regions) {
            // Tiap dataset punya 3–5 values
            foreach (range(1, rand(3, 5)) as $i) {
                DatasetValue::factory()->create([
                    'dataset_id' => $dataset->id,
                    'region_id'  => $regions[array_rand($regions)]->id,
                ]);
            }
        });
    }
}
