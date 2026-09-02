<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_templates', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Divisi (nullable, jika template berlaku umum atau spesifik divisi)
            $table->foreignId('division_id')
                ->nullable()
                ->constrained('divisis')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('division_id');
            $table->index('is_active');
        });

        Schema::create('checklist_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('checklist_template_id')
                ->constrained('checklist_templates')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('label', 255);
            $table->enum('type', [
                'condition',
                'yes_no',
                'text',
                'number',
                'photo',
            ])->default('condition');

            $table->json('options')->nullable();
            $table->boolean('is_required')->default(true);
            $table->integer('order')->default(0);

            $table->timestamps();

            $table->index('checklist_template_id');
            $table->index('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_questions');
        Schema::dropIfExists('checklist_templates');
    }
};
