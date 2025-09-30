<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Dataset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

class DatasetApiAdminTest extends TestCase
{
    use DatabaseMigrations; // ✅ ganti ini

    /** @test */
    public function admin_can_update_a_dataset()
    {
        Storage::fake('public');

        $category = Category::factory()->create();
        $dataset = Dataset::factory()->create([
            'category_id' => $category->id,
            'title' => 'Dataset Lama',
        ]);

        $admin = User::factory()->create();
        Sanctum::actingAs($admin);

        $newFile = UploadedFile::fake()->create('update.csv', 50, 'text/csv');

        $response = $this->putJson("/api/datasets/{$dataset->id}", [
            'title' => 'Dataset Baru',
            'description' => 'Deskripsi baru',
            'category_id' => $category->id,
            'file' => $newFile,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Dataset updated successfully',
                 ]);

        $this->assertDatabaseHas('datasets', [
            'id' => $dataset->id,
            'title' => 'Dataset Baru',
            'description' => 'Deskripsi baru',
        ]);
    }

    /** @test */
    public function admin_can_delete_a_dataset()
    {
        $category = Category::factory()->create();
        $dataset = Dataset::factory()->create([
            'category_id' => $category->id,
        ]);

        $admin = User::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->deleteJson("/api/datasets/{$dataset->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Dataset deleted successfully',
                 ]);

        $this->assertDatabaseMissing('datasets', ['id' => $dataset->id]);
    }

    /** @test */
    public function guest_cannot_update_or_delete_dataset()
    {
        $category = Category::factory()->create();
        $dataset = Dataset::factory()->create([
            'category_id' => $category->id,
        ]);

        // 🚫 Tanpa Sanctum (guest)
        $this->putJson("/api/datasets/{$dataset->id}", [
            'title' => 'Hack Update',
        ])->assertStatus(401);

        $this->deleteJson("/api/datasets/{$dataset->id}")
             ->assertStatus(401);
    }
}
