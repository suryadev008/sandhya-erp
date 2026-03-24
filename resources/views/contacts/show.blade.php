@extends('layouts.app')

@section('title', config('app.name') . ' | Contact: ' . $contact->person_name)

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Contact Detail</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('contacts.index') }}">Contacts</a></li>
            <li class="breadcrumb-item active">{{ $contact->person_name }}</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      {{-- Info Boxes --}}
      <div class="row">
        <div class="col-md-3 col-sm-6">
          <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-user"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Person Name</span>
              <span class="info-box-number">{{ $contact->person_name }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box">
            <span class="info-box-icon bg-primary"><i class="fas fa-phone"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Contact No</span>
              <span class="info-box-number">{{ $contact->contact_no }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fab fa-whatsapp"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">WhatsApp No</span>
              <span class="info-box-number">
                @if($contact->whatsapp_no)
                  <a href="https://wa.me/{{ preg_replace('/\D/', '', $contact->whatsapp_no) }}" target="_blank" rel="noopener noreferrer" class="text-success">
                    {{ $contact->whatsapp_no }}
                  </a>
                @else
                  —
                @endif
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-university"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Bank</span>
              <span class="info-box-number">{{ $contact->bank_name ?? '—' }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="row">

        {{-- Basic Information Card --}}
        <div class="col-md-5">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-address-card mr-1"></i> Basic Information</h3>
            </div>
            <div class="card-body p-0">
              <table class="table table-sm mb-0">
                <tr>
                  <th class="pl-3" style="width:45%">Person Name</th>
                  <td>{{ $contact->person_name }}</td>
                </tr>
                <tr>
                  <th class="pl-3">Contact No</th>
                  <td>{{ $contact->contact_no }}</td>
                </tr>
                <tr>
                  <th class="pl-3">WhatsApp No</th>
                  <td>
                    @if($contact->whatsapp_no)
                      <a href="https://wa.me/{{ preg_replace('/\D/', '', $contact->whatsapp_no) }}" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-whatsapp text-success mr-1"></i>{{ $contact->whatsapp_no }}
                      </a>
                    @else
                      —
                    @endif
                  </td>
                </tr>
                <tr>
                  <th class="pl-3">UPI No</th>
                  <td>{{ $contact->upi_no ?? '—' }}</td>
                </tr>
                <tr>
                  <th class="pl-3">Remarks</th>
                  <td>{{ $contact->remarks ?? '—' }}</td>
                </tr>
                <tr>
                  <th class="pl-3">Status</th>
                  <td>
                    <span class="badge badge-{{ $contact->is_active ? 'success' : 'danger' }}">
                      {{ $contact->is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                </tr>
                <tr>
                  <th class="pl-3">Created At</th>
                  <td>{{ $contact->created_at?->format('d M Y') }}</td>
                </tr>
              </table>
            </div>
            <div class="card-footer">
              <a href="{{ route('contacts.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
              </a>
            </div>
          </div>
        </div>

        {{-- Bank Details Card --}}
        <div class="col-md-7">
          <div class="card card-success card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-university mr-1"></i> Bank Details</h3>
            </div>
            <div class="card-body p-0">
              <table class="table table-sm mb-0">
                <tr>
                  <th class="pl-3" style="width:40%">Account Holder Name</th>
                  <td>{{ $contact->account_holder_name ?? '—' }}</td>
                </tr>
                <tr>
                  <th class="pl-3">Account No</th>
                  <td>{{ $contact->account_no ?? '—' }}</td>
                </tr>
                <tr>
                  <th class="pl-3">IFSC Code</th>
                  <td>{{ $contact->ifsc_code ? strtoupper($contact->ifsc_code) : '—' }}</td>
                </tr>
                <tr>
                  <th class="pl-3">Bank Name</th>
                  <td>{{ $contact->bank_name ?? '—' }}</td>
                </tr>
                <tr>
                  <th class="pl-3">Branch</th>
                  <td>{{ $contact->branch ?? '—' }}</td>
                </tr>
                <tr>
                  <th class="pl-3">UPI No</th>
                  <td>{{ $contact->upi_no ?? '—' }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
@endsection
