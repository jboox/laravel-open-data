<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Category;
use App\Models\User;
use App\Models\Dataset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

class DatasetApiUploadRegionNameTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_upload_and_parse_csv_with_region_name()
    {
        Storage::fake('public');

        $category = Category::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $csvContent = "date,region,value\n2023-01-01,Alok,95000\n2023-01-01,Maumere,45000\n";
        $csvFile = UploadedFile::fake()->createWithContent('penduduk.csv', $csvContent);

        $response = $this->postJson('/api/datasets', [
            'title' => 'Jumlah Penduduk 2023',
            'description' => 'Dataset penduduk tahun 2023',
            'category_id' => $category->id,
            'file' => $csvFile,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['success', 'message', 'data']);

        $dataset = Dataset::first();
        $this->assertNotNull($dataset);

        $this->assertDatabaseHas('regions', ['name' => 'Alok']);
        $this->assertDatabaseHas('regions', ['name' => 'Maumere']);
        $this->assertCount(2, $dataset->values);
    }
}
