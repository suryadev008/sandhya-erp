@extends('layouts.app')

@section('title', config('app.name') . ' | ' . $company->company_name)

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Customer Company Detail</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Customer Companies</a></li>
            <li class="breadcrumb-item active">{{ $company->company_name }}</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
          <div class="card card-primary card-outline">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h3 class="card-title mb-0">
                <i class="fas fa-building mr-2"></i>{{ $company->company_name }}
              </h3>
              <span class="badge badge-{{ $company->is_active ? 'success' : 'secondary' }} ml-2">
                {{ $company->is_active ? 'Active' : 'Inactive' }}
              </span>
            </div>
            <div class="card-body">
              <table class="table table-borderless table-sm">
                <tbody>
                  <tr>
                    <th style="width:200px" class="text-muted">Company Name</th>
                    <td>{{ $company->company_name }}</td>
                  </tr>
                  <tr>
                    <th class="text-muted">Plant Name</th>
                    <td>{{ $company->plant_name ?: '—' }}</td>
                  </tr>
                  <tr>
                    <th class="text-muted">Contact Person</th>
                    <td>{{ $company->contact_person ?: '—' }}</td>
                  </tr>
                  <tr>
                    <th class="text-muted">Designation</th>
                    <td>{{ $company->designation?->name ?: '—' }}</td>
                  </tr>
                  <tr>
                    <th class="text-muted">Contact Phone</th>
                    <td>{{ $company->contact_phone ?: '—' }}</td>
                  </tr>
                  <tr>
                    <th class="text-muted">Address</th>
                    <td>{{ $company->address ?: '—' }}</td>
                  </tr>
                  <tr>
                    <th class="text-muted">Remark</th>
                    <td>{{ $company->remark ?: '—' }}</td>
                  </tr>
                  <tr><th colspan="2" class="bg-light text-primary pt-2 pb-1">GST Details</th></tr>
                  <tr>
                    <th class="text-muted">GSTIN</th>
                    <td>
                      {{ $company->gst_no ?: '—' }}
                      @if($company->gst_verified_at)
                        <span class="badge badge-success ml-1"><i class="fas fa-check-circle"></i> Verified</span>
                        <small class="text-muted ml-1">{{ $company->gst_verified_at->format('d M Y') }}</small>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <th class="text-muted">Trade Name</th>
                    <td>{{ $company->gst_trade_name ?: '—' }}</td>
                  </tr>
                  <tr>
                    <th class="text-muted">Legal Name</th>
                    <td>{{ $company->gst_legal_name ?: '—' }}</td>
                  </tr>
                  <tr>
                    <th class="text-muted">GST Status</th>
                    <td>
                      @if($company->gst_status)
                        <span class="badge badge-{{ strtolower($company->gst_status) === 'active' ? 'success' : 'warning' }}">
                          {{ $company->gst_status }}
                        </span>
                      @else —
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <th class="text-muted">State</th>
                    <td>{{ $company->gst_state ?: '—' }}</td>
                  </tr>
                  <tr>
                    <th class="text-muted">PAN</th>
                    <td>{{ $company->gst_pan ?: '—' }}</td>
                  </tr>
                  <tr>
                    <th class="text-muted">Registration Date</th>
                    <td>{{ $company->gst_registration_date ?: '—' }}</td>
                  </tr>
                  <tr>
                    <th class="text-muted">Business Type</th>
                    <td>{{ $company->gst_business_type ?: '—' }}</td>
                  </tr>

                  <tr><th colspan="2" class="bg-light pt-2 pb-1 text-muted" style="font-size:11px">TIMESTAMPS</th></tr>
                  <tr>
                    <th class="text-muted">Created At</th>
                    <td>{{ $company->created_at->format('d M Y, h:i A') }}</td>
                  </tr>
                  <tr>
                    <th class="text-muted">Updated At</th>
                    <td>{{ $company->updated_at->format('d M Y, h:i A') }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="card-footer d-flex justify-content-between">
              <a href="{{ route('companies.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
              </a>
              @can('edit companies')
              <button class="btn btn-warning btn-sm edit-btn" data-id="{{ $company->id }}"
                data-toggle="modal" data-target="#edit-module-popup">
                <i class="fas fa-edit"></i> Edit
              </button>
              @endcan
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection