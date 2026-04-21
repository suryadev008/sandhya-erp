@extends('layouts.app')

@section('title', config('app.name') . ' | Operation: ' . $operation->operation_name)

@push('styles')
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Operation Detail</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('operations.index') }}">Operations</a></li>
            <li class="breadcrumb-item active">{{ $operation->operation_name }}</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      {{-- Info Boxes --}}
      @php
        $latestPrice = $operation->prices->sortByDesc('applicable_from')->first();
      @endphp
      <div class="row">
        <div class="col-md-3 col-sm-6">
          <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-building"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Vendor</span>
              <span class="info-box-number">{{ $operation->company?->company_name ?? '—' }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-rupee-sign"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Current Price</span>
              <span class="info-box-number">
                {{ $latestPrice ? '₹ ' . number_format($latestPrice->price, 2) : 'Not Set' }}
              </span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-cogs"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Applicable For</span>
              <span class="info-box-number text-capitalize">{{ $operation->applicable_for }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6">
          <div class="info-box">
            <span class="info-box-icon {{ $operation->is_active ? 'bg-success' : 'bg-danger' }}">
              <i class="fas fa-{{ $operation->is_active ? 'check' : 'times' }}-circle"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Status</span>
              <span class="info-box-number">{{ $operation->is_active ? 'Active' : 'Inactive' }}</span>
            </div>
          </div>
        </div>
      </div>

      {{-- Operation Detail Card --}}
      <div class="row">
        <div class="col-md-4">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Operation Info</h3>
            </div>
            <div class="card-body p-0">
              <table class="table table-sm mb-0">
                <tr>
                  <th class="pl-3 w-40">Operation Name</th>
                  <td>{{ $operation->operation_name }}</td>
                </tr>
                <tr>
                  <th class="pl-3">Vendor</th>
                  <td>{{ $operation->company?->company_name ?? '—' }}</td>
                </tr>
                <tr>
                  <th class="pl-3">Applicable For</th>
                  <td class="text-capitalize">{{ $operation->applicable_for }}</td>
                </tr>
                <tr>
                  <th class="pl-3">Remark</th>
                  <td>{{ $operation->remark ?? '—' }}</td>
                </tr>
                <tr>
                  <th class="pl-3">Status</th>
                  <td>
                    <span class="badge badge-{{ $operation->is_active ? 'success' : 'danger' }}">
                      {{ $operation->is_active ? 'Active' : 'Inactive' }}
                    </span>
                  </td>
                </tr>
                <tr>
                  <th class="pl-3">Created At</th>
                  <td>{{ $operation->created_at?->format('d M Y') }}</td>
                </tr>
              </table>
            </div>
            <div class="card-footer">
              <a href="{{ route('operations.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
              </a>
            </div>
          </div>
        </div>

        {{-- Price History Card --}}
        <div class="col-md-8">
          <div class="card card-success card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-history mr-1"></i> Price History</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#add-price-modal">
                  <i class="fas fa-plus"></i> Add Price Change
                </button>
              </div>
            </div>
            <div class="card-body p-0">
              <table class="table table-sm table-bordered mb-0" id="price-history-table">
                <thead class="thead-light">
                  <tr>
                    <th>#</th>
                    <th>Price (₹)</th>
                    <th>Applicable From</th>
                    <th>Remark</th>
                    <th>Added By</th>
                    <th>Added On</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($operation->prices->sortByDesc('applicable_from') as $index => $price)
                    <tr class="{{ $index === 0 ? 'table-success font-weight-bold' : '' }}">
                      <td>{{ $index + 1 }}</td>
                      <td>₹ {{ number_format($price->price, 2) }}</td>
                      <td>{{ $price->applicable_from->format('d M Y') }}</td>
                      <td>{{ $price->remark ?? '—' }}</td>
                      <td>{{ $price->creator?->name ?? '—' }}</td>
                      <td>{{ $price->created_at?->format('d M Y') }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center text-muted py-3">No price history found.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  {{-- Add Price Modal --}}
  <div class="modal fade" id="add-price-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add Price Change</h4>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <form id="addPriceForm">
            @csrf
            <div class="form-group">
              <label>New Price (₹) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" min="0" name="price" id="new_price" class="form-control" placeholder="e.g. 12.50" required>
            </div>
            <div class="form-group">
              <label>Applicable From <span class="text-danger">*</span></label>
              <input type="date" name="applicable_from" id="new_applicable_from" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="form-group">
              <label>Remark</label>
              <input type="text" name="remark" id="new_price_remark" class="form-control" placeholder="Reason for price change (optional)">
            </div>
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" form="addPriceForm" class="btn btn-success">
            <i class="fas fa-save"></i> Save Price
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script src="{{ asset('public/adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

  <script>
    var Toast = Swal.mixin({
      toast: true, position: 'top-end', showConfirmButton: false,
      timer: 4000, timerProgressBar: true
    });

    $.validator.setDefaults({
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (el) { $(el).addClass('is-invalid'); },
      unhighlight: function (el) { $(el).removeClass('is-invalid'); }
    });

    $('#addPriceForm').validate({
      rules: {
        price:          { required: true, number: true, min: 0 },
        applicable_from:{ required: true }
      },
      messages: {
        price:          { required: 'Please enter a price.', number: 'Enter a valid number.' },
        applicable_from:{ required: 'Please select a date.' }
      },
      submitHandler: function (form, e) {
        e.preventDefault();
        var btn = $('#add-price-modal').find('button[type="submit"]');
        btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
          url: '{{ route('operations.price.store', $operation->id) }}',
          type: 'POST',
          data: $(form).serialize(),
          success: function (res) {
            btn.html('<i class="fas fa-save"></i> Save Price').prop('disabled', false);
            if (res.success) {
              Toast.fire({ icon: 'success', title: res.message });
              $('#add-price-modal').modal('hide');
              // Reload page to refresh price history table
              setTimeout(function() { location.reload(); }, 1000);
            }
          },
          error: function (xhr) {
            btn.html('<i class="fas fa-save"></i> Save Price').prop('disabled', false);
            var msg = 'Something went wrong.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
              msg = Object.values(xhr.responseJSON.errors).map(function(e) { return e.join('<br>'); }).join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
              msg = xhr.responseJSON.message;
            }
            Swal.fire({
              icon: 'error', title: 'Error',
              html: '<div class="text-left text-danger">' + msg + '</div>',
              confirmButtonColor: '#dc3545'
            });
          }
        });
      }
    });
  </script>
@endpush
