<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        // Level 1: Kabupaten
        $kabupaten = Region::create([
            'name' => 'Kabupaten Sikka',
            'level' => 1,
            'parent_id' => null,
        ]);

        // Level 2: Kecamatan
        $kecamatan = Region::create([
            'name' => 'Kecamatan Alok',
            'level' => 2,
            'parent_id' => $kabupaten->id,
        ]);

        // Level 3: Kelurahan/Desa
        $desa = Region::create([
            'name' => 'Kelurahan Kota Baru',
            'level' => 3,
            'parent_id' => $kecamatan->id,
        ]);

        // Level 4: RT
        Region::create([
            'name' => 'RT 01',
            'level' => 4,
            'parent_id' => $desa->id,
        ]);

        Region::create([
            'name' => 'RT 02',
            'level' => 4,
            'parent_id' => $desa->id,
        ]);
    }
}
