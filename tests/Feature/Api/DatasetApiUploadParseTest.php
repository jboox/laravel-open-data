<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Category;
use App\Models\User;
use App\Models\Dataset;
use App\Models\DatasetValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DatasetApiUploadParseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_upload_and_parse_csv_into_dataset_values()
    {
        Storage::fake('public');

        $category = Category::factory()->create();
        $user = User::factory()->create();

        // Buat CSV dummy
        $csvContent = "date,region,value\n2023-01-01,1,95000\n2023-01-01,2,45000\n";
        $csvFile = UploadedFile::fake()->createWithContent('penduduk.csv', $csvContent);

        $response = $this->actingAs($user)
            ->postJson('/api/datasets', [
                'title' => 'Jumlah Penduduk 2023',
                'description' => 'Dataset penduduk tahun 2023',
                'category_id' => $category->id,
                'file' => $csvFile,
            ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Dataset uploaded and parsed successfully',
                 ]);

        // Pastikan file tersimpan
        Storage::disk('public')->assertExists('datasets/' . $csvFile->hashName());

        // Pastikan dataset ada
        $dataset = Dataset::first();
        $this->assertNotNull($dataset);

        // Pastikan values terisi
        $this->assertCount(2, $dataset->values);
        $this->assertEquals(95000, $dataset->values[0]->value);
        $this->assertEquals(45000, $dataset->values[1]->value);
    }
}
