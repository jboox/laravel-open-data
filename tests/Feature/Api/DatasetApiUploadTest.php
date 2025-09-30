<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DatasetApiUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_upload_a_dataset_file()
    {
        Storage::fake('public');

        $category = Category::factory()->create();
        $user = User::factory()->create();

        $file = UploadedFile::fake()->create('penduduk.csv', 100, 'text/csv');

        $response = $this->actingAs($user)
            ->postJson('/api/datasets', [
                'title' => 'Jumlah Penduduk 2023',
                'description' => 'Dataset penduduk tahun 2023',
                'category_id' => $category->id,
                'file' => $file,
            ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Dataset uploaded successfully',
                 ]);

        // Pastikan file tersimpan
        Storage::disk('public')->assertExists('datasets/' . $file->hashName());
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $response = $this->postJson('/api/datasets', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title', 'category_id', 'file']);
    }
}
