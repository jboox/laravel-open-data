<?php

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegionFactory extends Factory
{
    protected $model = Region::class;

    public function definition()
    {
        return [
            'name' => $this->faker->city,       // contoh: nama kota/wilayah
            'level' => 1, // atau bisa pakai $this->faker->randomElement(['provinsi','kabupaten','kecamatan','desa'])
        ];
    }
}
<?php

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegionFactory extends Factory
{
    protected $model = Region::class;

    public function definition()
    {
        return [
            'name'  => $this->faker->city,
            'level' => $this->faker->randomElement([
                1,
                2,
                3,
                4,
            ]), // isi kolom level (NOT NULL)
        ];
    }
}
