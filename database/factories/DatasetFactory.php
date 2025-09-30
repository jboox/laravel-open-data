<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dataset>
 */
class DatasetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'created_by'  => User::inRandomOrder()->first()->id ?? 1,
            'published_at'=> $this->faker->dateTimeThisDecade(),
            'views'       => $this->faker->numberBetween(0, 1000),
            'downloads'   => $this->faker->numberBetween(0, 500),
        ];
    }
}
