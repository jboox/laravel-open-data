<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dataset;
use App\Models\DatasetValue;
use App\Models\Category;
use App\Models\User;
use App\Models\Region;

class DatasetSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user admin pertama
        $admin = User::first();

        // Ambil kategori kesehatan
        $category = Category::where('slug', 'kesehatan')->first();

        // Ambil salah satu kecamatan dari RegionSeeder
        $kecamatan = Region::where('level', 2)->first();

        // Buat dataset contoh: Jumlah Penduduk
        $dataset = Dataset::create([
            'title' => 'Jumlah Penduduk',
            'description' => 'Jumlah penduduk berdasarkan data kependudukan Kabupaten Sikka',
            'category_id' => $category->id,
            'created_by' => $admin->id,
            'published_at' => now(),
        ]);

        // Isi nilai contoh (series per tahun)
        $values = [
            ['date' => '2020-01-01', 'value' => 35000],
            ['date' => '2021-01-01', 'value' => 36000],
            ['date' => '2022-01-01', 'value' => 37000],
            ['date' => '2023-01-01', 'value' => 38000],
        ];

        foreach ($values as $val) {
            DatasetValue::create([
                'dataset_id' => $dataset->id,
                'region_id' => $kecamatan->id,
                'date' => $val['date'],
                'value' => $val['value'],
                'meta' => ['satuan' => 'jiwa'],
            ]);
        }
    }
}
