@extends('layouts.app')

@section('title', config('app.name') . ' | Payroll – ' . $employee->name)

@push('styles')
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush

@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Payroll – {{ $employee->name }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}">Payroll</a></li>
          <li class="breadcrumb-item active">{{ $employee->emp_code }}</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    {{-- ── Employee Info Boxes ─────────────────────────────────── --}}
    <div class="row">
      <div class="col-md-3 col-sm-6">
        <div class="info-box">
          <span class="info-box-icon bg-info"><i class="fas fa-id-badge"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Emp Code</span>
            <span class="info-box-number">{{ $employee->emp_code }}</span>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="info-box">
          <span class="info-box-icon bg-primary"><i class="fas fa-cog"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Type</span>
            <span class="info-box-number text-capitalize">{{ $employee->employee_type }}</span>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="info-box">
          <span class="info-box-icon bg-success"><i class="fas fa-rupee-sign"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Current Per Day</span>
            <span class="info-box-number">
              {{ $employee->currentSalary ? '₹ ' . number_format($employee->currentSalary->per_day, 2) : '—' }}
            </span>
          </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="info-box">
          <span class="info-box-icon bg-warning"><i class="fas fa-wallet"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Current Per Month</span>
            <span class="info-box-number">
              {{ $employee->currentSalary ? '₹ ' . number_format($employee->currentSalary->per_month, 2) : '—' }}
            </span>
          </div>
        </div>
      </div>
    </div>

    {{-- ── Annual Summary ──────────────────────────────────────── --}}
    <div class="row">
      <div class="col-md-3 col-sm-6">
        <div class="small-box bg-gradient-teal">
          <div class="inner">
            <h4>₹ {{ number_format($annualSummary['total_gross'], 2) }}</h4>
            <p>Total Gross ({{ $selectedYear }})</p>
          </div>
          <div class="icon"><i class="fas fa-chart-bar"></i></div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="small-box bg-gradient-green">
          <div class="inner">
            <h4>₹ {{ number_format($annualSummary['total_net'], 2) }}</h4>
            <p>Total Net Paid ({{ $selectedYear }})</p>
          </div>
          <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="small-box bg-gradient-red">
          <div class="inner">
            <h4>₹ {{ number_format($annualSummary['total_deductions'], 2) }}</h4>
            <p>Total Deductions ({{ $selectedYear }})</p>
          </div>
          <div class="icon"><i class="fas fa-minus-circle"></i></div>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <div class="small-box bg-gradient-navy">
          <div class="inner">
            <h4>{{ $annualSummary['paid_count'] }} Paid &nbsp;|&nbsp; {{ $annualSummary['approved_count'] }} Approved &nbsp;|&nbsp; {{ $annualSummary['draft_count'] }} Draft</h4>
            <p>Payroll Status ({{ $selectedYear }})</p>
          </div>
          <div class="icon"><i class="fas fa-tasks"></i></div>
        </div>
      </div>
    </div>

    {{-- ── Payroll Table Card ───────────────────────────────────── --}}
    <div class="card card-primary card-outline">
      <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="card-title"><i class="fas fa-list-alt mr-1"></i> Monthly Payroll</h3>

        <div class="d-flex align-items-center flex-wrap">
          {{-- Year Filter --}}
          <form method="GET" action="{{ route('payrolls.show', $employee->id) }}" class="form-inline mr-3 mb-1">
            <label class="mr-1 mb-0">Year:</label>
            <select name="year" class="form-control form-control-sm mr-1" onchange="this.form.submit()">
              @foreach($years as $yr)
                <option value="{{ $yr }}" {{ $yr == $selectedYear ? 'selected' : '' }}>{{ $yr }}</option>
              @endforeach
            </select>
          </form>

          {{-- Generate Payroll Button --}}
          <button type="button" class="btn btn-sm btn-success mb-1" data-toggle="modal" data-target="#generate-modal">
            <i class="fas fa-plus"></i> Generate Payroll
          </button>

          {{-- Lathe Slip shortcut --}}
          @if(in_array($employee->employee_type, ['lathe', 'both']))
            <a href="{{ route('payrolls.lathe-slip.show', [$employee->id]) }}?month={{ now()->month }}&year={{ $selectedYear }}"
               class="btn btn-sm btn-info mb-1 ml-1">
              <i class="fas fa-file-invoice mr-1"></i> Lathe Payslip
            </a>
          @endif
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm table-bordered mb-0">
            <thead class="thead-light">
              <tr>
                <th>#</th>
                <th>Month</th>
                <th>Working Days</th>
                <th>Present</th>
                <th>Absent</th>
                @if(in_array($employee->employee_type, ['lathe', 'both']))
                  <th>Lathe Amt (₹)</th>
                @endif
                @if(in_array($employee->employee_type, ['cnc', 'both']))
                  <th>CNC Days</th>
                  <th>CNC Amt (₹)</th>
                @endif
                <th>Extra (₹)</th>
                <th>Gross (₹)</th>
                <th>Deduction (₹)</th>
                <th class="text-success">Net (₹)</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($months as $i => $row)
                @php
                  $p = $row['payroll'];
                  $absent = $p ? max(0, $p->total_working_days - $p->present_days) : '—';
                @endphp
                <tr class="{{ $p && $p->status === 'paid' ? 'table-success' : ($p && $p->status === 'approved' ? 'table-info' : '') }}">
                  <td>{{ $i + 1 }}</td>
                  <td class="font-weight-bold">{{ $row['label'] }}</td>

                  @if($p)
                    <td>{{ $p->total_working_days }}</td>
                    <td>{{ $p->present_days }}</td>
                    <td>{{ $absent }}</td>

                    @if(in_array($employee->employee_type, ['lathe', 'both']))
                      <td>{{ number_format($p->total_lathe_amount, 2) }}</td>
                    @endif
                    @if(in_array($employee->employee_type, ['cnc', 'both']))
                      <td>{{ $p->total_cnc_days }}</td>
                      <td>{{ number_format($p->total_cnc_amount, 2) }}</td>
                    @endif

                    <td>
                      {{ number_format($p->extra_payment_total, 2) }}
                      @if($p->status === 'draft')
                        <button class="btn btn-xs btn-outline-success ml-1 btn-add-extra" data-id="{{ $p->id }}" title="Add Extra">
                          <i class="fas fa-plus"></i>
                        </button>
                      @endif
                    </td>
                    <td class="font-weight-bold">{{ number_format($p->gross_amount, 2) }}</td>
                    <td>
                      {{ number_format($p->deductions, 2) }}
                      @if($p->status === 'draft')
                        <button class="btn btn-xs btn-outline-danger ml-1 btn-edit-deduction" data-id="{{ (int) $p->id }}" data-deduction="{{ (float) $p->deductions }}" data-remarks="{{ e($p->deduction_remarks) }}" title="Edit Deduction">
                          <i class="fas fa-edit"></i>
                        </button>
                      @endif
                    </td>
                    <td class="font-weight-bold text-success">{{ number_format($p->net_amount, 2) }}</td>
                    <td>
                      @if($p->status === 'draft')
                        <span class="badge badge-secondary">Draft</span>
                      @elseif($p->status === 'approved')
                        <span class="badge badge-info">Approved</span>
                      @elseif($p->status === 'paid')
                        <span class="badge badge-success">Paid</span>
                      @endif
                    </td>
                    <td class="text-nowrap">
                      {{-- View detail --}}
                      <button class="btn btn-xs btn-info btn-view-detail" data-id="{{ $p->id }}" title="View Detail">
                        <i class="fas fa-eye"></i>
                      </button>
                      {{-- Regenerate (draft only) --}}
                      @if($p->status === 'draft')
                        <button class="btn btn-xs btn-warning btn-regenerate"
                          data-id="{{ $employee->id }}"
                          data-month="{{ $p->month }}"
                          data-year="{{ $p->year }}"
                          data-working="{{ $p->total_working_days }}"
                          data-present="{{ $p->present_days }}"
                          data-sunday="{{ $p->sunday_half_days }}"
                          title="Regenerate">
                          <i class="fas fa-sync-alt"></i>
                        </button>
                        <button class="btn btn-xs btn-primary btn-approve" data-id="{{ $p->id }}" title="Approve">
                          <i class="fas fa-check"></i> Approve
                        </button>
                      @endif
                      {{-- Mark Paid (approved only) --}}
                      @if($p->status === 'approved')
                        <button class="btn btn-xs btn-success btn-mark-paid" data-id="{{ $p->id }}" title="Mark as Paid">
                          <i class="fas fa-rupee-sign"></i> Paid
                        </button>
                      @endif
                    </td>
                  @else
                    {{-- Not generated --}}
                    <td colspan="
                      {{ 3 +
                         (in_array($employee->employee_type, ['lathe', 'both']) ? 1 : 0) +
                         (in_array($employee->employee_type, ['cnc', 'both']) ? 2 : 0) +
                         5 }}"
                      class="text-center text-muted py-2">
                      Not Generated
                    </td>
                    <td>
                      <button class="btn btn-xs btn-success btn-generate-quick"
                        data-month="{{ $row['month'] }}"
                        data-year="{{ $row['year'] }}"
                        data-label="{{ $row['label'] }}"
                        title="Generate Payroll">
                        <i class="fas fa-plus"></i> Generate
                      </button>
                    </td>
                  @endif
                </tr>
              @empty
                <tr>
                  <td colspan="15" class="text-center text-muted py-3">No months found for {{ $selectedYear }}.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <div class="card-footer">
        <a href="{{ route('payrolls.index') }}" class="btn btn-sm btn-secondary">
          <i class="fas fa-arrow-left"></i> Back to List
        </a>
      </div>
    </div>

  </div>
