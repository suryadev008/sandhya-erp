@extends('layouts.app')

@section('title', config('app.name') . ' | Owner Company Details')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Company Details: {{ $company->company_name }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('my-company.index') }}">My Company</a></li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            @if($company->logo_url)
                                <img class="profile-user-img img-fluid img-circle" src="{{ asset($company->logo_url) }}" alt="Company Logo">
                            @else
                                <div class="profile-user-img img-fluid img-circle bg-secondary d-flex justify-content-center align-items-center" style="height: 100px; font-size: 24px;">
                                    {{ substr($company->company_name, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <h3 class="profile-username text-center">{{ $company->company_name }}</h3>
                        <p class="text-muted text-center">{{ config('company.types.'.$company->company_type) }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>PAN Number</b> <a class="float-right">{{ $company->pan_number }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>GSTIN</b> <a class="float-right">{{ $company->gstin }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Status</b> <a class="float-right">
                                    {!! $company->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>' !!}
                                </a>
                            </li>
                        </ul>

                        <a href="{{ route('my-company.edit', $company->id) }}" class="btn btn-warning btn-block"><b><i class="fas fa-edit"></i> Edit Company</b></a>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#info" data-toggle="tab">Details & Statutory</a></li>
                            <li class="nav-item"><a class="nav-link" href="#banks" data-toggle="tab">Bank Accounts</a></li>
                            <li class="nav-item"><a class="nav-link" href="#contacts" data-toggle="tab">Contacts</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            
                            <!-- INFO TAB -->
                            <div class="active tab-pane" id="info">
                                
                                <h5 class="text-primary mb-3">General Information</h5>
                                <div class="row border-bottom pb-2 mb-3">
                                    <div class="col-sm-4"><strong>Incorporation Date:</strong> <p class="text-muted">{{ $company->incorporation_date ? $company->incorporation_date->format('d M Y') : 'N/A' }}</p></div>
                                    <div class="col-sm-4"><strong>Industry:</strong> <p class="text-muted">{{ $company->industry_type ?? 'N/A' }}</p></div>
                                    <div class="col-sm-4"><strong>Company Code:</strong> <p class="text-muted">{{ $company->company_code ?? 'N/A' }}</p></div>
                                    <div class="col-sm-4"><strong>Website:</strong> <p class="text-muted">{{ $company->website ?? 'N/A' }}</p></div>
                                </div>

                                <h5 class="text-primary mb-3">Address Information</h5>
                                <div class="row border-bottom pb-2 mb-3">
                                    <div class="col-sm-6">
                                        <strong>Registered Address:</strong>
                                        <address class="text-muted">
                                            {{ $company->reg_address_line1 }}<br>
                                            {{ $company->reg_city }}, {{ $company->reg_state }} - {{ $company->reg_pincode }}<br>
                                            {{ $company->reg_country }}
                                        </address>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Corporate Address:</strong>
                                        <address class="text-muted">
                                            {{ $company->corp_address_line1 ?: 'N/A' }}<br>
                                            {{ $company->corp_city }}, {{ $company->corp_state }} - {{ $company->corp_pincode }}
                                        </address>
                                    </div>
                                </div>

                                <h5 class="text-primary mb-3">ERP Configuration</h5>
                                <div class="row border-bottom pb-2 mb-3">
                                    <div class="col-sm-3"><strong>Financial Year:</strong> <p class="text-muted border-bottom-0">{{ ucfirst($company->financial_year_start) }}</p></div>
                                    <div class="col-sm-3"><strong>Invoice Prefix:</strong> <p class="text-muted">{{ $company->invoice_prefix }}</p></div>
                                    <div class="col-sm-3"><strong>Currency:</strong> <p class="text-muted">{{ $company->base_currency }}</p></div>
                                    <div class="col-sm-3"><strong>Timezone:</strong> <p class="text-muted">{{ $company->timezone }}</p></div>
                                    <div class="col-sm-3"><strong>Tax Regime:</strong> <p class="text-muted">{{ ucfirst($company->tax_regime) }}</p></div>
                                    <div class="col-sm-3"><strong>Multi Branch:</strong> <p class="text-muted">{{ $company->is_multi_branch ? 'Yes' : 'No' }}</p></div>
                                </div>

                                <h5 class="text-primary mb-3">Statutory Details</h5>
                                <div class="row">
                                    <div class="col-sm-3"><strong>CIN:</strong> <p class="text-muted">{{ $company->cin_number ?? 'N/A' }}</p></div>
                                    <div class="col-sm-3"><strong>TAN:</strong> <p class="text-muted">{{ $company->tan_number ?? 'N/A' }}</p></div>
                                    <div class="col-sm-3"><strong>MSME Reg No:</strong> <p class="text-muted">{{ $company->msme_reg_no ?? 'N/A' }}</p></div>
                                    <div class="col-sm-3"><strong>ROC:</strong> <p class="text-muted">{{ $company->roc ?? 'N/A' }}</p></div>
                                    <div class="col-sm-3"><strong>Auth. Capital:</strong> <p class="text-muted">{{ $company->authorized_capital ?? 'N/A' }}</p></div>
                                    <div class="col-sm-3"><strong>Paid Up Capital:</strong> <p class="text-muted">{{ $company->paid_up_capital ?? 'N/A' }}</p></div>
                                    <div class="col-sm-3"><strong>Directors:</strong> <p class="text-muted">{{ $company->num_directors ?? 'N/A' }}</p></div>
                                    <div class="col-sm-3"><strong>Auditor:</strong> <p class="text-muted">{{ $company->auditor_name ?? 'N/A' }} ({{ $company->auditor_firm }})</p></div>
                                </div>

                            </div>

                            <!-- BANKS TAB -->
                            <div class="tab-pane" id="banks">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Bank Name</th>
                                                <th>Account No.</th>
                                                <th>IFSC</th>
                                                <th>Type</th>
                                                <th>Branch</th>
                                                <th>Primary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($company->bankAccounts as $bank)
                                            <tr>
                                                <td>{{ $bank->bank_name }}</td>
                                                <td>{{ $bank->account_number }}</td>
                                                <td>{{ $bank->ifsc_code }}</td>
                                                <td>{{ ucfirst($bank->account_type) }}</td>
                                                <td>{{ $bank->branch_name }}</td>
                                                <td>
                                                    @if($bank->is_primary)
                                                        <span class="badge badge-success"><i class="fas fa-check"></i> Primary</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- CONTACTS TAB -->
                            <div class="tab-pane" id="contacts">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Designation</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Support Email</th>
                                                <th>Primary</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($company->contacts as $contact)
                                            <tr>
                                                <td>{{ $contact->contact_person }}</td>
                                                <td>{{ $contact->designation }}</td>
                                                <td>{{ $contact->phone }} <br><small>{{ $contact->alternate_phone }}</small></td>
                                                <td>{{ $contact->email }}</td>
                                                <td>{{ $contact->support_email }}</td>
                                                <td>
                                                    @if($contact->is_primary)
                                                        <span class="badge badge-success"><i class="fas fa-check"></i> Primary</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
