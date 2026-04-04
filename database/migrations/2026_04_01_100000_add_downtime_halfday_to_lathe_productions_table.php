<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lathe_productions', function (Blueprint $table) {
            $table->string('downtime_type')->nullable()->after('remarks');
            $table->unsignedSmallInteger('downtime_minutes')->nullable()->after('downtime_type');
            $table->boolean('is_half_day')->default(false)->after('downtime_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('lathe_productions', function (Blueprint $table) {
            $table->dropColumn(['downtime_type', 'downtime_minutes', 'is_half_day']);
        });
    }
};
