@extends('layouts.app')

@section('title', config('app.name') . ' | ' . $employee->name)

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Employee Detail</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
            <li class="breadcrumb-item active">{{ $employee->name }}</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-10">

          {{-- Personal Information --}}
          <div class="card card-primary card-outline">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h3 class="card-title mb-0">
                <i class="fas fa-user mr-2"></i>{{ $employee->name }}
                <small class="text-muted ml-2">({{ $employee->emp_code }})</small>
              </h3>
              @php
                $badgeColor = ['active' => 'success', 'inactive' => 'secondary', 'terminated' => 'danger'][$employee->status] ?? 'secondary';
              @endphp
              <span class="badge badge-{{ $badgeColor }}">{{ ucfirst($employee->status) }}</span>
            </div>

            <div class="card-body">
              <h6 class="text-muted font-weight-bold border-bottom pb-1 mb-3">Personal Information</h6>
              <div class="row">
                <div class="col-md-6">
                  <table class="table table-borderless table-sm">
                    <tr>
                      <th style="width:160px" class="text-muted">Emp Code</th>
                      <td>{{ $employee->emp_code }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">Full Name</th>
                      <td>{{ $employee->name }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">Aadhar No</th>
                      <td>{{ $employee->aadhar_no ?: '—' }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">Mobile (Primary)</th>
                      <td>{{ $employee->mobile_primary }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">Mobile (Secondary)</th>
                      <td>{{ $employee->mobile_secondary ?: '—' }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">WhatsApp No</th>
                      <td>{{ $employee->whatsapp_no ?: '—' }}</td>
                    </tr>
                  </table>
                </div>
                <div class="col-md-6">
                  <table class="table table-borderless table-sm">
                    <tr>
                      <th style="width:160px" class="text-muted">Permanent Address</th>
                      <td>{{ $employee->permanent_address ?: '—' }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">Present Address</th>
                      <td>{{ $employee->present_address }}</td>
                    </tr>
                  </table>
                </div>
              </div>

              {{-- Aadhar Image --}}
              @if($employee->aadhar_image)
              <h6 class="text-muted font-weight-bold border-bottom pb-1 mb-3 mt-3">Aadhar Image</h6>
              <div class="row mb-3">
                <div class="col-md-4">
                  <a href="{{ asset('storage/' . $employee->aadhar_image) }}" target="_blank">
                    <img src="{{ asset('storage/' . $employee->aadhar_image) }}"
                         alt="Aadhar Image"
                         class="img-fluid img-thumbnail"
                         style="max-height:200px;">
                  </a>
                  <small class="d-block text-muted mt-1">Click to view full size</small>
                </div>
              </div>
              @endif

              <h6 class="text-muted font-weight-bold border-bottom pb-1 mb-3 mt-3">Bank & Payment Details</h6>
              <div class="row">
                <div class="col-md-6">
                  <table class="table table-borderless table-sm">
                    <tr>
                      <th style="width:160px" class="text-muted">Bank Account No</th>
                      <td>{{ $employee->bank_account_no ?: '—' }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">Bank Name</th>
                      <td>{{ $employee->bank_name ?: '—' }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">IFSC Code</th>
                      <td>{{ $employee->ifsc_code ?: '—' }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">UPI Number</th>
                      <td>{{ $employee->upi_number ?: '—' }}</td>
                    </tr>
                  </table>
                </div>
              </div>

              <h6 class="text-muted font-weight-bold border-bottom pb-1 mb-3 mt-3">Job Details</h6>
              <div class="row">
                <div class="col-md-6">
                  <table class="table table-borderless table-sm">
                    <tr>
                      <th style="width:160px" class="text-muted">Employee Type</th>
                      <td>{{ ucfirst($employee->employee_type) }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">Experience</th>
                      <td>{{ $employee->experience_years ? $employee->experience_years . ' years' : '—' }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">Joining Date</th>
                      <td>{{ $employee->joining_date ? \Carbon\Carbon::parse($employee->joining_date)->format('d M Y') : '—' }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">Status</th>
                      <td><span class="badge badge-{{ $badgeColor }}">{{ ucfirst($employee->status) }}</span></td>
                    </tr>
                    <tr>
                      <th class="text-muted">Created At</th>
                      <td>{{ $employee->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
              </a>
            </div>
          </div>

        </div>

        {{-- Salary Section --}}
        <div class="col-md-10">
          <div class="card card-success card-outline">
            <div class="card-header d-flex align-items-center justify-content-between">
              <h3 class="card-title mb-0"><i class="fas fa-rupee-sign mr-2"></i>Salary</h3>
              <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#add-salary-modal">
                <i class="fas fa-plus"></i> Add / Update Salary
              </button>
            </div>
            <div class="card-body">

              {{-- Current Salary --}}
              @if($employee->currentSalary)
                <div class="row mb-4">
                  <div class="col-md-4">
                    <div class="info-box bg-success">
                      <span class="info-box-icon"><i class="fas fa-calendar-day"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Per Day</span>
                        <span class="info-box-number">₹ {{ number_format($employee->currentSalary->per_day, 2) }}</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="info-box bg-primary">
                      <span class="info-box-icon"><i class="fas fa-calendar-alt"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Per Month (30 days)</span>
                        <span class="info-box-number">₹ {{ number_format($employee->currentSalary->per_month, 2) }}</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="info-box bg-warning">
                      <span class="info-box-icon"><i class="fas fa-calendar-check"></i></span>
                      <div class="info-box-content">
                        <span class="info-box-text">Effective From</span>
                        <span class="info-box-number">{{ $employee->currentSalary->effect_from->format('d M Y') }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              @else
                <p class="text-muted">No salary record found. Click "Add / Update Salary" to add one.</p>
              @endif

              {{-- Salary History Table --}}
              @if($employee->salaries->count() > 0)
              <h6 class="text-muted font-weight-bold border-bottom pb-1 mb-3">Salary History</h6>
              <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>Per Day (₹)</th>
                      <th>Per Month (₹)</th>
                      <th>Effect From</th>
                      <th>Remark</th>
                      <th>Added By</th>
                      <th>Added On</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($employee->salaries as $i => $sal)
                    <tr @if($i === 0) class="table-success" @endif>
                      <td>{{ $i + 1 }}</td>
                      <td>₹ {{ number_format($sal->per_day, 2) }}</td>
                      <td>₹ {{ number_format($sal->per_month, 2) }}</td>
                      <td>{{ $sal->effect_from->format('d M Y') }}</td>
                      <td>{{ $sal->remark ?: '—' }}</td>
                      <td>{{ $sal->createdBy->name ?? '—' }}</td>
                      <td>{{ $sal->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <small class="text-success"><i class="fas fa-circle"></i> Green row = current active salary</small>
              @endif

            </div>
          </div>
        </div>

      </div>
    </div>

    {{-- Add Salary Modal --}}
    <div class="modal fade" id="add-salary-modal" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-success">
            <h4 class="modal-title text-white"><i class="fas fa-rupee-sign mr-2"></i>Add / Update Salary</h4>
            <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <form id="addSalaryForm">
              @csrf
              <div class="form-group">
                <label>Per Day (₹) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0" name="per_day" id="modal_per_day" class="form-control" placeholder="0.00" required>
              </div>
              <div class="form-group">
                <label>Per Month (₹) <small class="text-muted">30 days — auto calculated</small></label>
                <input type="number" step="0.01" min="0" name="per_month" id="modal_per_month" class="form-control" placeholder="0.00" required>
              </div>
              <div class="form-group">
                <label>Effect From <span class="text-danger">*</span></label>
                <input type="date" name="effect_from" id="modal_effect_from" class="form-control" required>
                <small class="text-muted">Payroll will use this salary from this date onwards</small>
              </div>
              <div class="form-group">
                <label>Remark</label>
                <input type="text" name="remark" id="modal_remark" class="form-control" placeholder="e.g. Annual Increment, Promotion">
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="addSalaryForm" id="saveSalaryBtn" class="btn btn-success">Save Salary</button>
          </div>
        </div>
      </div>
    </div>
    {{-- End Add Salary Modal --}}

  </section>

@push('scripts')
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
  var Toast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false,
    timer: 3000, timerProgressBar: true,
  });

  // Auto-calculate salary
  $('#modal_per_day').on('input', function () {
    let v = parseFloat($(this).val());
    if (!isNaN(v) && v > 0) $('#modal_per_month').val((v * 30).toFixed(2));
  });
  $('#modal_per_month').on('input', function () {
    let v = parseFloat($(this).val());
    if (!isNaN(v) && v > 0) $('#modal_per_day').val((v / 30).toFixed(2));
  });

  // Save Salary
  $('#addSalaryForm').on('submit', function (e) {
    e.preventDefault();
    let btn = $('#saveSalaryBtn');
    btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

    $.ajax({
      url: '{{ route("employees.salary.store", $employee->id) }}',
      type: 'POST',
      data: $(this).serialize(),
      success: function (res) {
        btn.html('Save Salary').prop('disabled', false);
        if (res.success) {
          Toast.fire({ icon: 'success', title: res.message });
          setTimeout(() => location.reload(), 1500);
        }
      },
      error: function (xhr) {
        btn.html('Save Salary').prop('disabled', false);
        let msg = xhr.responseJSON?.errors
          ? Object.values(xhr.responseJSON.errors).flat().join('<br>')
          : (xhr.responseJSON?.message || 'Something went wrong.');
        Swal.fire({ icon: 'error', title: 'Error', html: msg });
      }
    });
  });
</script>
@endpush
@endsection
