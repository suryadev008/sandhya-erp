<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('gst_no', 15)->nullable()->unique()->after('remark');
            $table->string('gst_trade_name')->nullable()->after('gst_no');
            $table->string('gst_legal_name')->nullable()->after('gst_trade_name');
            $table->string('gst_status', 50)->nullable()->after('gst_legal_name');
            $table->string('gst_state', 100)->nullable()->after('gst_status');
            $table->string('gst_pan', 10)->nullable()->after('gst_state');
            $table->string('gst_registration_date', 20)->nullable()->after('gst_pan');
            $table->string('gst_business_type', 100)->nullable()->after('gst_registration_date');
            $table->timestamp('gst_verified_at')->nullable()->after('gst_business_type');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'gst_no', 'gst_trade_name', 'gst_legal_name', 'gst_status',
                'gst_state', 'gst_pan', 'gst_registration_date',
                'gst_business_type', 'gst_verified_at',
            ]);
        });
    }
};
