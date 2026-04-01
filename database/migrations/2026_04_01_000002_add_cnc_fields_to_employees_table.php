<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // CNC payment model: day_rate = per-day salary + incentive, per_piece = like lathe
            $table->enum('cnc_payment_type', ['day_rate', 'per_piece'])
                ->default('day_rate')
                ->after('employee_type')
                ->comment('day_rate: fixed day salary + incentive above target; per_piece: paid per piece like lathe');

            // Standard target pieces per shift (used only for day_rate model)
            $table->unsignedSmallInteger('cnc_target_per_shift')
                ->default(90)
                ->after('cnc_payment_type')
                ->comment('Standard target pieces per 8hr shift for incentive calculation');

            // Extra amount paid per piece produced above target (day_rate model only)
            $table->decimal('cnc_incentive_rate', 8, 2)
                ->default(0)
                ->after('cnc_target_per_shift')
                ->comment('Incentive rate per piece produced above target');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['cnc_payment_type', 'cnc_target_per_shift', 'cnc_incentive_rate']);
        });
    }
};
