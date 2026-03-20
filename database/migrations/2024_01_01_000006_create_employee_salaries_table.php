<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->decimal('per_day', 10, 2)->comment('Per day salary');
            $table->decimal('per_month', 10, 2)->comment('Per month salary (30 days)');
            $table->date('effect_from')->comment('Salary effective from this date');
            $table->string('remark', 255)->nullable()->comment('e.g. Joining Salary, Annual Increment');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            $table->index(['employee_id', 'effect_from']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_salaries');
    }
};
