<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Category;
use App\Models\User;
use App\Models\Dataset;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DatasetApiUploadExcelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_upload_and_parse_excel_into_dataset_values()
    {
        Storage::fake('public');

        $category = Category::factory()->create();
        $user = User::factory()->create();

        // 🚀 Buat file Excel dummy
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([
            ['date', 'region', 'value'],
            ['2023-01-01', 'Sikka', 95000],
            ['2023-01-01', 'Alok', 45000],
        ]);

        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        $excelFile = new UploadedFile(
            $tempFile,
            'penduduk.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        // Upload via API
        $response = $this->actingAs($user)
            ->postJson('/api/datasets', [
                'title' => 'Jumlah Penduduk Excel 2023',
                'description' => 'Dataset penduduk dari Excel',
                'category_id' => $category->id,
                'file' => $excelFile,
            ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Dataset uploaded and parsed successfully',
                 ]);

        // Pastikan dataset terbentuk
        $dataset = Dataset::first();
        $this->assertNotNull($dataset);

        // Pastikan regions auto-terbentuk
        $this->assertDatabaseHas('regions', ['name' => 'Sikka']);
        $this->assertDatabaseHas('regions', ['name' => 'Alok']);

        // Pastikan values terisi
        $this->assertCount(2, $dataset->values);
        $this->assertEquals(95000, $dataset->values[0]->value);
        $this->assertEquals(45000, $dataset->values[1]->value);
    }
}
