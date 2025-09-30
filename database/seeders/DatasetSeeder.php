<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Dataset;
use App\Models\Region;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;   // ✅ tambahin ini

class DatasetSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada kategori
        $category = Category::firstOrCreate(
            ['name' => 'Demografi'],
            ['slug' => Str::slug('Demografi')] // ✅ slug otomatis
        );

        // Buat dataset sample
        $dataset = Dataset::create([
            'title'       => 'Jumlah Penduduk Sample',
            'description' => 'Dataset contoh jumlah penduduk per kecamatan',
            'category_id' => $category->id,
            'created_by'  => 1,
            'file_path'   => 'samples/penduduk.csv',
            'api_url'     => url('/api/datasets'),
            'published_at'=> now(),
            'views'       => 0,
            'downloads'   => 0,
        ]);

        // Baca sample CSV
        $samplePath = storage_path('samples/penduduk.csv');
        if (file_exists($samplePath) && ($handle = fopen($samplePath, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ',');

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $record = array_combine($header, $row);

                // Buat region kalau belum ada
                $region = Region::firstOrCreate(
                    ['name' => $record['region']],
                    ['level' => 1]
                );

                $dataset->values()->create([
                    'date'      => $record['date'],
                    'region_id' => $region->id,
                    'value'     => $record['value'],
                ]);
            }

            fclose($handle);
        }
    }
}
