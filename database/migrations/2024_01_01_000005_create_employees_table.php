<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_code', 20)->unique()->comment('e.g. EMP001');
            $table->string('name');
            $table->string('aadhar_no', 12)->nullable()->unique();
            $table->string('mobile_primary', 10)->comment('Required');
            $table->string('mobile_secondary', 10)->nullable()->comment('Optional');
            $table->string('whatsapp_no', 10)->nullable()->comment('For salary slip sharing');
            $table->string('upi_number', 50)->nullable();
            $table->text('permanent_address')->nullable();
            $table->text('present_address')->comment('Required');
            $table->string('aadhar_image')->nullable()->comment('File path in storage');
            $table->string('bank_account_no', 20)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('ifsc_code', 11)->nullable();
            $table->enum('employee_type', ['lathe', 'cnc', 'both'])->default('lathe')
                ->comment('Determines payroll calculation method');
            $table->decimal('experience_years', 4, 1)->default(0)->nullable();
            $table->date('joining_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index('emp_code');
            $table->index('mobile_primary');
            $table->index('mobile_secondary');
            $table->index('whatsapp_no');
            $table->index('name');
            $table->index('status');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->nullable()->after('id'); // ← यह line ADD की
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id'); // ← यह भी add करो
        });

        Schema::dropIfExists('employees');
    }
};