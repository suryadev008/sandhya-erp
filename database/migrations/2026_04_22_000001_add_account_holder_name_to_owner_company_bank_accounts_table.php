<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('owner_company_bank_accounts', function (Blueprint $table) {
            $table->string('account_holder_name')->after('bank_name');
        });
    }

    public function down(): void
    {
        Schema::table('owner_company_bank_accounts', function (Blueprint $table) {
            $table->dropColumn('account_holder_name');
        });
    }
};
