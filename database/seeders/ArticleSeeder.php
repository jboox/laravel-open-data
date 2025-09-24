<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@sikka.opendata')->first();

        if (!$admin) {
            $this->command->error('User admin tidak ditemukan, seeding artikel dibatalkan.');
            return;
        }

        $articles = [
            [
                'title' => 'Data Bicara: Pertumbuhan Penduduk Kabupaten Sikka',
                'content' => '<p>Dalam 3 tahun terakhir, jumlah penduduk di Kabupaten Sikka terus meningkat.</p>',
            ],
            [
                'title' => 'Data Bicara: Distribusi Fasilitas Kesehatan di Sikka',
                'content' => '<p>Dataset fasilitas kesehatan menunjukkan ketimpangan distribusi puskesmas.</p>',
            ],
        ];

        foreach ($articles as $a) {
            Article::firstOrCreate(
                ['slug' => Str::slug($a['title'])],
                [
                    'title' => $a['title'],
                    'content' => $a['content'],
                    'author_id' => $admin->id,
                    'published_at' => now(),
                ]
            );
        }
    }
}
