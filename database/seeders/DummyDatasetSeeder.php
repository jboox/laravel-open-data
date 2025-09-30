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
        // Pastikan ada kategori
        $demografi = Category::firstOrCreate(
            ['name' => 'Demografi'],
            ['slug' => Str::slug('Demografi')]
        );
        $ekonomi = Category::firstOrCreate(
            ['name' => 'Ekonomi'],
            ['slug' => Str::slug('Ekonomi')]
        );
        $pendidikan = Category::firstOrCreate(
            ['name' => 'Pendidikan'],
            ['slug' => Str::slug('Pendidikan')]
        );

        // Pastikan ada region
        $sikka = Region::firstOrCreate(['name' => 'Sikka'], ['level' => 2]);

        // Dataset dummy
        $datasets = [
            ['title' => 'Jumlah Penduduk Sample', 'category_id' => $demografi->id, 'min' => 80000, 'max' => 120000],
            ['title' => 'PDRB Sample',             'category_id' => $ekonomi->id,   'min' => 100000, 'max' => 200000],
            ['title' => 'Angka Partisipasi Sekolah','category_id' => $pendidikan->id,'min' => 60, 'max' => 100],
            ['title' => 'Tingkat Pengangguran',    'category_id' => $ekonomi->id,   'min' => 2, 'max' => 15],
        ];

        foreach ($datasets as $data) {
            $dataset = Dataset::updateOrCreate(
                ['title' => $data['title']],
                [
                    'description' => 'Dummy dataset untuk uji dashboard',
                    'category_id' => $data['category_id'],
                    'created_by'  => 1,
                    'published_at'=> now(),
                    'views'       => 0,
                    'downloads'   => 0,
                ]
            );

            // Hapus data lama
            $dataset->values()->delete();

            // Tambahkan data tahunan untuk semua dataset
            foreach (range(2020, 2024) as $year) {
                $dataset->values()->create([
                    'date'      => $year.'-01-01',
                    'region_id' => $sikka->id,
                    'value'     => rand($data['min'], $data['max']),
                ]);
            }
        }
    }
}
