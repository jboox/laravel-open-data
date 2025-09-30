<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Region;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DatasetValue>
 */
class DatasetValueFactory extends Factory
{
    public function definition(): array
    {
        return [
            'date'      => $this->faker->dateTimeBetween('-5 years', 'now'),
            'region_id' => Region::inRandomOrder()->first()->id ?? Region::factory(),
            'value'     => $this->faker->numberBetween(1000, 100000),
        ];
    }
}
