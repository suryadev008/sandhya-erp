<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_operation_rates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('cascade');

            $table->foreignId('operation_id')
                ->constrained('operations')
                ->onDelete('cascade');

            // Rate paid to THIS employee per piece for this operation
            $table->decimal('rate', 10, 2)->default(0);

            // Date from which this rate is effective (allows future-dated changes)
            $table->date('applicable_from');

            // Remark: "Joining rate", "Experience increment", etc.
            $table->string('remark', 255)->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // One employee+operation combination can have multiple rates over time
            $table->index(['employee_id', 'operation_id', 'applicable_from'], 'emp_op_rate_lookup');

            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_operation_rates');
    }
};
