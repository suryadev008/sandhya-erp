<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'half_day', 'holiday', 'sunday'])
                ->default('present');
            $table->enum('shift', ['day', 'night', 'A', 'B', 'general'])->nullable();
            $table->decimal('day_value', 3, 1)->default(1.0)
                ->comment('1.0 = full day, 0.5 = half day, 0 = absent');
            $table->string('remarks', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // One attendance record per employee per day
            $table->unique(['employee_id', 'date']);
            $table->index('date');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