</section>

{{-- ── Generate Payroll Modal ──────────────────────────────────────── --}}
<div class="modal fade" id="generate-modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fas fa-plus-circle mr-1"></i> Generate Payroll</h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="generateForm">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Month <span class="text-danger">*</span></label>
                <select name="month" id="gen_month" class="form-control" required>
                  @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                      {{ date('F', mktime(0,0,0,$m,1)) }}
                    </option>
                  @endfor
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Year <span class="text-danger">*</span></label>
                <select name="year" id="gen_year" class="form-control" required>
                  @foreach($years as $yr)
                    <option value="{{ $yr }}" {{ $yr == $selectedYear ? 'selected' : '' }}>{{ $yr }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Total Working Days <span class="text-danger">*</span></label>
                <input type="number" name="total_working_days" id="gen_working" class="form-control" min="1" max="31" value="26" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Present Days <span class="text-danger">*</span></label>
                <input type="number" name="present_days" id="gen_present" class="form-control" min="0" max="31" step="0.5" value="26" required>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Sunday Half Days</label>
                <input type="number" name="sunday_half_days" id="gen_sunday" class="form-control" min="0" max="10" step="0.5" value="0">
              </div>
            </div>
          </div>
          <div class="alert alert-info alert-sm py-2 mb-0">
            <i class="fas fa-info-circle mr-1"></i>
            @if(in_array($employee->employee_type, ['lathe', 'both']))
              Lathe amount will be auto-fetched from production records.
            @endif
            @if(in_array($employee->employee_type, ['cnc', 'both']))
              CNC amount = Present Days × Per Day Rate.
            @endif
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" id="generateBtn" class="btn btn-success">
          <i class="fas fa-cog"></i> Generate
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ── Extra Payment Modal ─────────────────────────────────────────── --}}
<div class="modal fade" id="extra-payment-modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fas fa-plus-circle mr-1"></i> Add Extra Payment</h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="extraPaymentForm">
          @csrf
          <input type="hidden" id="extra_payroll_id">
          <div class="form-group">
            <label>Payment Name <span class="text-danger">*</span></label>
            <input type="text" name="payment_name" id="extra_payment_name" class="form-control" placeholder="e.g. Diwali Bonus, Travel Allowance">
          </div>
          <div class="form-group">
            <label>Amount (₹) <span class="text-danger">*</span></label>
            <input type="number" name="amount" id="extra_amount" class="form-control" min="0.01" step="0.01" placeholder="0.00">
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" id="saveExtraBtn" class="btn btn-success">
          <i class="fas fa-save"></i> Save
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ── Deduction Modal ─────────────────────────────────────────────── --}}
<div class="modal fade" id="deduction-modal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fas fa-minus-circle mr-1"></i> Update Deduction</h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="deductionForm">
          @csrf
          <input type="hidden" id="deduction_payroll_id">
          <div class="form-group">
            <label>Deduction Amount (₹) <span class="text-danger">*</span></label>
            <input type="number" name="deductions" id="deduction_amount" class="form-control" min="0" step="0.01" placeholder="0.00">
          </div>
          <div class="form-group">
            <label>Deduction Remarks</label>
            <textarea name="deduction_remarks" id="deduction_remarks" class="form-control" rows="2" placeholder="Reason for deduction"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" id="saveDeductionBtn" class="btn btn-danger">
          <i class="fas fa-save"></i> Save
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ── Payroll Detail Modal ────────────────────────────────────────── --}}
<div class="modal fade" id="detail-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fas fa-file-invoice mr-1"></i> Payroll Detail</h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body" id="detail-body">
        <div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
  <script src="{{ asset('public/adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

  <script>
  var empId      = {{ $employee->id }};
  var genUrl     = '/payroll/payrolls/' + empId + '/generate';
  var statusUrl  = function(id) { return '/payroll/payrolls/' + id + '/status'; };
  var extraUrl   = function(id) { return '/payroll/payrolls/' + id + '/extra-payment'; };
  var dedUrl     = function(id) { return '/payroll/payrolls/' + id + '/deduction'; };
  var detailUrl  = function(id) { return '/payroll/payrolls/' + id + '/detail'; };

  var Toast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false,
    timer: 4000, timerProgressBar: true
  });

  function postAjax(url, data, btn, originalHtml, successMsg) {
    btn.html('<i class="fas fa-spinner fa-spin"></i> ...').prop('disabled', true);
    $.ajax({
      url: url, type: 'POST',
      data: Object.assign({ _token: '{{ csrf_token() }}' }, data),
      success: function (res) {
        btn.html(originalHtml).prop('disabled', false);
        if (res.success) {
          Toast.fire({ icon: 'success', title: res.message || successMsg });
          setTimeout(() => location.reload(), 1000);
        } else {
          Swal.fire('Error', res.message, 'error');
        }
      },
      error: function (xhr) {
        btn.html(originalHtml).prop('disabled', false);
        var msg = xhr.responseJSON?.message || 'Something went wrong.';
        if (xhr.responseJSON?.errors) {
          msg = Object.values(xhr.responseJSON.errors).map(e => e.join(' ')).join(' ');
        }
        Swal.fire('Error', msg, 'error');
      }
    });
  }

  // ── Generate Payroll ──
  $('#generateBtn').on('click', function () {
    var btn  = $(this);
    var data = {
      month:              $('#gen_month').val(),
      year:               $('#gen_year').val(),
      total_working_days: $('#gen_working').val(),
      present_days:       $('#gen_present').val(),
      sunday_half_days:   $('#gen_sunday').val(),
    };
    postAjax(genUrl, data, btn, btn.html(), 'Payroll generated.');
  });

  // ── Quick Generate from row ──
  $(document).on('click', '.btn-generate-quick', function () {
    var $b = $(this);
    $('#gen_month').val($b.data('month'));
    $('#gen_year').val($b.data('year'));
    $('#generate-modal').modal('show');
  });

  // ── Regenerate from row ──
  $(document).on('click', '.btn-regenerate', function () {
    var $b = $(this);
    $('#gen_month').val($b.data('month'));
    $('#gen_year').val($b.data('year'));
    $('#gen_working').val($b.data('working'));
    $('#gen_present').val($b.data('present'));
    $('#gen_sunday').val($b.data('sunday'));
    $('#generate-modal').modal('show');
  });

  // ── Approve ──
  $(document).on('click', '.btn-approve', function () {
    var id  = $(this).data('id');
    var btn = $(this);
    Swal.fire({
      title: 'Approve Payroll?', icon: 'question',
      showCancelButton: true, confirmButtonColor: '#007bff',
      confirmButtonText: 'Yes, Approve'
    }).then(r => {
      if (r.isConfirmed) {
        postAjax(statusUrl(id), { status: 'approved' }, btn, btn.html(), 'Payroll approved.');
      }
    });
  });

  // ── Mark Paid ──
  $(document).on('click', '.btn-mark-paid', function () {
    var id  = $(this).data('id');
    var btn = $(this);
    Swal.fire({
      title: 'Mark as Paid?', icon: 'question',
      showCancelButton: true, confirmButtonColor: '#28a745',
      confirmButtonText: 'Yes, Mark Paid'
    }).then(r => {
      if (r.isConfirmed) {
        postAjax(statusUrl(id), { status: 'paid' }, btn, btn.html(), 'Payroll marked as paid.');
      }
    });
  });

  // ── Extra Payment ──
  $(document).on('click', '.btn-add-extra', function () {
    $('#extra_payroll_id').val($(this).data('id'));
    $('#extra_payment_name').val('');
    $('#extra_amount').val('');
    $('#extra-payment-modal').modal('show');
  });

  $('#saveExtraBtn').on('click', function () {
    var btn = $(this);
    var id  = $('#extra_payroll_id').val();
    postAjax(extraUrl(id), {
      payment_name: $('#extra_payment_name').val(),
      amount:       $('#extra_amount').val()
    }, btn, btn.html(), 'Extra payment added.');
  });

  // ── Deduction ──
  $(document).on('click', '.btn-edit-deduction', function () {
    var $b = $(this);
    $('#deduction_payroll_id').val($b.data('id'));
    $('#deduction_amount').val($b.data('deduction'));
    $('#deduction_remarks').val($b.data('remarks'));
    $('#deduction-modal').modal('show');
  });

  $('#saveDeductionBtn').on('click', function () {
    var btn = $(this);
    var id  = $('#deduction_payroll_id').val();
    postAjax(dedUrl(id), {
      deductions:        $('#deduction_amount').val(),
      deduction_remarks: $('#deduction_remarks').val()
    }, btn, btn.html(), 'Deduction updated.');
  });

  // ── View Detail ──
  $(document).on('click', '.btn-view-detail', function () {
    var id = $(this).data('id');
    $('#detail-body').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>');
    $('#detail-modal').modal('show');

    $.getJSON(detailUrl(id), function (res) {
      if (!res.success) { $('#detail-body').html('<p class="text-danger">Failed to load.</p>'); return; }
      var d = res.data;

      // Safe HTML escape helper — prevents XSS from user-supplied strings
      function esc(str) {
        if (str === null || str === undefined) return '—';
        return $('<div>').text(String(str)).html();
      }

      var deductionRemarks = d.deduction_remarks
        ? ' <small class="text-muted">(' + esc(d.deduction_remarks) + ')</small>'
        : '';

      var html =
        '<div class="row">' +
        '<div class="col-md-6">' +
          '<table class="table table-sm table-bordered mb-3">' +
            '<tr><th class="bg-light" colspan="2">Attendance</th></tr>' +
            '<tr><th>Working Days</th><td>' + parseInt(d.total_working_days) + '</td></tr>' +
            '<tr><th>Present Days</th><td>' + parseFloat(d.present_days) + '</td></tr>' +
            '<tr><th>Absent Days</th><td>' + (parseInt(d.total_working_days) - parseFloat(d.present_days)) + '</td></tr>' +
            '<tr><th>Sunday Half Days</th><td>' + parseFloat(d.sunday_half_days) + '</td></tr>' +
          '</table>' +
        '</div>' +
        '<div class="col-md-6">' +
          '<table class="table table-sm table-bordered mb-3">' +
            '<tr><th class="bg-light" colspan="2">Earnings</th></tr>' +
            '<tr><th>Lathe Amount</th><td>₹ ' + parseFloat(d.total_lathe_amount).toFixed(2) + '</td></tr>' +
            '<tr><th>CNC Days</th><td>' + parseFloat(d.total_cnc_days) + '</td></tr>' +
            '<tr><th>CNC Rate/Day</th><td>₹ ' + parseFloat(d.cnc_rate_per_day).toFixed(2) + '</td></tr>' +
            '<tr><th>CNC Amount</th><td>₹ ' + parseFloat(d.total_cnc_amount).toFixed(2) + '</td></tr>' +
            '<tr><th>Extra Payments</th><td>₹ ' + parseFloat(d.extra_payment_total).toFixed(2) + '</td></tr>' +
            '<tr class="table-warning"><th>Gross Amount</th><td><strong>₹ ' + parseFloat(d.gross_amount).toFixed(2) + '</strong></td></tr>' +
            '<tr class="table-danger"><th>Deductions</th><td>₹ ' + parseFloat(d.deductions).toFixed(2) + deductionRemarks + '</td></tr>' +
            '<tr class="table-success"><th>Net Amount</th><td><strong>₹ ' + parseFloat(d.net_amount).toFixed(2) + '</strong></td></tr>' +
          '</table>' +
        '</div>' +
        '</div>';

      // Extra payments list
      if (d.extra_payments && d.extra_payments.length) {
        html += '<h6 class="border-bottom pb-1">Extra Payments Breakdown</h6><table class="table table-sm table-bordered">' +
          '<thead class="thead-light"><tr><th>#</th><th>Description</th><th>Amount</th></tr></thead><tbody>';
        d.extra_payments.forEach(function (ep, i) {
          html += '<tr><td>' + (i + 1) + '</td><td>' + esc(ep.payment_name) + '</td><td>₹ ' + parseFloat(ep.amount).toFixed(2) + '</td></tr>';
        });
        html += '</tbody></table>';
      }

      html += '<div class="row mt-2">' +
        '<div class="col-sm-6"><small class="text-muted">Generated by: ' + esc(d.generated_by ? d.generated_by.name : null) + '</small></div>' +
        '<div class="col-sm-6 text-right"><small class="text-muted">Approved by: ' + esc(d.approved_by ? d.approved_by.name : null) + '</small></div>' +
        '</div>';

      $('#detail-body').html(html);
    });
  });
  </script>
@endpush
