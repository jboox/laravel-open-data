<?php

namespace Database\Factories;

use App\Models\Dataset;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatasetFactory extends Factory
{
    protected $model = Dataset::class;

    public function definition()
    {
        return [
            'title'       => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'category_id' => Category::factory(),
            'created_by'  => User::factory(),
            'published_at'=> now(),
            'views'       => 0,
            'downloads'   => 0,
        ];
    }
}
