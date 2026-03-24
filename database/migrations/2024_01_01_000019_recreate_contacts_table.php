<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('contacts');

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('person_name');
            $table->string('contact_no', 20);
            $table->string('whatsapp_no', 20)->nullable();
            $table->string('upi_no', 100)->nullable();
            $table->text('bank_details')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
