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
        Schema::create('owner_companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->unique();
            $table->string('company_code')->nullable();
            $table->enum('company_type', ['pvt_ltd', 'llp', 'partnership', 'sole_prop', 'public_ltd'])->nullable();
            $table->string('pan_number')->unique();
            $table->string('gstin')->unique();
            $table->date('incorporation_date');
            $table->enum('financial_year_start', ['april', 'january']);
            $table->string('base_currency')->nullable()->default('INR');
            $table->string('timezone')->nullable()->default('Asia/Kolkata');
            $table->string('date_format')->default('d/m/Y');
            $table->string('invoice_prefix');
            $table->enum('tax_regime', ['old', 'new'])->nullable();
            
            $table->string('reg_address_line1');
            $table->string('reg_city');
            $table->string('reg_state');
            $table->string('reg_pincode');
            $table->string('reg_country')->default('India');
            
            $table->string('industry_type')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('website')->nullable();
            $table->string('cin_number')->nullable();
            $table->string('tan_number')->nullable();
            $table->string('msme_reg_no')->nullable();
            $table->string('roc')->nullable();
            
            $table->string('corp_address_line1')->nullable();
            $table->string('corp_address_line2')->nullable();
            $table->string('corp_city')->nullable();
            $table->string('corp_state')->nullable();
            $table->string('corp_pincode')->nullable();
            
            $table->boolean('is_multi_branch')->default(false);
            $table->decimal('authorized_capital', 15, 2)->nullable();
            $table->decimal('paid_up_capital', 15, 2)->nullable();
            $table->integer('num_directors')->nullable();
            $table->string('auditor_name')->nullable();
            $table->string('auditor_firm')->nullable();
            $table->string('cs_name')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_companies');
    }
};
