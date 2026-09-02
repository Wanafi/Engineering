<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_executions', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('checklist_template_id')
                ->constrained('checklist_templates')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('unit_id')
                ->constrained('units')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('event_id')
                ->nullable()
                ->constrained('events')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('technician_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->enum('status', [
                'draft',
                'submitted',
                'supervisor_approved',
                'completed',
                'rejected',
            ])->default('draft');

            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->foreignId('supervisor_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamp('supervisor_approved_at')->nullable();

            $table->foreignId('head_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->timestamp('head_approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('checklist_template_id');
            $table->index('unit_id');
            $table->index('technician_id');
        });

        Schema::create('checklist_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('checklist_execution_id')
                ->constrained('checklist_executions')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // Snapshot fields
            $table->string('question_snapshot', 255);
            $table->string('type_snapshot', 50);
            $table->json('options_snapshot')->nullable();
            $table->boolean('is_required_snapshot')->default(true);
            $table->integer('order')->default(0);

            // Answer fields
            $table->text('answer_text')->nullable();
            $table->string('answer_photo_path', 255)->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('checklist_execution_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_answers');
        Schema::dropIfExists('checklist_executions');
    }
};
