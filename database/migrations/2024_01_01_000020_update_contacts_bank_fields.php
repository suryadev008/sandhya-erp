<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('bank_details');
            $table->string('account_holder_name', 255)->nullable()->after('upi_no');
            $table->string('account_no', 50)->nullable()->after('account_holder_name');
            $table->string('ifsc_code', 20)->nullable()->after('account_no');
            $table->string('bank_name', 100)->nullable()->after('ifsc_code');
            $table->string('branch', 100)->nullable()->after('bank_name');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['account_holder_name', 'account_no', 'ifsc_code', 'bank_name', 'branch']);
            $table->text('bank_details')->nullable();
        });
    }
};
