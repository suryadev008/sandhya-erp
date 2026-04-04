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
        Schema::table('contacts', function (Blueprint $table) {
            // Drop the old string category column, replace with FK
            $table->dropColumn('category');
            $table->unsignedBigInteger('contact_category_id')->nullable()->after('area');
            $table->foreign('contact_category_id')
                  ->references('id')->on('contact_categories')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['contact_category_id']);
            $table->dropColumn('contact_category_id');
            $table->string('category', 100)->nullable()->after('area');
        });
    }
};
