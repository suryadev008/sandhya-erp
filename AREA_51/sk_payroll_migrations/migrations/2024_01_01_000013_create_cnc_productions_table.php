<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cnc_productions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('restrict');

            // CNC machine number — stored as FK to machines table
            $table->foreignId('machine_id')
                ->nullable()
                ->constrained('machines')
                ->onDelete('set null');

            $table->date('date');
            $table->enum('shift', ['day', 'night', 'A', 'B', 'general'])->default('general');

            $table->foreignId('company_id')
                ->constrained('companies')
                ->onDelete('restrict');

            $table->string('job_name')->nullable();

            $table->foreignId('part_id')
                ->constrained('parts')
                ->onDelete('restrict');

            $table->enum('operation_type', [
                'full_finish',
                'finish_first_side',
                'finish_second_side',
            ]);

            // Actual time taken per product cycle (HH:MM:SS)
            $table->time('actual_cycle_time')->nullable();

            $table->integer('production_qty')->default(0);

            // Auto-calculated: 1 if production_qty >= min_target_qty from employee_cnc_rates
            $table->boolean('target_met')->default(false)
                ->comment('Auto-calculated when saving');

            // If set, day is fully excused regardless of qty
            $table->enum('downtime_type', ['power_cut', 'machine_breakdown', 'other'])
                ->nullable()
                ->comment('Excused reason — full day pay even if target not met');

            $table->integer('downtime_minutes')->nullable()->default(0);

            // Sunday flag — auto-set based on date, contributes 0.5 day
            $table->boolean('is_sunday')->default(false)
                ->comment('Sunday = half day pay');

            $table->boolean('is_half_day')->default(false);

            $table->text('remark')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'date'], 'cnc_prod_emp_date_index');
            $table->index('date');
            $table->index('machine_id');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cnc_productions');
    }
};
