<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Dataset;
use App\Models\Region;
use Illuminate\Support\Str;

class DummyDatasetSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan kategori ada
        $demografi = Category::firstOrCreate(
            ['name' => 'Demografi'],
            ['slug' => Str::slug('Demografi')]
        );

        $ekonomi = Category::firstOrCreate(
            ['name' => 'Ekonomi'],
            ['slug' => Str::slug('Ekonomi')]
        );

        // Pastikan region ada
        $sikka = Region::firstOrCreate(['name' => 'Sikka'], ['level' => 2]);
        $maumere = Region::firstOrCreate(['name' => 'Maumere'], ['level' => 2]);

        // Dataset 1
        $dataset1 = Dataset::firstOrCreate(
            ['title' => 'Jumlah Penduduk Sample'],
            [
                'description' => 'Dataset contoh jumlah penduduk per wilayah',
                'category_id' => $demografi->id,
                'created_by'  => 1,
                'published_at'=> now(),
                'views'       => 0,
                'downloads'   => 0,
            ]
        );

        $dataset1->values()->delete(); // reset kalau ada
        $dataset1->values()->createMany([
            ['date' => '2023-01-01', 'region_id' => $sikka->id, 'value' => 95000],
            ['date' => '2023-01-01', 'region_id' => $maumere->id, 'value' => 45000],
        ]);

        // Dataset 2
        $dataset2 = Dataset::firstOrCreate(
            ['title' => 'PDRB Sample'],
            [
                'description' => 'Dataset contoh PDRB per wilayah',
                'category_id' => $ekonomi->id,
                'created_by'  => 1,
                'published_at'=> now(),
                'views'       => 0,
                'downloads'   => 0,
            ]
        );

        $dataset2->values()->delete();
        $dataset2->values()->createMany([
            ['date' => '2023-01-01', 'region_id' => $sikka->id, 'value' => 120000],
            ['date' => '2023-01-01', 'region_id' => $maumere->id, 'value' => 80000],
        ]);
    }
}
