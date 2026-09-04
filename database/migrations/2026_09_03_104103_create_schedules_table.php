<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divisis_id')->constrained('divisis')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('schedule_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled', 'overdue'])->default('scheduled');
            $table->timestamps();
            $table->softDeletes();

            $table->index('schedule_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
