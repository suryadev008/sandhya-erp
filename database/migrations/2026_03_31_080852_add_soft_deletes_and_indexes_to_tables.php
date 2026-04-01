<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Soft deletes ──────────────────────────────────────────────────────
        Schema::table('employees', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->softDeletes();
        });

        // ── Performance indexes ───────────────────────────────────────────────
        Schema::table('employees', function (Blueprint $table) {
            $table->index('status',        'idx_employees_status');
            $table->index('employee_type', 'idx_employees_type');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->index(['employee_id', 'year', 'month'], 'idx_payrolls_emp_year_month');
            $table->index('status', 'idx_payrolls_status');
        });

        Schema::table('lathe_productions', function (Blueprint $table) {
            $table->index(['employee_id', 'date'], 'idx_lathe_emp_date');
        });

        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->index(['employee_id', 'effect_from'], 'idx_salaries_emp_effect');
        });
    }

    public function down(): void
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->dropIndex('idx_salaries_emp_effect');
        });

        Schema::table('lathe_productions', function (Blueprint $table) {
            $table->dropIndex('idx_lathe_emp_date');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropIndex('idx_payrolls_emp_year_month');
            $table->dropIndex('idx_payrolls_status');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex('idx_employees_status');
            $table->dropIndex('idx_employees_type');
            $table->dropSoftDeletes();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
