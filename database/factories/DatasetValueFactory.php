<?php

namespace Database\Factories;

use App\Models\DatasetValue;
use App\Models\Dataset;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatasetValueFactory extends Factory
{
    protected $model = DatasetValue::class;

    public function definition()
    {
        return [
            'dataset_id' => Dataset::factory(),
            'region_id'  => Region::factory(), // sekarang bisa pakai RegionFactory
            'date'       => $this->faker->date(),
            'value'      => $this->faker->randomFloat(2, 10, 1000), // angka float biar realistis
        ];
    }
}
