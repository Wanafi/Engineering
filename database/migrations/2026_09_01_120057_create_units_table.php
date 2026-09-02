<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();

            // Relasi ke Divisi
            $table->foreignId('divisis_id')
                ->constrained('divisis')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            // Identitas Unit
            $table->string('unit_code', 50)->unique();
            $table->string('unit_name', 150);

            // Informasi Lokasi
            $table->string('location', 150)->nullable();
            $table->string('floor', 50)->nullable();
            $table->string('area', 100)->nullable();

            // Informasi Tambahan
            $table->text('description')->nullable();

            // Status
            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');

            $table->timestamps();
            $table->softDeletes();

            // Index
            $table->index('divisis_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};