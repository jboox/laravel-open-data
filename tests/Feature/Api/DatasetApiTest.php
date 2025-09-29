<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Dataset;
use App\Models\DatasetValue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatasetApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_datasets()
    {
        $category = Category::factory()->create();
        $dataset = Dataset::factory()->create([
            'category_id' => $category->id,
        ]);

        $response = $this->getJson('/api/datasets');

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'title' => $dataset->title,
                     'category' => $category->name,
                 ]);
    }

    /** @test */
    public function it_can_show_dataset_detail()
    {
        $dataset = Dataset::factory()->create();
        $value = DatasetValue::factory()->create([
            'dataset_id' => $dataset->id,
        ]);

        $response = $this->getJson("/api/datasets/{$dataset->id}");

        // 🔍 Debug: tampilkan isi response saat test jalan
        $response->dump();

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id' => $dataset->id,
                     'title' => $dataset->title,
                 ])
                 ->assertJsonFragment([
                     'value' => $value->value,
                 ]);
    }

    /** @test */
    public function it_can_download_dataset_as_json()
    {
        $dataset = Dataset::factory()->create();
        DatasetValue::factory()->create([
            'dataset_id' => $dataset->id,
            'value' => 123,
        ]);

        $response = $this->getJson("/api/datasets/{$dataset->id}/download/json");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'Nilai' => 123,
                 ]);
    }
}
