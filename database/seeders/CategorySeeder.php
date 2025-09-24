<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kesehatan', 'slug' => 'kesehatan', 'description' => 'Data terkait kesehatan masyarakat'],
            ['name' => 'Pendidikan', 'slug' => 'pendidikan', 'description' => 'Data pendidikan di Kabupaten Sikka'],
            ['name' => 'Ekonomi', 'slug' => 'ekonomi', 'description' => 'Data ekonomi dan perdagangan'],
            ['name' => 'Infrastruktur', 'slug' => 'infrastruktur', 'description' => 'Data fasilitas umum dan infrastruktur'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
