<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')
                ->constrained('companies')
                ->onDelete('cascade');
            $table->string('part_number', 100);
            $table->string('part_name')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Part number is unique per company (same part_number can exist for different companies)
            $table->unique(['company_id', 'part_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parts');
    }
};
