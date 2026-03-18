<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_cnc_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('cascade');
            $table->decimal('rate_per_day', 10, 2)->comment('Daily wage in INR');
            $table->integer('min_target_qty')->default(80)->comment('Min products required per 8 hrs');
            $table->integer('max_target_qty')->default(100)->comment('Max target per 8 hrs');
            $table->date('applicable_from');
            $table->date('applicable_to')->nullable()->comment('NULL means currently active rate');
            $table->string('notes', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'applicable_from', 'applicable_to'], 'cnc_rates_emp_dates_index');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_cnc_rates');
    }
};
