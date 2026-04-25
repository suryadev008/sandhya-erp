@extends('layouts.app')

@section('title', config('app.name') . ' | Add Owner Company')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Add Owner Company</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('my-company.index') }}">My Company</a></li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Errors!</h5>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('my-company.store') }}" method="POST" enctype="multipart/form-data" id="companyForm" novalidate>
            @csrf
            
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="company-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-info" data-toggle="pill" href="#content-info" role="tab">Company Info</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-bank" data-toggle="pill" href="#content-bank" role="tab">Bank Accounts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-contact" data-toggle="pill" href="#content-contact" role="tab">Contacts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-erp" data-toggle="pill" href="#content-erp" role="tab">ERP & Statutory</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="company-tabs-content">
                        <!-- TAB 1: COMPANY INFO -->
                        <div class="tab-pane fade show active" id="content-info" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Company Name <span class="text-danger">*</span></label>
                                    <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Company Code</label>
                                    <input type="text" name="company_code" class="form-control" value="{{ old('company_code') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Company Type</label>
                                    <select name="company_type" class="form-control">
                                        <option value="">Select Type</option>
                                        @foreach(config('company.types') as $key => $val)
                                            <option value="{{ $key }}" {{ old('company_type') == $key ? 'selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>PAN Number <span class="text-danger">*</span></label>
                                    <input type="text" id="pan_number" name="pan_number" class="form-control" value="{{ old('pan_number') }}" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>GSTIN <span class="text-danger">*</span></label>
                                    <input type="text" id="gstin" name="gstin" class="form-control" value="{{ old('gstin') }}" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Incorporation Date <span class="text-danger">*</span></label>
                                    <input type="date" name="incorporation_date" class="form-control" value="{{ old('incorporation_date') }}" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Industry Type</label>
                                    <select name="industry_type" class="form-control">
                                        <option value="">Select Industry</option>
                                        @foreach(config('company.industry_types') as $ind)
                                            <option value="{{ $ind }}" {{ old('industry_type') == $ind ? 'selected' : '' }}>{{ $ind }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-12"><hr><h6 class="font-weight-bold">Registered Address <span class="text-danger">*</span></h6></div>
                                
                                <div class="col-md-6 form-group">
                                    <label>Address Line 1 <span class="text-danger">*</span></label>
                                    <input type="text" name="reg_address_line1" class="form-control" value="{{ old('reg_address_line1') }}" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>City <span class="text-danger">*</span></label>
                                    <input type="text" name="reg_city" class="form-control" value="{{ old('reg_city') }}" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>State <span class="text-danger">*</span></label>
                                    <select name="reg_state" class="form-control" required>
                                        <option value="">Select State</option>
                                        @foreach(config('company.states') as $state)
                                            <option value="{{ $state }}" {{ old('reg_state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Pincode <span class="text-danger">*</span></label>
                                    <input type="text" name="reg_pincode" class="form-control" value="{{ old('reg_pincode') }}" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Country</label>
                                    <input type="text" name="reg_country" class="form-control" value="{{ old('reg_country', 'India') }}" readonly>
                                </div>

                                <div class="col-12"><hr><h6 class="font-weight-bold">Corporate Address (Optional)</h6></div>
                                
                                <div class="col-md-6 form-group">
                                    <label>Address Line 1</label>
                                    <input type="text" name="corp_address_line1" class="form-control" value="{{ old('corp_address_line1') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>City</label>
                                    <input type="text" name="corp_city" class="form-control" value="{{ old('corp_city') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>State</label>
                                    <select name="corp_state" class="form-control">
                                        <option value="">Select State</option>
                                        @foreach(config('company.states') as $state)
                                            <option value="{{ $state }}" {{ old('corp_state') == $state ? 'selected' : '' }}>{{ $state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Pincode</label>
                                    <input type="text" name="corp_pincode" class="form-control" value="{{ old('corp_pincode') }}">
                                </div>

                                <div class="col-12"><hr><h6 class="font-weight-bold">Other Details</h6></div>
                                <div class="col-md-3 form-group">
                                    <label>Website</label>
                                    <input type="url" name="website" class="form-control" value="{{ old('website') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Company Logo</label>
                                    <input type="file" id="logo_path" name="logo_path" class="form-control-file" accept="image/png, image/jpeg, image/jpg">
                                    <img id="logo-preview" src="" alt="Preview" class="mt-2 img-thumbnail" style="display:none; max-height:80px;">
                                </div>
                                <div class="col-md-3 form-group">
                                    <div class="custom-control custom-switch mt-4 pt-2">
                                      <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                      <label class="custom-control-label" for="is_active">Active Status</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: BANK ACCOUNTS -->
                        <div class="tab-pane fade" id="content-bank" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Bank Name *</th>
                                            <th>A/c Holder Name *</th>
                                            <th>A/c Number *</th>
                                            <th>IFSC Code *</th>
                                            <th>A/c Type *</th>
                                            <th>Branch</th>
                                            <th>SWIFT Code</th>
                                            <th>Primary</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="banks-tbl-body">
                                        @if(old('bank_accounts'))
                                            @foreach(old('bank_accounts') as $index => $bank)
                                                @include('owner_companies.partials._bank_row', ['index' => $index, 'bank' => (object)$bank])
                                            @endforeach
                                        @else
                                            @include('owner_companies.partials._bank_row', ['index' => 0])
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-info" id="add-bank-btn"><i class="fas fa-plus"></i> Add Bank Account</button>
                        </div>

                        <!-- TAB 3: CONTACTS -->
                        <div class="tab-pane fade" id="content-contact" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Name *</th>
                                            <th>Designation</th>
                                            <th>Phone *</th>
                                            <th>Alt Phone</th>
                                            <th>Email *</th>
                                            <th>Support Email</th>
                                            <th>Primary</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contacts-tbl-body">
                                        @if(old('contacts'))
                                            @foreach(old('contacts') as $index => $contact)
                                                @include('owner_companies.partials._contact_row', ['index' => $index, 'contact' => (object)$contact])
                                            @endforeach
                                        @else
                                            @include('owner_companies.partials._contact_row', ['index' => 0])
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-sm btn-info" id="add-contact-btn"><i class="fas fa-plus"></i> Add Contact</button>
                        </div>

                        <!-- TAB 4: ERP CONFIG -->
                        <div class="tab-pane fade" id="content-erp" role="tabpanel">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label>Invoice Prefix <span class="text-danger">*</span></label>
                                    <input type="text" name="invoice_prefix" class="form-control" value="{{ old('invoice_prefix') }}" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Financial Year Start <span class="text-danger">*</span></label>
                                    <select name="financial_year_start" class="form-control" required>
                                        <option value="april" {{ old('financial_year_start') == 'april' ? 'selected' : '' }}>April</option>
                                        <option value="january" {{ old('financial_year_start') == 'january' ? 'selected' : '' }}>January</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Base Currency</label>
                                    <select name="base_currency" class="form-control">
                                        @foreach(config('company.currencies') as $key => $val)
                                            <option value="{{ $key }}" {{ old('base_currency', 'INR') == $key ? 'selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Timezone</label>
                                    <select name="timezone" class="form-control">
                                        @foreach(config('company.timezones') as $key => $val)
                                            <option value="{{ $key }}" {{ old('timezone', 'Asia/Kolkata') == $key ? 'selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Tax Regime</label>
                                    <select name="tax_regime" class="form-control">
                                        <option value="">Select</option>
                                        <option value="old" {{ old('tax_regime') == 'old' ? 'selected' : '' }}>Old Regime</option>
                                        <option value="new" {{ old('tax_regime') == 'new' ? 'selected' : '' }}>New Regime</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Multi Branch Support</label>
                                    <div class="custom-control custom-switch mt-2">
                                      <input type="checkbox" name="is_multi_branch" class="custom-control-input" id="is_multi_branch" value="1" {{ old('is_multi_branch') ? 'checked' : '' }}>
                                      <label class="custom-control-label" for="is_multi_branch">Enabled</label>
                                    </div>
                                </div>

                                <div class="col-12"><hr><h6 class="font-weight-bold">Statutory Details</h6></div>
                                
                                <div class="col-md-3 form-group">
                                    <label>CIN Number</label>
                                    <input type="text" name="cin_number" class="form-control" value="{{ old('cin_number') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>TAN Number</label>
                                    <input type="text" name="tan_number" class="form-control" value="{{ old('tan_number') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>MSME Reg No</label>
                                    <input type="text" name="msme_reg_no" class="form-control" value="{{ old('msme_reg_no') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>ROC</label>
                                    <input type="text" name="roc" class="form-control" value="{{ old('roc') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Authorized Capital</label>
                                    <input type="number" step="0.01" name="authorized_capital" class="form-control" value="{{ old('authorized_capital') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Paid Up Capital</label>
                                    <input type="number" step="0.01" name="paid_up_capital" class="form-control" value="{{ old('paid_up_capital') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Number of Directors</label>
                                    <input type="number" name="num_directors" class="form-control" value="{{ old('num_directors') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Auditor Name</label>
                                    <input type="text" name="auditor_name" class="form-control" value="{{ old('auditor_name') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Auditor Firm</label>
                                    <input type="text" name="auditor_firm" class="form-control" value="{{ old('auditor_firm') }}">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>CS Name</label>
                                    <input type="text" name="cs_name" class="form-control" value="{{ old('cs_name') }}">
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer text-right">
                    <a href="{{ route('my-company.index') }}" class="btn btn-default">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Company</button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Templates for JS -->
<template id="bank-row-template">
    @include('owner_companies.partials._bank_row', ['index' => '__INDEX__', 'bank' => null])
</template>
<template id="contact-row-template">
    @include('owner_companies.partials._contact_row', ['index' => '__INDEX__', 'contact' => null])
</template>

@endsection

@push('scripts')
    @include('owner_companies.partials._form_scripts')
@endpush
