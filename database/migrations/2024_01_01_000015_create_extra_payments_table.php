<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extra_payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payroll_id')
                ->constrained('payrolls')
                ->onDelete('cascade');

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('restrict');

            // Denormalised for quick monthly reports without joining payrolls
            $table->tinyInteger('month')->unsigned();
            $table->smallInteger('year')->unsigned();

            $table->string('payment_name')
                ->comment('e.g. Diwali Bonus, Advance, Travel Allowance');
            $table->decimal('amount', 10, 2)->default(0);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'month', 'year']);

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extra_payments');
    }
};
