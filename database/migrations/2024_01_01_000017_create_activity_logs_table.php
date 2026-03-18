<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action', 100)
                ->comment('e.g. created, updated, deleted, approved, payroll_generated, slip_sent');
            $table->string('model_type', 100)->nullable()
                ->comment('e.g. App\\Models\\Payroll');
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('description', 255)->nullable();

            // JSON snapshots of old and new values for auditing
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            $table->string('ip_address', 45)->nullable();

            // No updated_at — logs are immutable
            $table->timestamp('created_at')->nullable();

            $table->index('user_id');
            $table->index(['model_type', 'model_id'], 'activity_logs_model_index');
            $table->index('action');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
