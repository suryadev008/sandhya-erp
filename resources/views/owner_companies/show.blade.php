@extends('layouts.app')

@section('title', config('app.name') . ' | ' . $company->company_name)

@push('styles')
<style>
  .info-label  { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #6c757d; letter-spacing: .5px; margin-bottom: 2px; }
  .info-value  { font-size: 14px; color: #212529; margin-bottom: 0; }
  .info-block  { margin-bottom: 18px; }
  .section-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #495057; border-bottom: 2px solid #e9ecef; padding-bottom: 6px; margin-bottom: 16px; }
  .badge-primary-contact { background: #d4edda; color: #155724; font-size: 11px; padding: 2px 7px; border-radius: 10px; }
  .logo-box    { width: 90px; height: 90px; border-radius: 10px; object-fit: cover; border: 2px solid #dee2e6; }
  .logo-placeholder { width: 90px; height: 90px; border-radius: 10px; background: #343a40; color: #fff; font-size: 32px; font-weight: 700; display: flex; align-items: center; justify-content: center; }
</style>
@endpush

@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1 class="m-0">Company Profile</h1></div>
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

    {{-- ── Top header bar ── --}}
    <div class="card card-primary card-outline mb-3">
      <div class="card-body py-3">
        <div class="d-flex align-items-center">
          {{-- Logo --}}
          <div class="mr-4">
            @if($company->logo_url)
              <img src="{{ asset($company->logo_url) }}" class="logo-box" alt="Logo">
            @else
              <div class="logo-placeholder">{{ strtoupper(substr($company->company_name, 0, 1)) }}</div>
            @endif
          </div>

          {{-- Name + badges --}}
          <div class="flex-grow-1">
            <h3 class="mb-1 font-weight-bold">{{ $company->company_name }}</h3>
            <div>
              @if($company->company_type)
                <span class="badge badge-secondary mr-1">{{ config("company.types.{$company->company_type}", $company->company_type) }}</span>
              @endif
              @if($company->industry_type)
                <span class="badge badge-info mr-1">{{ $company->industry_type }}</span>
              @endif
              @if($company->is_active)
                <span class="badge badge-success">Active</span>
              @else
                <span class="badge badge-danger">Inactive</span>
              @endif
            </div>
            <div class="mt-1 text-muted" style="font-size:13px">
              @if($company->website)
                <a href="{{ $company->website }}" target="_blank"><i class="fas fa-globe mr-1"></i>{{ $company->website }}</a>
              @endif
            </div>
          </div>

          {{-- Action buttons --}}
          <div>
            <a href="{{ route('my-company.index') }}" class="btn btn-secondary btn-sm mr-1">
              <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
            @can('edit my-company')
            <a href="{{ route('my-company.edit', $company->id) }}" class="btn btn-warning btn-sm">
              <i class="fas fa-edit mr-1"></i> Edit
            </a>
            @endcan
          </div>
        </div>
      </div>
    </div>

    {{-- ── Row: Identity + Address + ERP ── --}}
    <div class="row">

      {{-- Company Identity --}}
      <div class="col-md-4">
        <div class="card card-outline card-primary h-100">
          <div class="card-header py-2">
            <h6 class="card-title mb-0"><i class="fas fa-id-card mr-1 text-primary"></i> Identity & Registration</h6>
          </div>
          <div class="card-body">
            <div class="info-block">
              <div class="info-label">PAN Number</div>
              <div class="info-value">{{ $company->pan_number }}</div>
            </div>
            <div class="info-block">
              <div class="info-label">GSTIN</div>
              <div class="info-value">{{ $company->gstin }}</div>
            </div>
            <div class="info-block">
              <div class="info-label">Company Code</div>
              <div class="info-value">{{ $company->company_code ?: '—' }}</div>
            </div>
            <div class="info-block">
              <div class="info-label">Incorporation Date</div>
              <div class="info-value">{{ $company->incorporation_date ? $company->incorporation_date->format('d M Y') : '—' }}</div>
            </div>
            <div class="info-block mb-0">
              <div class="info-label">Industry</div>
              <div class="info-value">{{ $company->industry_type ?: '—' }}</div>
            </div>
          </div>
        </div>
      </div>

      {{-- Address --}}
      <div class="col-md-4">
        <div class="card card-outline card-info h-100">
          <div class="card-header py-2">
            <h6 class="card-title mb-0"><i class="fas fa-map-marker-alt mr-1 text-info"></i> Address</h6>
          </div>
          <div class="card-body">
            <div class="section-title">Registered</div>
            <p class="info-value mb-3">
              {{ $company->reg_address_line1 }}<br>
              {{ $company->reg_city }}, {{ $company->reg_state }} – {{ $company->reg_pincode }}<br>
              {{ $company->reg_country }}
            </p>

            <div class="section-title">Corporate</div>
            @if($company->corp_address_line1)
              <p class="info-value mb-0">
                {{ $company->corp_address_line1 }}<br>
                @if($company->corp_city){{ $company->corp_city }}, @endif
                @if($company->corp_state){{ $company->corp_state }}@endif
                @if($company->corp_pincode) – {{ $company->corp_pincode }}@endif
              </p>
            @else
              <p class="text-muted mb-0">—</p>
            @endif
          </div>
        </div>
      </div>

      {{-- ERP Configuration --}}
      <div class="col-md-4">
        <div class="card card-outline card-success h-100">
          <div class="card-header py-2">
            <h6 class="card-title mb-0"><i class="fas fa-cogs mr-1 text-success"></i> ERP Configuration</h6>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-6 info-block">
                <div class="info-label">Invoice Prefix</div>
                <div class="info-value">{{ $company->invoice_prefix }}</div>
              </div>
              <div class="col-6 info-block">
                <div class="info-label">Financial Year</div>
                <div class="info-value">{{ ucfirst($company->financial_year_start) }}</div>
              </div>
              <div class="col-6 info-block">
                <div class="info-label">Currency</div>
                <div class="info-value">{{ $company->base_currency ?: '—' }}</div>
              </div>
              <div class="col-6 info-block">
                <div class="info-label">Timezone</div>
                <div class="info-value" style="font-size:12px">{{ $company->timezone ?: '—' }}</div>
              </div>
              <div class="col-6 info-block">
                <div class="info-label">Tax Regime</div>
                <div class="info-value">{{ $company->tax_regime ? ucfirst($company->tax_regime).' Regime' : '—' }}</div>
              </div>
              <div class="col-6 info-block mb-0">
                <div class="info-label">Multi Branch</div>
                <div class="info-value">
                  @if($company->is_multi_branch)
                    <span class="badge badge-success">Enabled</span>
                  @else
                    <span class="badge badge-secondary">Disabled</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>{{-- end row --}}

    {{-- ── Statutory Details ── --}}
    <div class="card card-outline card-warning">
      <div class="card-header py-2">
        <h6 class="card-title mb-0"><i class="fas fa-file-alt mr-1 text-warning"></i> Statutory Details</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-2 col-6 info-block">
            <div class="info-label">CIN</div>
            <div class="info-value">{{ $company->cin_number ?: '—' }}</div>
          </div>
          <div class="col-md-2 col-6 info-block">
            <div class="info-label">TAN</div>
            <div class="info-value">{{ $company->tan_number ?: '—' }}</div>
          </div>
          <div class="col-md-2 col-6 info-block">
            <div class="info-label">MSME Reg No</div>
            <div class="info-value">{{ $company->msme_reg_no ?: '—' }}</div>
          </div>
          <div class="col-md-2 col-6 info-block">
            <div class="info-label">ROC</div>
            <div class="info-value">{{ $company->roc ?: '—' }}</div>
          </div>
          <div class="col-md-2 col-6 info-block">
            <div class="info-label">Auth. Capital</div>
            <div class="info-value">{{ $company->authorized_capital ? '₹ '.number_format($company->authorized_capital, 2) : '—' }}</div>
          </div>
          <div class="col-md-2 col-6 info-block">
            <div class="info-label">Paid Up Capital</div>
            <div class="info-value">{{ $company->paid_up_capital ? '₹ '.number_format($company->paid_up_capital, 2) : '—' }}</div>
          </div>
          <div class="col-md-2 col-6 info-block mb-0">
            <div class="info-label">No. of Directors</div>
            <div class="info-value">{{ $company->num_directors ?: '—' }}</div>
          </div>
          <div class="col-md-3 col-6 info-block mb-0">
            <div class="info-label">Auditor</div>
            <div class="info-value">
              {{ $company->auditor_name ?: '—' }}
              @if($company->auditor_firm)
                <small class="text-muted">({{ $company->auditor_firm }})</small>
              @endif
            </div>
          </div>
          <div class="col-md-2 col-6 info-block mb-0">
            <div class="info-label">CS Name</div>
            <div class="info-value">{{ $company->cs_name ?: '—' }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- ── Bank Accounts ── --}}
    <div class="card card-outline card-primary">
      <div class="card-header py-2">
        <h6 class="card-title mb-0"><i class="fas fa-university mr-1 text-primary"></i> Bank Accounts</h6>
        <div class="card-tools"><span class="badge badge-primary">{{ $company->bankAccounts->count() }}</span></div>
      </div>
      <div class="card-body p-0">
        @if($company->bankAccounts->isEmpty())
          <p class="text-muted p-3 mb-0">No bank accounts added.</p>
        @else
        <div class="table-responsive">
          <table class="table table-bordered table-hover mb-0">
            <thead class="thead-light">
              <tr>
                <th>#</th>
                <th>Bank Name</th>
                <th>Account Holder</th>
                <th>Account No.</th>
                <th>IFSC Code</th>
                <th>Type</th>
                <th>Branch</th>
                <th>SWIFT</th>
                <th class="text-center">Primary</th>
              </tr>
            </thead>
            <tbody>
              @foreach($company->bankAccounts as $i => $bank)
              <tr @if($bank->is_primary) class="table-success" @endif>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $bank->bank_name }}</strong></td>
                <td>{{ $bank->account_holder_name ?? '—' }}</td>
                <td><code>{{ $bank->account_number }}</code></td>
                <td><code>{{ $bank->ifsc_code }}</code></td>
                <td><span class="badge badge-secondary">{{ ucfirst($bank->account_type) }}</span></td>
                <td>{{ $bank->branch_name ?: '—' }}</td>
                <td>{{ $bank->swift_code ?: '—' }}</td>
                <td class="text-center">
                  @if($bank->is_primary)
                    <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Primary</span>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @endif
      </div>
    </div>

    {{-- ── Contacts ── --}}
    <div class="card card-outline card-info">
      <div class="card-header py-2">
        <h6 class="card-title mb-0"><i class="fas fa-address-book mr-1 text-info"></i> Contacts</h6>
        <div class="card-tools"><span class="badge badge-info">{{ $company->contacts->count() }}</span></div>
      </div>
      <div class="card-body p-0">
        @if($company->contacts->isEmpty())
          <p class="text-muted p-3 mb-0">No contacts added.</p>
        @else
        <div class="table-responsive">
          <table class="table table-bordered table-hover mb-0">
            <thead class="thead-light">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Support Email</th>
                <th class="text-center">Primary</th>
              </tr>
            </thead>
            <tbody>
              @foreach($company->contacts as $i => $contact)
              <tr @if($contact->is_primary) class="table-success" @endif>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $contact->contact_person }}</strong></td>
                <td>{{ $contact->designation ?: '—' }}</td>
                <td>
                  {{ $contact->phone }}
                  @if($contact->alternate_phone)
                    <br><small class="text-muted"><i class="fas fa-phone-alt mr-1"></i>{{ $contact->alternate_phone }}</small>
                  @endif
                </td>
                <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                <td>{!! $contact->support_email ? "<a href=\"mailto:{$contact->support_email}\">{$contact->support_email}</a>" : '—' !!}</td>
                <td class="text-center">
                  @if($contact->is_primary)
                    <span class="badge badge-success"><i class="fas fa-check mr-1"></i>Primary</span>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @endif
      </div>
    </div>

    {{-- Footer meta --}}
    <div class="text-muted text-right mb-3" style="font-size:12px">
      @if($company->createdBy)
        Created by <strong>{{ $company->createdBy->name }}</strong>
      @endif
      @if($company->updatedBy)
        &nbsp;·&nbsp; Last updated by <strong>{{ $company->updatedBy->name }}</strong>
      @endif
    </div>

  </div>
</section>
@endsection
