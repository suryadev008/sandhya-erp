<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('cnc_incentive_rate', 8, 2)->nullable()->default(0)->change();
            $table->unsignedSmallInteger('cnc_target_per_shift')->nullable()->default(90)->change();
            $table->enum('cnc_payment_type', ['day_rate', 'per_piece'])->nullable()->default('day_rate')->change();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('cnc_incentive_rate', 8, 2)->default(0)->change();
            $table->unsignedSmallInteger('cnc_target_per_shift')->default(90)->change();
            $table->enum('cnc_payment_type', ['day_rate', 'per_piece'])->default('day_rate')->change();
        });
    }
};
