<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operation_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')->constrained('operations')->restrictOnDelete();
            $table->decimal('price', 10, 2)->comment('Rate per piece in INR');
            $table->date('applicable_from')->comment('Price effective from this date');
            $table->text('remark')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['operation_id', 'applicable_from']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operation_prices');
    }
};
