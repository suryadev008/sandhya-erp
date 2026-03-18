<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lathe_productions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('restrict');

            $table->foreignId('machine_id')
                ->nullable()
                ->constrained('machines')
                ->onDelete('set null');

            $table->date('date');
            $table->enum('shift', ['day', 'night', 'A', 'B', 'general'])->default('general');

            $table->foreignId('company_id')
                ->constrained('companies')
                ->onDelete('restrict');

            $table->foreignId('part_id')
                ->constrained('parts')
                ->onDelete('restrict');

            $table->foreignId('operation_id')
                ->constrained('operations')
                ->onDelete('restrict');

            $table->integer('qty')->default(0);

            // Rate is stored at time of entry — not fetched live — so historical records are unaffected
            // if the operation price changes later
            $table->decimal('rate', 10, 2)->default(0)
                ->comment('Fetched from operations at time of entry (snapshot)');

            $table->decimal('amount', 10, 2)->default(0)
                ->comment('qty × rate — calculated on save');

            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'date'], 'lathe_prod_emp_date_index');
            $table->index('date');
            $table->index('company_id');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lathe_productions');
    }
};
