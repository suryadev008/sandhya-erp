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
      @php
        $badgeColor = ['active' => 'success', 'inactive' => 'secondary', 'terminated' => 'danger'][$employee->status] ?? 'secondary';
      @endphp

      {{-- ── Employee Header Card ──────────────────────────────── --}}
      <div class="card card-primary card-outline mb-3">
        <div class="card-body py-3">
          <div class="d-flex align-items-center justify-content-between flex-wrap">
            <div>
              <h4 class="mb-0 font-weight-bold">
                <i class="fas fa-user-circle mr-2 text-primary"></i>{{ $employee->name }}
                <small class="text-muted ml-2">{{ $employee->emp_code }}</small>
              </h4>
              <small class="text-muted">
                {{ ucfirst($employee->employee_type) }} &nbsp;|&nbsp;
                Joined: {{ $employee->joining_date ? $employee->joining_date->format('d M Y') : '—' }}
              </small>
            </div>
            <div class="d-flex align-items-center mt-2 mt-md-0">
              <span class="badge badge-{{ $badgeColor }} mr-3" style="font-size:0.9rem; padding:6px 12px;">
                {{ ucfirst($employee->status) }}
              </span>
              <a href="{{ route('employees.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
              </a>
            </div>
          </div>
        </div>
      </div>

      {{-- ── Accordion ──────────────────────────────────────────── --}}
      <div id="empAccordion">

        {{-- ── 1. Personal Information ──────────────────────────── --}}
        <div class="card card-outline card-primary mb-2">
          <div class="card-header d-flex align-items-center py-2">
            <h5 class="mb-0">
              <i class="fas fa-id-card mr-2 text-primary"></i> Personal Information
            </h5>
            <button class="btn btn-sm btn-outline-primary collapsed ml-auto"
              data-toggle="collapse" data-target="#collapse-personal"
              aria-expanded="false">
              <i class="fas fa-eye mr-1"></i> Show
            </button>
          </div>
          <div id="collapse-personal" class="collapse" data-parent="#empAccordion">
            <div class="card-body">
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
                      <td>
                        @if($employee->whatsapp_no)
                          <a href="https://wa.me/91{{ $employee->whatsapp_no }}" target="_blank">
                            <i class="fab fa-whatsapp text-success mr-1"></i>{{ $employee->whatsapp_no }}
                          </a>
                        @else —
                        @endif
                      </td>
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

                  @if($employee->aadhar_image)
                    <div class="mt-3">
                      <p class="text-muted font-weight-bold mb-1">Aadhar Image</p>
                      <a href="{{ asset('storage/' . $employee->aadhar_image) }}" target="_blank">
                        <img src="{{ asset('storage/' . $employee->aadhar_image) }}"
                             alt="Aadhar" class="img-fluid img-thumbnail" style="max-height:160px;">
                      </a>
                      <small class="d-block text-muted mt-1">Click to view full size</small>
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- ── 2. Bank & Payment Details ─────────────────────────── --}}
        <div class="card card-outline card-primary mb-2">
          <div class="card-header d-flex align-items-center py-2">
            <h5 class="mb-0">
              <i class="fas fa-university mr-2 text-primary"></i> Bank &amp; Payment Details
            </h5>
            <button class="btn btn-sm btn-outline-primary collapsed ml-auto"
              data-toggle="collapse" data-target="#collapse-bank"
              aria-expanded="false">
              <i class="fas fa-eye mr-1"></i> Show
            </button>
          </div>
          <div id="collapse-bank" class="collapse" data-parent="#empAccordion">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <table class="table table-borderless table-sm">
                    <tr>
                      <th style="width:170px" class="text-muted">Bank Account No</th>
                      <td>{{ $employee->bank_account_no ?: '—' }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">Bank Name</th>
                      <td>{{ $employee->bank_name ?: '—' }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">Bank Branch</th>
                      <td>{{ $employee->bank_branch ?: '—' }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">IFSC Code</th>
                      <td>{{ $employee->ifsc_code ? strtoupper($employee->ifsc_code) : '—' }}</td>
                    </tr>
                    <tr>
                      <th class="text-muted">UPI Number</th>
                      <td>{{ $employee->upi_number ?: '—' }}</td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- ── 3. Job Details ────────────────────────────────────── --}}
        <div class="card card-outline card-primary mb-2">
          <div class="card-header d-flex align-items-center py-2">
            <h5 class="mb-0">
              <i class="fas fa-briefcase mr-2 text-primary"></i> Job Details
            </h5>
            <button class="btn btn-sm btn-outline-primary collapsed ml-auto"
              data-toggle="collapse" data-target="#collapse-job"
              aria-expanded="false">
              <i class="fas fa-eye mr-1"></i> Show
            </button>
          </div>
          <div id="collapse-job" class="collapse" data-parent="#empAccordion">
            <div class="card-body">
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
                      <td>{{ $employee->joining_date ? $employee->joining_date->format('d M Y') : '—' }}</td>
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
          </div>
        </div>

        {{-- ── 4. CNC Settings (only for cnc/both employees) ───────── --}}
        @if(in_array($employee->employee_type, ['cnc', 'both']))
        <div class="card card-outline card-info mb-2">
          <div class="card-header d-flex align-items-center py-2">
            <h5 class="mb-0">
              <i class="fas fa-cogs mr-2 text-info"></i> CNC Settings
              <small class="text-muted ml-2">
                {{ $employee->cnc_payment_type === 'per_piece' ? 'Per Piece' : 'Day Rate + Incentive' }}
                &nbsp;|&nbsp; Target: {{ $employee->cnc_target_per_shift }} pcs/shift
              </small>
            </h5>
            <button class="btn btn-sm btn-outline-info collapsed ml-auto"
              data-toggle="collapse" data-target="#collapse-cnc"
              aria-expanded="false">
              <i class="fas fa-eye mr-1"></i> Show
            </button>
          </div>
          <div id="collapse-cnc" class="collapse" data-parent="#empAccordion">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <table class="table table-borderless table-sm">
                    <tr>
                      <th style="width:180px" class="text-muted">Payment Model</th>
                      <td>
                        @if($employee->cnc_payment_type === 'per_piece')
                          <span class="badge badge-primary">Per Piece</span>
                          <small class="text-muted ml-1">— paid per piece produced (like lathe)</small>
                        @else
                          <span class="badge badge-info">Day Rate + Incentive</span>
                          <small class="text-muted ml-1">— fixed day salary + bonus above target</small>
                        @endif
                      </td>
                    </tr>
                    <tr>
                      <th class="text-muted">Target / Shift</th>
                      <td>{{ $employee->cnc_target_per_shift ?? 90 }} pieces per shift</td>
                    </tr>
                    @if($employee->cnc_payment_type !== 'per_piece')
                    <tr>
                      <th class="text-muted">Incentive Rate</th>
                      <td>₹ {{ number_format($employee->cnc_incentive_rate, 2) }} per piece above target</td>
                    </tr>
                    @endif
                  </table>
                </div>
                <div class="col-md-6">
                  <div class="alert alert-light border mb-0">
                    <i class="fas fa-info-circle text-info mr-1"></i>
                    @if($employee->cnc_payment_type === 'per_piece')
                      This employee is paid per piece completed — similar to lathe operator. Set rates in <strong>Operation Rates</strong> section below.
                    @else
                      This employee earns a fixed day-rate salary. Extra pieces above <strong>{{ $employee->cnc_target_per_shift }}</strong>/shift earn <strong>₹{{ number_format($employee->cnc_incentive_rate, 2) }}</strong>/piece incentive.
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endif

        {{-- ── 5. Operation Rates (for lathe/both, or per_piece CNC) ─ --}}
        @if(in_array($employee->employee_type, ['lathe', 'both']) || ($employee->employee_type === 'cnc' && $employee->cnc_payment_type === 'per_piece'))
        <div class="card card-outline card-warning mb-2">
          <div class="card-header d-flex align-items-center py-2">
            <h5 class="mb-0">
              <i class="fas fa-tags mr-2 text-warning"></i> Operation Rates
              <small class="text-muted ml-2">Employee-specific rates override global operation price</small>
            </h5>
            <div class="d-flex align-items-center ml-auto">
              <button class="btn btn-sm btn-warning mr-2" data-toggle="modal" data-target="#add-rate-modal">
                <i class="fas fa-plus"></i> Add Rate
              </button>
              <button class="btn btn-sm btn-outline-warning collapsed"
                data-toggle="collapse" data-target="#collapse-rates" aria-expanded="false">
                <i class="fas fa-eye mr-1"></i> Show
              </button>
            </div>
          </div>
          <div id="collapse-rates" class="collapse" data-parent="#empAccordion">
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover mb-0" id="ratesTable">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>Operation</th>
                      <th class="text-right">Rate (₹/piece)</th>
                      <th>Effective From</th>
                      <th>Remark</th>
                      <th class="text-center" style="width:80px">Action</th>
                    </tr>
                  </thead>
                  <tbody id="ratesBody">
                    <tr><td colspan="6" class="text-center text-muted py-3">
                      <i class="fas fa-spinner fa-spin"></i> Loading...
                    </td></tr>
                  </tbody>
                </table>
              </div>
              <div class="px-3 py-2 bg-light border-top">
                <small class="text-muted">
                  <i class="fas fa-info-circle text-warning mr-1"></i>
                  If no employee-specific rate is set, the global operation price is used.
                  Rate with latest <em>Effective From</em> date applies for each entry.
                </small>
              </div>
            </div>
          </div>
        </div>
        @endif

        {{-- ── (Salary) ───────────────────────────────────────────── --}}
        <div class="card card-outline card-primary mb-2">
          <div class="card-header d-flex align-items-center py-2">
            <h5 class="mb-0">
              <i class="fas fa-rupee-sign mr-2 text-primary"></i> Salary
              @if($employee->currentSalary)
                <small class="text-muted ml-2">
                  Current: ₹ {{ number_format($employee->currentSalary->per_day, 2) }}/day &nbsp;|&nbsp;
                  ₹ {{ number_format($employee->currentSalary->per_month, 2) }}/month
                </small>
              @endif
            </h5>
            <div class="d-flex align-items-center ml-auto">
              <button class="btn btn-sm btn-primary mr-2" data-toggle="modal" data-target="#add-salary-modal">
                <i class="fas fa-plus"></i> Add / Update
              </button>
              <button class="btn btn-sm btn-outline-primary"
                data-toggle="collapse" data-target="#collapse-salary"
                aria-expanded="true">
                <i class="fas fa-eye-slash mr-1"></i> Hide
              </button>
            </div>
          </div>
          <div id="collapse-salary" class="collapse show" data-parent="#empAccordion">
            <div class="card-body">

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
                <p class="text-muted">No salary record found. Click "Add / Update" to add one.</p>
              @endif

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
      {{-- /.accordion --}}

    </div>
  </section>

  {{-- Add Operation Rate Modal --}}
  <div class="modal fade" id="add-rate-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h4 class="modal-title"><i class="fas fa-tags mr-2"></i>Add Employee Operation Rate</h4>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <form id="addRateForm">
            @csrf
            <div class="form-group">
              <label>Operation <span class="text-danger">*</span></label>
              <select name="operation_id" id="rate_operation_id" class="form-control select2-modal" required>
                <option value="">-- Select Operation --</option>
                @foreach($operations as $op)
                  <option value="{{ $op->id }}">{{ $op->operation_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Rate (₹ per piece) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" min="0" name="rate" id="rate_amount" class="form-control" placeholder="0.00" required>
            </div>
            <div class="form-group">
              <label>Effective From <span class="text-danger">*</span></label>
              <input type="date" name="applicable_from" id="rate_from" class="form-control" required>
              <small class="text-muted">This rate applies from this date onwards. Old payroll entries are unaffected.</small>
            </div>
            <div class="form-group">
              <label>Remark</label>
              <input type="text" name="remark" id="rate_remark" class="form-control" placeholder="e.g. Joining rate, Experience increment">
            </div>
          </form>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" form="addRateForm" id="saveRateBtn" class="btn btn-warning">
            <i class="fas fa-save mr-1"></i> Save Rate
          </button>
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

@push('scripts')
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
  var Toast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false,
    timer: 3000, timerProgressBar: true,
  });

  // Toggle button text on collapse open/close
  $('[data-toggle="collapse"]').on('click', function () {
    var $btn = $(this);
    var target = $btn.data('target');
    var isExpanded = $(target).hasClass('show');
    $btn.html(isExpanded
      ? '<i class="fas fa-eye mr-1"></i> Show'
      : '<i class="fas fa-eye-slash mr-1"></i> Hide'
    );
  });

  // ── Operation Rates ──────────────────────────────────────────────
  var ratesUrl   = '{{ route("employees.operation-rates.index", $employee->id) }}';
  var ratesStore = '{{ route("employees.operation-rates.store", $employee->id) }}';

  function loadRates() {
    $.getJSON(ratesUrl, function (res) {
      var rows = '';
      if (!res.data || res.data.length === 0) {
        rows = '<tr><td colspan="6" class="text-center text-muted py-3"><i class="fas fa-inbox mr-1"></i>No rates set — global operation price will be used.</td></tr>';
      } else {
        // Group by operation for display
        $.each(res.data, function (i, r) {
          rows += '<tr>'
            + '<td>' + (i + 1) + '</td>'
            + '<td><strong>' + $('<div>').text(r.operation_name).html() + '</strong></td>'
            + '<td class="text-right font-weight-bold text-warning">₹ ' + r.rate + '</td>'
            + '<td>' + r.applicable_from + '</td>'
            + '<td class="text-muted small">' + (r.remark ? $('<div>').text(r.remark).html() : '—') + '</td>'
            + '<td class="text-center"><button class="btn btn-xs btn-outline-danger delete-rate-btn" data-id="' + r.id + '"><i class="fas fa-trash"></i></button></td>'
            + '</tr>';
        });
      }
      $('#ratesBody').html(rows);
    });
  }

  // Load rates when accordion opens
  $('[data-target="#collapse-rates"]').on('click', function () {
    if (!$(this).hasClass('collapsed') === false) loadRates();
  });
  // Also load on page load if already open
  loadRates();

  // Initialize Select2 in modal
  $('.select2-modal').select2({ theme: 'bootstrap4', dropdownParent: $('#add-rate-modal'), width: '100%' });

  // Save rate
  $('#addRateForm').on('submit', function (e) {
    e.preventDefault();
    var btn = $('#saveRateBtn');
    btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

    $.ajax({
      url: ratesStore,
      type: 'POST',
      data: $(this).serialize(),
      success: function (res) {
        btn.html('<i class="fas fa-save mr-1"></i> Save Rate').prop('disabled', false);
        if (res.success) {
          Toast.fire({ icon: 'success', title: res.message });
          $('#add-rate-modal').modal('hide');
          $('#addRateForm')[0].reset();
          loadRates();
        }
      },
      error: function (xhr) {
        btn.html('<i class="fas fa-save mr-1"></i> Save Rate').prop('disabled', false);
        var msg = xhr.responseJSON?.errors
          ? Object.values(xhr.responseJSON.errors).flat().join('<br>')
          : (xhr.responseJSON?.message || 'Something went wrong.');
        Swal.fire({ icon: 'error', title: 'Error', html: msg });
      }
    });
  });

  // Delete rate
  $(document).on('click', '.delete-rate-btn', function () {
    var rateId = $(this).data('id');
    Swal.fire({
      title: 'Delete this rate?',
      text: 'This will not affect already-generated payrolls.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      confirmButtonText: 'Yes, delete'
    }).then(function (result) {
      if (!result.isConfirmed) return;
      $.ajax({
        url: '{{ url("/payroll/employees/" . $employee->id . "/operation-rates") }}/' + rateId,
        type: 'POST',
        data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
        success: function () {
          Toast.fire({ icon: 'success', title: 'Rate deleted.' });
          loadRates();
        },
        error: function () {
          Toast.fire({ icon: 'error', title: 'Delete failed.' });
        }
      });
    });
  });

  // ── Auto-calculate salary ──────────────────────────────────────────
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
