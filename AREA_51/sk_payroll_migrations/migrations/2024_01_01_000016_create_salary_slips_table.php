<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_slips', function (Blueprint $table) {
            $table->id();

            // One slip per payroll
            $table->foreignId('payroll_id')
                ->unique()
                ->constrained('payrolls')
                ->onDelete('cascade');

            // Human-readable slip reference e.g. SLIP-2025-01-EMP001
            $table->string('slip_number', 50)->unique();

            // Path to the generated PDF in storage (e.g. salary_slips/2025/01/EMP001.pdf)
            $table->string('pdf_path')->nullable();

            // WhatsApp sending info
            $table->boolean('whatsapp_sent')->default(false);
            $table->timestamp('whatsapp_sent_at')->nullable();
            $table->string('sent_to_number', 10)->nullable()
                ->comment('WhatsApp number used at time of sending');

            $table->unsignedBigInteger('sent_by')->nullable();
            $table->timestamps();

            $table->foreign('sent_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_slips');
    }
};
