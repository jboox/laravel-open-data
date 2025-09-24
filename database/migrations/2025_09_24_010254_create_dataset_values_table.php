<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dataset_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dataset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('region_id')->nullable()->constrained()->cascadeOnDelete();
            $table->date('date')->nullable(); // bisa tahun/bulan/hari
            $table->decimal('value', 15, 2)->nullable(); // fleksibel untuk angka besar
            $table->json('meta')->nullable(); // opsional untuk satuan, sumber, dsb.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dataset_values');
    }
};
