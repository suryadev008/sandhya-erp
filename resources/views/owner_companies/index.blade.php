@extends('layouts.app')

@section('title', config('app.name') . ' | My Company')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">My Company</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">My Company</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h5><i class="icon fas fa-check"></i> Success!</h5>
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('info') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h5><i class="icon fas fa-ban"></i> Errors!</h5>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('my-company.update', $company->id) }}" method="POST" enctype="multipart/form-data" id="companyForm">
            @csrf
            @method('PUT')

            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="company-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="pill" href="#tab-info" role="tab">
                                <i class="fas fa-building mr-1"></i> Company Info
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#tab-bank" role="tab">
                                <i class="fas fa-university mr-1"></i> Bank Accounts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#tab-contact" role="tab">
                                <i class="fas fa-address-book mr-1"></i> Contacts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#tab-erp" role="tab">
                                <i class="fas fa-cog mr-1"></i> ERP & Statutory
                            </a>
                        </li>
                        <li class="nav-item ml-auto d-flex align-items-center pr-3">
                            <a href="{{ route('my-company.show', $company->id) }}" class="btn btn-info btn-sm mr-1">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <button type="button" id="btn-edit" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" id="btn-cancel" class="btn btn-default btn-sm d-none">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" id="btn-save" class="btn btn-success btn-sm ml-1 d-none">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="company-tabs-content">

                        {{-- ============================== --}}
                        {{-- TAB 1: COMPANY INFO --}}
                        {{-- ============================== --}}
                        <div class="tab-pane fade show active" id="tab-info" role="tabpanel">

                            <!-- VIEW MODE -->
                            <div class="view-section">
                                <div class="row align-items-center mb-3">
                                    <div class="col-auto">
                                        @if($company->logo_url)
                                            <img src="{{ asset($company->logo_url) }}" alt="Logo" class="img-thumbnail" style="max-height:80px;">
                                        @else
                                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" style="width:80px;height:80px;font-size:28px;font-weight:bold;">
                                                {{ strtoupper(substr($company->company_name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <h4 class="mb-0">{{ $company->company_name }}</h4>
                                        <small class="text-muted">{{ config('company.types.'.$company->company_type, $company->company_type) }}</small>
                                        <span class="ml-2">
                                            {!! $company->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>' !!}
                                        </span>
                                    </div>
                                </div>

                                <h6 class="text-primary font-weight-bold border-bottom pb-1 mb-3">General Information</h6>
                                <div class="row mb-3">
                                    <div class="col-md-3"><strong>Company Code</strong><p class="text-muted">{{ $company->company_code ?: 'N/A' }}</p></div>
                                    <div class="col-md-3"><strong>PAN Number</strong><p class="text-muted">{{ $company->pan_number }}</p></div>
                                    <div class="col-md-3"><strong>GSTIN</strong><p class="text-muted">{{ $company->gstin }}</p></div>
                                    <div class="col-md-3"><strong>Incorporation Date</strong><p class="text-muted">{{ $company->incorporation_date ? $company->incorporation_date->format('d M Y') : 'N/A' }}</p></div>
                                    <div class="col-md-3"><strong>Industry Type</strong><p class="text-muted">{{ $company->industry_type ?: 'N/A' }}</p></div>
                                    <div class="col-md-3"><strong>Website</strong><p class="text-muted">{{ $company->website ?: 'N/A' }}</p></div>
                                </div>

                                <h6 class="text-primary font-weight-bold border-bottom pb-1 mb-3">Registered Address</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <address class="text-muted">
                                            {{ $company->reg_address_line1 }}<br>
                                            {{ $company->reg_city }}, {{ $company->reg_state }} - {{ $company->reg_pincode }}<br>
                                            {{ $company->reg_country }}
                                        </address>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Corporate Address</strong>
                                        <address class="text-muted">
                                            @if($company->corp_address_line1)
                                                {{ $company->corp_address_line1 }}<br>
                                                {{ $company->corp_city }}, {{ $company->corp_state }} - {{ $company->corp_pincode }}
                                            @else
                                                N/A
                                            @endif
                                        </address>
                                    </div>
                                </div>
                            </div><!-- end view-section -->

                            <!-- EDIT MODE -->
                            <div class="edit-section" style="display:none;">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Company Name <span class="text-danger">*</span></label>
                                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $company->company_name) }}" required>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Company Code</label>
                                        <input type="text" name="company_code" class="form-control" value="{{ old('company_code', $company->company_code) }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Company Type</label>
                                        <select name="company_type" class="form-control">
                                            <option value="">Select Type</option>
                                            @foreach(config('company.types') as $key => $val)
                                                <option value="{{ $key }}" {{ old('company_type', $company->company_type) == $key ? 'selected' : '' }}>{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>PAN Number <span class="text-danger">*</span></label>
                                        <input type="text" id="pan_number" name="pan_number" class="form-control text-uppercase" value="{{ old('pan_number', $company->pan_number) }}" required>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>GSTIN <span class="text-danger">*</span></label>
                                        <input type="text" id="gstin" name="gstin" class="form-control text-uppercase" value="{{ old('gstin', $company->gstin) }}" required>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Incorporation Date <span class="text-danger">*</span></label>
                                        <input type="date" name="incorporation_date" class="form-control" value="{{ old('incorporation_date', $company->incorporation_date ? $company->incorporation_date->format('Y-m-d') : '') }}" required>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Industry Type</label>
                                        <select name="industry_type" class="form-control">
                                            <option value="">Select Industry</option>
                                            @foreach(config('company.industry_types') as $ind)
                                                <option value="{{ $ind }}" {{ old('industry_type', $company->industry_type) == $ind ? 'selected' : '' }}>{{ $ind }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12"><hr><h6 class="font-weight-bold">Registered Address <span class="text-danger">*</span></h6></div>
                                    <div class="col-md-6 form-group">
                                        <label>Address Line 1 <span class="text-danger">*</span></label>
                                        <input type="text" name="reg_address_line1" class="form-control" value="{{ old('reg_address_line1', $company->reg_address_line1) }}" required>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>City <span class="text-danger">*</span></label>
                                        <input type="text" name="reg_city" class="form-control" value="{{ old('reg_city', $company->reg_city) }}" required>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>State <span class="text-danger">*</span></label>
                                        <select name="reg_state" class="form-control" required>
                                            <option value="">Select State</option>
                                            @foreach(config('company.states') as $state)
                                                <option value="{{ $state }}" {{ old('reg_state', $company->reg_state) == $state ? 'selected' : '' }}>{{ $state }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Pincode <span class="text-danger">*</span></label>
                                        <input type="text" name="reg_pincode" class="form-control" value="{{ old('reg_pincode', $company->reg_pincode) }}" required>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Country</label>
                                        <input type="text" name="reg_country" class="form-control" value="{{ old('reg_country', $company->reg_country) }}" readonly>
                                    </div>

                                    <div class="col-12"><hr><h6 class="font-weight-bold">Corporate Address (Optional)</h6></div>
                                    <div class="col-md-6 form-group">
                                        <label>Address Line 1</label>
                                        <input type="text" name="corp_address_line1" class="form-control" value="{{ old('corp_address_line1', $company->corp_address_line1) }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>City</label>
                                        <input type="text" name="corp_city" class="form-control" value="{{ old('corp_city', $company->corp_city) }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>State</label>
                                        <select name="corp_state" class="form-control">
                                            <option value="">Select State</option>
                                            @foreach(config('company.states') as $state)
                                                <option value="{{ $state }}" {{ old('corp_state', $company->corp_state) == $state ? 'selected' : '' }}>{{ $state }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Pincode</label>
                                        <input type="text" name="corp_pincode" class="form-control" value="{{ old('corp_pincode', $company->corp_pincode) }}">
                                    </div>

                                    <div class="col-12"><hr><h6 class="font-weight-bold">Other Details</h6></div>
                                    <div class="col-md-3 form-group">
                                        <label>Website</label>
                                        <input type="url" name="website" class="form-control" value="{{ old('website', $company->website) }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Company Logo</label>
                                        <input type="file" id="logo_path" name="logo_path" class="form-control-file" accept="image/png,image/jpeg,image/jpg">
                                        <img id="logo-preview" src="{{ $company->logo_url ? asset($company->logo_url) : '' }}" alt="Preview" class="mt-2 img-thumbnail" style="{{ $company->logo_url ? '' : 'display:none;' }}max-height:80px;">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <div class="custom-control custom-switch mt-4 pt-2">
                                            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" {{ old('is_active', $company->is_active) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_active">Active Status</label>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- end edit-section -->

                        </div>{{-- end tab-info --}}


                        {{-- ============================== --}}
                        {{-- TAB 2: BANK ACCOUNTS --}}
                        {{-- ============================== --}}
                        <div class="tab-pane fade" id="tab-bank" role="tabpanel">

                            <!-- VIEW MODE -->
                            <div class="view-section">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Bank Name</th>
                                                <th>Account No.</th>
                                                <th>IFSC</th>
                                                <th>Type</th>
                                                <th>Branch</th>
                                                <th>SWIFT</th>
                                                <th>Primary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($company->bankAccounts as $bank)
                                            <tr>
                                                <td>{{ $bank->bank_name }}</td>
                                                <td>{{ $bank->account_number }}</td>
                                                <td>{{ $bank->ifsc_code }}</td>
                                                <td>{{ ucfirst($bank->account_type) }}</td>
                                                <td>{{ $bank->branch_name ?: '—' }}</td>
                                                <td>{{ $bank->swift_code ?: '—' }}</td>
                                                <td>
                                                    @if($bank->is_primary)
                                                        <span class="badge badge-success"><i class="fas fa-check"></i> Primary</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr><td colspan="7" class="text-center text-muted">No bank accounts added.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- EDIT MODE -->
                            <div class="edit-section" style="display:none;">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Bank Name *</th>
                                                <th>Acc Number *</th>
                                                <th>IFSC Code *</th>
                                                <th>Acc Type *</th>
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
                                                @foreach($company->bankAccounts as $index => $bank)
                                                    @include('owner_companies.partials._bank_row', ['index' => $index, 'bank' => $bank])
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-sm btn-info" id="add-bank-btn">
                                    <i class="fas fa-plus"></i> Add Bank Account
                                </button>
                            </div>

                        </div>{{-- end tab-bank --}}


                        {{-- ============================== --}}
                        {{-- TAB 3: CONTACTS --}}
                        {{-- ============================== --}}
                        <div class="tab-pane fade" id="tab-contact" role="tabpanel">

                            <!-- VIEW MODE -->
                            <div class="view-section">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Phone</th>
                                                <th>Alt Phone</th>
                                                <th>Email</th>
                                                <th>Support Email</th>
                                                <th>Primary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($company->contacts as $contact)
                                            <tr>
                                                <td>{{ $contact->contact_person }}</td>
                                                <td>{{ $contact->designation ?: '—' }}</td>
                                                <td>{{ $contact->phone }}</td>
                                                <td>{{ $contact->alternate_phone ?: '—' }}</td>
                                                <td>{{ $contact->email }}</td>
                                                <td>{{ $contact->support_email ?: '—' }}</td>
                                                <td>
                                                    @if($contact->is_primary)
                                                        <span class="badge badge-success"><i class="fas fa-check"></i> Primary</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr><td colspan="7" class="text-center text-muted">No contacts added.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- EDIT MODE -->
                            <div class="edit-section" style="display:none;">
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
                                                @foreach($company->contacts as $index => $contact)
                                                    @include('owner_companies.partials._contact_row', ['index' => $index, 'contact' => $contact])
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-sm btn-info" id="add-contact-btn">
                                    <i class="fas fa-plus"></i> Add Contact
                                </button>
                            </div>

                        </div>{{-- end tab-contact --}}


                        {{-- ============================== --}}
                        {{-- TAB 4: ERP & STATUTORY --}}
                        {{-- ============================== --}}
                        <div class="tab-pane fade" id="tab-erp" role="tabpanel">

                            <!-- VIEW MODE -->
                            <div class="view-section">
                                <h6 class="text-primary font-weight-bold border-bottom pb-1 mb-3">ERP Configuration</h6>
                                <div class="row mb-3">
                                    <div class="col-md-3"><strong>Financial Year Start</strong><p class="text-muted">{{ ucfirst($company->financial_year_start) }}</p></div>
                                    <div class="col-md-3"><strong>Invoice Prefix</strong><p class="text-muted">{{ $company->invoice_prefix }}</p></div>
                                    <div class="col-md-3"><strong>Base Currency</strong><p class="text-muted">{{ $company->base_currency }}</p></div>
                                    <div class="col-md-3"><strong>Timezone</strong><p class="text-muted">{{ $company->timezone }}</p></div>
                                    <div class="col-md-3"><strong>Tax Regime</strong><p class="text-muted">{{ ucfirst($company->tax_regime ?: 'N/A') }}</p></div>
                                    <div class="col-md-3"><strong>Multi Branch</strong><p class="text-muted">{{ $company->is_multi_branch ? 'Yes' : 'No' }}</p></div>
                                </div>

                                <h6 class="text-primary font-weight-bold border-bottom pb-1 mb-3">Statutory Details</h6>
                                <div class="row">
                                    <div class="col-md-3"><strong>CIN</strong><p class="text-muted">{{ $company->cin_number ?: 'N/A' }}</p></div>
                                    <div class="col-md-3"><strong>TAN</strong><p class="text-muted">{{ $company->tan_number ?: 'N/A' }}</p></div>
                                    <div class="col-md-3"><strong>MSME Reg No</strong><p class="text-muted">{{ $company->msme_reg_no ?: 'N/A' }}</p></div>
                                    <div class="col-md-3"><strong>ROC</strong><p class="text-muted">{{ $company->roc ?: 'N/A' }}</p></div>
                                    <div class="col-md-3"><strong>Auth. Capital</strong><p class="text-muted">{{ $company->authorized_capital ? '₹ '.number_format($company->authorized_capital, 2) : 'N/A' }}</p></div>
                                    <div class="col-md-3"><strong>Paid Up Capital</strong><p class="text-muted">{{ $company->paid_up_capital ? '₹ '.number_format($company->paid_up_capital, 2) : 'N/A' }}</p></div>
                                    <div class="col-md-3"><strong>Directors</strong><p class="text-muted">{{ $company->num_directors ?: 'N/A' }}</p></div>
                                    <div class="col-md-3"><strong>Auditor</strong><p class="text-muted">{{ $company->auditor_name ?: 'N/A' }} {{ $company->auditor_firm ? '('.$company->auditor_firm.')' : '' }}</p></div>
                                    <div class="col-md-3"><strong>CS Name</strong><p class="text-muted">{{ $company->cs_name ?: 'N/A' }}</p></div>
                                </div>
                            </div>

                            <!-- EDIT MODE -->
                            <div class="edit-section" style="display:none;">
                                <div class="row">
                                    <div class="col-md-3 form-group">
                                        <label>Invoice Prefix <span class="text-danger">*</span></label>
                                        <input type="text" name="invoice_prefix" class="form-control" value="{{ old('invoice_prefix', $company->invoice_prefix) }}" required>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Financial Year Start <span class="text-danger">*</span></label>
                                        <select name="financial_year_start" class="form-control" required>
                                            <option value="april" {{ old('financial_year_start', $company->financial_year_start) == 'april' ? 'selected' : '' }}>April</option>
                                            <option value="january" {{ old('financial_year_start', $company->financial_year_start) == 'january' ? 'selected' : '' }}>January</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Base Currency</label>
                                        <select name="base_currency" class="form-control">
                                            @foreach(config('company.currencies') as $key => $val)
                                                <option value="{{ $key }}" {{ old('base_currency', $company->base_currency) == $key ? 'selected' : '' }}>{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Timezone</label>
                                        <select name="timezone" class="form-control">
                                            @foreach(config('company.timezones') as $key => $val)
                                                <option value="{{ $key }}" {{ old('timezone', $company->timezone) == $key ? 'selected' : '' }}>{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Tax Regime</label>
                                        <select name="tax_regime" class="form-control">
                                            <option value="">Select</option>
                                            <option value="old" {{ old('tax_regime', $company->tax_regime) == 'old' ? 'selected' : '' }}>Old Regime</option>
                                            <option value="new" {{ old('tax_regime', $company->tax_regime) == 'new' ? 'selected' : '' }}>New Regime</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Multi Branch Support</label>
                                        <div class="custom-control custom-switch mt-2">
                                            <input type="checkbox" name="is_multi_branch" class="custom-control-input" id="is_multi_branch" value="1" {{ old('is_multi_branch', $company->is_multi_branch) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_multi_branch">Enabled</label>
                                        </div>
                                    </div>

                                    <div class="col-12"><hr><h6 class="font-weight-bold">Statutory Details</h6></div>
                                    <div class="col-md-3 form-group">
                                        <label>CIN Number</label>
                                        <input type="text" name="cin_number" class="form-control" value="{{ old('cin_number', $company->cin_number) }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>TAN Number</label>
                                        <input type="text" name="tan_number" class="form-control" value="{{ old('tan_number', $company->tan_number) }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>MSME Reg No</label>
                                        <input type="text" name="msme_reg_no" class="form-control" value="{{ old('msme_reg_no', $company->msme_reg_no) }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>ROC</label>
                                        <input type="text" name="roc" class="form-control" value="{{ old('roc', $company->roc) }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Authorized Capital</label>
                                        <input type="number" step="0.01" name="authorized_capital" class="form-control" value="{{ old('authorized_capital', $company->authorized_capital) }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Paid Up Capital</label>
                                        <input type="number" step="0.01" name="paid_up_capital" class="form-control" value="{{ old('paid_up_capital', $company->paid_up_capital) }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Number of Directors</label>
                                        <input type="number" name="num_directors" class="form-control" value="{{ old('num_directors', $company->num_directors) }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Auditor Name</label>
                                        <input type="text" name="auditor_name" class="form-control" value="{{ old('auditor_name', $company->auditor_name) }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>Auditor Firm</label>
                                        <input type="text" name="auditor_firm" class="form-control" value="{{ old('auditor_firm', $company->auditor_firm) }}">
                                    </div>
                                    <div class="col-md-3 form-group">
                                        <label>CS Name</label>
                                        <input type="text" name="cs_name" class="form-control" value="{{ old('cs_name', $company->cs_name) }}">
                                    </div>
                                </div>
                            </div>

                        </div>{{-- end tab-erp --}}

                    </div>{{-- end tab-content --}}
                </div>{{-- end card-body --}}

                <!-- BOTTOM ACTION BAR (edit mode only) -->
                <div class="card-footer text-right edit-section" style="display:none;">
                    <button type="button" id="btn-cancel-bottom" class="btn btn-default">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-success ml-1">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>

            </div>{{-- end card --}}
        </form>

    </div>
</section>

<!-- JS Templates for dynamic row cloning -->
<template id="bank-row-template">
    @include('owner_companies.partials._bank_row', ['index' => '__INDEX__'])
</template>
<template id="contact-row-template">
    @include('owner_companies.partials._contact_row', ['index' => '__INDEX__'])
</template>

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // If there were validation errors, auto-switch to edit mode
    @if($errors->any())
        enterEditMode();
    @endif

    // Edit button
    $('#btn-edit').on('click', function () {
        enterEditMode();
    });

    // Cancel buttons
    $('#btn-cancel, #btn-cancel-bottom').on('click', function () {
        exitEditMode();
    });

    function enterEditMode() {
        $('.view-section').hide();
        $('.edit-section').show();
        $('#btn-edit').addClass('d-none');
        $('#btn-cancel, #btn-save').removeClass('d-none');
    }

    function exitEditMode() {
        $('.edit-section').hide();
        $('.view-section').show();
        $('#btn-edit').removeClass('d-none');
        $('#btn-cancel, #btn-save').addClass('d-none');
    }

    // Uppercase PAN, GSTIN, IFSC
    $(document).on('input', '#pan_number, #gstin, .ifsc-input', function () {
        var pos = this.selectionStart;
        $(this).val($(this).val().toUpperCase());
        this.setSelectionRange(pos, pos);
    });

    // Logo preview
    $('#logo_path').on('change', function () {
        const file = this.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('#logo-preview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#logo-preview').hide();
        }
    });

    // Dynamic bank rows
    let bankIndex = {{ $company->bankAccounts->count() > 0 ? $company->bankAccounts->count() : 1 }};

    $('#add-bank-btn').on('click', function () {
        let tpl = $('#bank-row-template').html().replace(/__INDEX__/g, bankIndex);
        $('#banks-tbl-body').append(tpl);
        bankIndex++;
    });

    $(document).on('click', '.remove-bank-row', function () {
        if ($('#banks-tbl-body tr.bank-row').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('At least one bank account is required.');
        }
    });

    $(document).on('change', '.primary-bank-radio', function () {
        $('.primary-bank-hidden').val('false');
        $(this).closest('td').find('.primary-bank-hidden').val('true');
    });

    // Dynamic contact rows
    let contactIndex = {{ $company->contacts->count() > 0 ? $company->contacts->count() : 1 }};

    $('#add-contact-btn').on('click', function () {
        let tpl = $('#contact-row-template').html().replace(/__INDEX__/g, contactIndex);
        $('#contacts-tbl-body').append(tpl);
        contactIndex++;
    });

    $(document).on('click', '.remove-contact-row', function () {
        if ($('#contacts-tbl-body tr.contact-row').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('At least one contact is required.');
        }
    });

    $(document).on('change', '.primary-contact-radio', function () {
        $('.primary-contact-hidden').val('false');
        $(this).closest('td').find('.primary-contact-hidden').val('true');
    });

    // Before submit: ensure primary flags are set
    $('#companyForm').on('submit', function () {
        if ($('.primary-bank-radio:checked').length === 0) {
            $('.primary-bank-radio').first().prop('checked', true).trigger('change');
        }
        if ($('.primary-contact-radio:checked').length === 0) {
            $('.primary-contact-radio').first().prop('checked', true).trigger('change');
        }
    });

});
</script>
@endpush
