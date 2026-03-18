<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('restrict');

            $table->tinyInteger('month')->unsigned()->comment('1 = January … 12 = December');
            $table->smallInteger('year')->unsigned();

            // ── Attendance summary ──────────────────────────────────────
            $table->integer('total_working_days')->default(0)
                ->comment('Calendar working days in the month');
            $table->decimal('present_days', 5, 1)->default(0)
                ->comment('Includes 0.5 for half days');
            $table->decimal('sunday_half_days', 5, 1)->default(0);

            // ── Lathe earnings ──────────────────────────────────────────
            $table->decimal('total_lathe_amount', 12, 2)->default(0)
                ->comment('SUM of lathe_productions.amount for the month');

            // ── CNC earnings ────────────────────────────────────────────
            $table->decimal('total_cnc_days', 5, 1)->default(0)
                ->comment('Billable days calculated by target logic');
            $table->decimal('cnc_rate_per_day', 10, 2)->default(0)
                ->comment('Rate at time of payroll generation (snapshot)');
            $table->decimal('total_cnc_amount', 12, 2)->default(0)
                ->comment('total_cnc_days × cnc_rate_per_day');

            // ── Extras & deductions ─────────────────────────────────────
            $table->decimal('extra_payment_total', 12, 2)->default(0);
            $table->decimal('gross_amount', 12, 2)->default(0);
            $table->decimal('deductions', 12, 2)->default(0);
            $table->text('deduction_remarks')->nullable();
            $table->decimal('net_amount', 12, 2)->default(0);

            // ── Workflow status ─────────────────────────────────────────
            $table->enum('status', ['draft', 'approved', 'paid'])->default('draft');

            $table->unsignedBigInteger('generated_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            // One payroll record per employee per month/year
            $table->unique(['employee_id', 'month', 'year'], 'payrolls_emp_month_year_unique');
            $table->index(['month', 'year']);
            $table->index('status');

            $table->foreign('generated_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
