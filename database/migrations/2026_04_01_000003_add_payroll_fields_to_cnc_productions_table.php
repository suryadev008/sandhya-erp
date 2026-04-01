<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cnc_productions', function (Blueprint $table) {
            // Target for this specific entry (defaults from employee.cnc_target_per_shift)
            $table->unsignedSmallInteger('target_qty')
                ->default(90)
                ->after('production_qty')
                ->comment('Target for this shift — defaults from employee setting');

            // Pieces above target = incentive pieces (computed on save)
            $table->unsignedInteger('incentive_qty')
                ->default(0)
                ->after('target_qty')
                ->comment('MAX(0, production_qty - target_qty)');

            // Rate per piece — used only when employee.cnc_payment_type = per_piece
            $table->decimal('rate_per_piece', 10, 2)
                ->default(0)
                ->after('incentive_qty')
                ->comment('Applicable for per_piece payment model only');

            // Incentive rate per piece above target — used only for day_rate model
            $table->decimal('incentive_rate', 8, 2)
                ->default(0)
                ->after('rate_per_piece')
                ->comment('Incentive per piece above target — day_rate model only');

            // Calculated amount for this entry
            // day_rate:  incentive_qty × incentive_rate
            // per_piece: production_qty × rate_per_piece
            $table->decimal('amount', 10, 2)
                ->default(0)
                ->after('incentive_rate')
                ->comment('Calculated production amount for this entry');
        });
    }

    public function down(): void
    {
        Schema::table('cnc_productions', function (Blueprint $table) {
            $table->dropColumn([
                'target_qty', 'incentive_qty',
                'rate_per_piece', 'incentive_rate', 'amount',
            ]);
        });
    }
};
