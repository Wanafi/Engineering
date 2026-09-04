<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenance_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('divisis_id')->constrained('divisis')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('work_order_id')->nullable()->constrained('work_orders')->cascadeOnUpdate()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('report_date');
            $table->decimal('cost', 15, 2)->default(0);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
            $table->index('report_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_reports');
    }
};
