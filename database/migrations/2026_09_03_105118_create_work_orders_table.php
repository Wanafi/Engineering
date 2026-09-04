<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('wo_number', 50)->unique();
            $table->foreignId('divisis_id')->constrained('divisis')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('reported_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->enum('priority', ['low', 'medium', 'high', 'emergency'])->default('medium');
            $table->enum('type', ['corrective', 'preventive', 'breakdown', 'request'])->default('corrective');
            $table->text('description');
            $table->date('scheduled_date')->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->enum('status', ['open', 'in_progress', 'completed', 'overdue', 'cancelled'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('wo_number');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
