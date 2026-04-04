@extends('layouts.app')

@section('title', config('app.name') . ' | ' . $company->company_name)

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Vendor Detail</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('companies.index') }}">Our Vendors</a></li>
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
                    <th style="width:200px" class="text-muted">Vendor Name</th>
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
            <div class="card-footer">
              <a href="{{ route('companies.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection