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
        Schema::create('owner_company_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_company_id')->constrained('owner_companies')->cascadeOnDelete();
            $table->string('contact_person');
            $table->string('designation')->nullable();
            $table->string('phone');
            $table->string('alternate_phone')->nullable();
            $table->string('email');
            $table->string('support_email')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_company_contacts');
    }
};
