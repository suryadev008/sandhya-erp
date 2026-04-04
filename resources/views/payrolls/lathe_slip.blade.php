@extends('layouts.app')

@section('title', config('app.name') . ' | Lathe Payslip – ' . $employee->name)

@push('styles')
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <style>
    .summary-card { border-left: 4px solid; border-radius: 4px; }
    .summary-card.lathe  { border-color: #17a2b8; }
    .summary-card.extra  { border-color: #28a745; }
    .summary-card.deduct { border-color: #dc3545; }
    .summary-card.net    { border-color: #007bff; }
    .entry-date-row { background: #f8f9fa; font-weight: 600; }
  </style>
@endpush

@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Lathe Payslip – {{ $employee->name }}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('payrolls.index') }}">Payroll</a></li>
          <li class="breadcrumb-item"><a href="{{ route('payrolls.show', $employee->id) }}">{{ $employee->emp_code }}</a></li>
          <li class="breadcrumb-item active">Lathe Slip</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    {{-- ── Employee + Month Selector ─────────────────────────────── --}}
    <div class="card card-primary card-outline mb-3">
      <div class="card-body py-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
          <div>
            <h5 class="mb-0 font-weight-bold">
              <i class="fas fa-user-circle mr-2 text-primary"></i>
              {{ $employee->name }}
              <small class="text-muted ml-1">{{ $employee->emp_code }}</small>
              <span class="badge badge-info ml-2">{{ ucfirst($employee->employee_type) }}</span>
              @if($payroll)
                @php $statusColors = ['draft'=>'secondary','approved'=>'primary','paid'=>'success']; @endphp
                <span class="badge badge-{{ $statusColors[$payroll->status] ?? 'secondary' }} ml-1">
                  {{ ucfirst($payroll->status) }}
                </span>
              @endif
            </h5>
            @if($salary)
              <small class="text-muted">Rate: ₹ {{ number_format($salary->per_day, 2) }}/day &nbsp;|&nbsp; ₹ {{ number_format($salary->per_month, 2) }}/month</small>
            @endif
          </div>
          <form method="GET" action="{{ route('payrolls.lathe-slip.show', $employee->id) }}" class="form-inline mt-2 mt-md-0">
            <select name="month" class="form-control form-control-sm mr-1">
              @foreach($months as $num => $name)
                <option value="{{ $num }}" {{ $num == $selectedMonth ? 'selected' : '' }}>{{ $name }}</option>
              @endforeach
            </select>
            <select name="year" class="form-control form-control-sm mr-1">
              @foreach($years as $yr)
                <option value="{{ $yr }}" {{ $yr == $selectedYear ? 'selected' : '' }}>{{ $yr }}</option>
              @endforeach
            </select>
            <button type="submit" class="btn btn-sm btn-primary">
              <i class="fas fa-search"></i> Load
            </button>
          </form>
        </div>
      </div>
    </div>

    <div class="row">

      {{-- ── LEFT: Production Entries ────────────────────────────── --}}
      <div class="col-lg-8">
        <div class="card card-outline card-info">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-list mr-1"></i>
              Lathe Production Entries — {{ $months[$selectedMonth] }} {{ $selectedYear }}
            </h3>
            <div class="card-tools">
              <span class="badge badge-info">{{ $entries->count() }} entries</span>
            </div>
          </div>
          <div class="card-body p-0">
            @if($entries->isEmpty())
              <div class="text-center text-muted py-5">
                <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                No lathe production entries found for this month.
              </div>
            @else
              <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>Date</th>
                      <th>Vendor</th>
                      <th>Part No.</th>
                      <th>Operation</th>
                      <th>Shift</th>
                      <th class="text-right">Qty</th>
                      <th class="text-right">Rate (₹)</th>
                      <th class="text-right">Amount (₹)</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $sno = 1; @endphp
                    @foreach($entriesByDate as $date => $dayEntries)
                      @php $dayTotal = $dayEntries->sum('amount'); @endphp
                      @foreach($dayEntries as $entry)
                        <tr>
                          <td>{{ $sno++ }}</td>
                          <td class="text-nowrap">{{ \Carbon\Carbon::parse($date)->format('d M') }}</td>
                          <td>{{ $entry->company?->company_name ?? '—' }}</td>
                          <td>{{ $entry->part?->part_number ?? '—' }}</td>
                          <td>{{ $entry->operation?->operation_name ?? '—' }}</td>
                          <td class="text-capitalize">{{ $entry->shift }}</td>
                          <td class="text-right">{{ $entry->qty }}</td>
                          <td class="text-right">{{ number_format($entry->rate, 2) }}</td>
                          <td class="text-right font-weight-bold">{{ number_format($entry->amount, 2) }}</td>
                        </tr>
                      @endforeach
                      {{-- Day subtotal --}}
                      <tr class="entry-date-row">
                        <td colspan="6" class="text-right pr-3 text-muted">
                          <small>{{ \Carbon\Carbon::parse($date)->format('d M Y') }} — Day Total</small>
                        </td>
                        <td class="text-right text-muted"><small>{{ $dayEntries->sum('qty') }}</small></td>
                        <td></td>
                        <td class="text-right text-info">₹ {{ number_format($dayTotal, 2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot class="thead-light">
                    <tr>
                      <th colspan="8" class="text-right">Total Lathe Amount</th>
                      <th class="text-right text-info">₹ {{ number_format($totalLatheAmount, 2) }}</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            @endif
          </div>
        </div>
      </div>

      {{-- ── RIGHT: Summary & Actions ────────────────────────────── --}}
      <div class="col-lg-4">

        {{-- Summary Cards --}}
        <div class="card summary-card lathe p-3 mb-3">
          <div class="d-flex justify-content-between">
            <span class="text-muted">Lathe Earnings</span>
            <strong class="text-info">₹ {{ number_format($totalLatheAmount, 2) }}</strong>
          </div>
        </div>
        <div class="card summary-card extra p-3 mb-3">
          <div class="d-flex justify-content-between">
            <span class="text-muted">Extra Payments</span>
            <strong class="text-success">+ ₹ {{ number_format($extraTotal, 2) }}</strong>
          </div>
        </div>
        <div class="card summary-card deduct p-3 mb-3">
          <div class="d-flex justify-content-between">
            <span class="text-muted">Deductions</span>
            <strong class="text-danger">- ₹ {{ number_format($deductions, 2) }}</strong>
          </div>
        </div>
        <div class="card summary-card net p-3 mb-4">
          <div class="d-flex justify-content-between align-items-center">
            <span class="font-weight-bold">Net Payable</span>
            <strong class="text-primary" style="font-size:1.2rem;">₹ {{ number_format($netAmount, 2) }}</strong>
          </div>
        </div>

        {{-- Action Buttons --}}
        <div class="card card-outline card-primary">
          <div class="card-header"><h3 class="card-title">Actions</h3></div>
          <div class="card-body">

            {{-- Save Payroll --}}
            @if(!$payroll || $payroll->status === 'draft')
              <button class="btn btn-block btn-primary mb-2" id="btnSave">
                <i class="fas fa-save mr-1"></i>
                {{ $payroll ? 'Update Payroll' : 'Save Payroll' }}
              </button>
            @endif

            {{-- Extra Payment --}}
            @if($payroll && $payroll->status === 'draft')
              <button class="btn btn-block btn-success mb-2" data-toggle="modal" data-target="#modal-extra">
                <i class="fas fa-plus mr-1"></i> Add Extra Payment
              </button>
            @endif

            {{-- Deduction --}}
            @if($payroll && $payroll->status === 'draft')
              <button class="btn btn-block btn-warning mb-2" data-toggle="modal" data-target="#modal-deduction">
                <i class="fas fa-minus mr-1"></i> Update Deduction
              </button>
            @endif

            {{-- Approve --}}
            @if($payroll && $payroll->status === 'draft')
              <button class="btn btn-block btn-info mb-2" id="btnApprove" data-id="{{ $payroll->id }}">
                <i class="fas fa-check mr-1"></i> Approve
              </button>
            @endif

            {{-- Mark Paid --}}
            @if($payroll && $payroll->status === 'approved')
              <button class="btn btn-block btn-success mb-2" id="btnPaid" data-id="{{ $payroll->id }}">
                <i class="fas fa-rupee-sign mr-1"></i> Mark as Paid
              </button>
            @endif

            {{-- PDF --}}
            @if($payroll)
              <a href="{{ route('payrolls.lathe-slip.pdf', [$employee->id, $payroll->id]) }}"
                 class="btn btn-block btn-danger mb-2" target="_blank">
                <i class="fas fa-file-pdf mr-1"></i> Download Payslip PDF
              </a>
            @endif

            <a href="{{ route('payrolls.show', $employee->id) }}" class="btn btn-block btn-secondary">
              <i class="fas fa-arrow-left mr-1"></i> Back to Payroll History
            </a>
          </div>
        </div>

        {{-- Extra Payments List --}}
        @if($payroll && $extraPayments->count())
          <div class="card card-outline card-success mt-3">
            <div class="card-header"><h3 class="card-title">Extra Payments</h3></div>
            <div class="card-body p-0">
              <table class="table table-sm mb-0">
                @foreach($extraPayments as $ep)
                  <tr>
                    <td>{{ $ep->payment_name }}</td>
                    <td class="text-right text-success">₹ {{ number_format($ep->amount, 2) }}</td>
                    <td class="text-center" style="width:40px">
                      @if($payroll->status === 'draft')
                        <button class="btn btn-xs btn-outline-danger btn-remove-extra"
                          data-payroll="{{ $payroll->id }}" data-extra="{{ $ep->id }}">
                          <i class="fas fa-times"></i>
                        </button>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </table>
            </div>
          </div>
        @endif

        {{-- Deduction Info --}}
        @if($payroll && $deductions > 0)
          <div class="card card-outline card-danger mt-3">
            <div class="card-header"><h3 class="card-title">Deduction</h3></div>
            <div class="card-body py-2">
              <div class="d-flex justify-content-between">
                <span>₹ {{ number_format($deductions, 2) }}</span>
                @if($deductionRemarks)
                  <small class="text-muted">{{ $deductionRemarks }}</small>
                @endif
              </div>
            </div>
          </div>
        @endif

      </div>
    </div>

  </div>
</section>

{{-- ── Extra Payment Modal ──────────────────────────────────────────── --}}
<div class="modal fade" id="modal-extra" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fas fa-plus-circle mr-1"></i> Add Extra Payment</h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Payment Description <span class="text-danger">*</span></label>
          <input type="text" id="extra_name" class="form-control" placeholder="e.g. Diwali Bonus, Overtime, Advance">
        </div>
        <div class="form-group">
          <label>Amount (₹) <span class="text-danger">*</span></label>
          <input type="number" id="extra_amount" class="form-control" min="0.01" step="0.01" placeholder="0.00">
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="btnSaveExtra">
          <i class="fas fa-save"></i> Add
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ── Deduction Modal ──────────────────────────────────────────────── --}}
<div class="modal fade" id="modal-deduction" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fas fa-minus-circle mr-1"></i> Update Deduction</h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Deduction Amount (₹) <span class="text-danger">*</span></label>
          <input type="number" id="ded_amount" class="form-control" min="0" step="0.01" value="{{ $deductions }}" placeholder="0.00">
        </div>
        <div class="form-group">
          <label>Reason</label>
          <textarea id="ded_remarks" class="form-control" rows="2" placeholder="Reason for deduction">{{ $deductionRemarks }}</textarea>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="btnSaveDed">
          <i class="fas fa-save"></i> Save
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
  <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script>
  var empId      = {{ $employee->id }};
  var month      = {{ $selectedMonth }};
  var year       = {{ $selectedYear }};
  var saveUrl    = '{{ route('payrolls.lathe-slip.save', $employee->id) }}';
  var payrollId  = {{ $payroll ? $payroll->id : 'null' }};

  var Toast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false,
    timer: 4000, timerProgressBar: true
  });

  function doPost(url, data, btn, cb) {
    var orig = btn.html();
    btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
    $.ajax({
      url: url, type: 'POST',
      data: Object.assign({ _token: '{{ csrf_token() }}' }, data),
      success: function (res) {
        btn.html(orig).prop('disabled', false);
        if (res.success) {
          Toast.fire({ icon: 'success', title: res.message });
          if (cb) cb(res); else setTimeout(() => location.reload(), 900);
        } else {
          Swal.fire('Error', res.message, 'error');
        }
      },
      error: function (xhr) {
        btn.html(orig).prop('disabled', false);
        var msg = xhr.responseJSON?.message || 'Something went wrong.';
        if (xhr.responseJSON?.errors) msg = Object.values(xhr.responseJSON.errors).flat().join(' ');
        Swal.fire('Error', msg, 'error');
      }
    });
  }

  // ── Save Payroll ──
  $('#btnSave').on('click', function () {
    doPost(saveUrl, { month: month, year: year }, $(this), function (res) {
      payrollId = res.payroll_id;
      setTimeout(() => location.reload(), 900);
    });
  });

  // ── Add Extra ──
  $('#btnSaveExtra').on('click', function () {
    if (!payrollId) { Swal.fire('Info', 'Please save the payroll first.', 'info'); return; }
    var url = '/payroll/payrolls/' + empId + '/lathe-slip/' + payrollId + '/extra';
    doPost(url, { payment_name: $('#extra_name').val(), amount: $('#extra_amount').val() }, $(this));
  });

  // ── Remove Extra ──
  $(document).on('click', '.btn-remove-extra', function () {
    var pid = $(this).data('payroll'), eid = $(this).data('extra');
    var url = '/payroll/payrolls/' + empId + '/lathe-slip/' + pid + '/extra/' + eid;
    var btn = $(this);
    Swal.fire({ title: 'Remove?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545' })
      .then(r => {
        if (r.isConfirmed) {
          var orig = btn.html();
          btn.prop('disabled', true);
          $.ajax({
            url: url, type: 'POST',
            data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
            success: function () { Toast.fire({ icon: 'success', title: 'Removed.' }); setTimeout(() => location.reload(), 700); },
            error: function () { btn.prop('disabled', false).html(orig); }
          });
        }
      });
  });

  // ── Save Deduction ──
  $('#btnSaveDed').on('click', function () {
    if (!payrollId) { Swal.fire('Info', 'Please save the payroll first.', 'info'); return; }
    var url = '/payroll/payrolls/' + empId + '/lathe-slip/' + payrollId + '/deduction';
    doPost(url, { deductions: $('#ded_amount').val(), deduction_remarks: $('#ded_remarks').val() }, $(this));
  });

  // ── Approve ──
  $('#btnApprove').on('click', function () {
    var id = $(this).data('id');
    Swal.fire({ title: 'Approve Payroll?', icon: 'question', showCancelButton: true, confirmButtonColor: '#17a2b8', confirmButtonText: 'Yes, Approve' })
      .then(r => {
        if (r.isConfirmed) {
          var url = '/payroll/payrolls/' + empId + '/lathe-slip/' + id + '/status';
          doPost(url, { status: 'approved' }, $('#btnApprove'));
        }
      });
  });

  // ── Mark Paid ──
  $('#btnPaid').on('click', function () {
    var id = $(this).data('id');
    Swal.fire({ title: 'Mark as Paid?', icon: 'question', showCancelButton: true, confirmButtonColor: '#28a745', confirmButtonText: 'Yes, Mark Paid' })
      .then(r => {
        if (r.isConfirmed) {
          var url = '/payroll/payrolls/' + empId + '/lathe-slip/' + id + '/status';
          doPost(url, { status: 'paid' }, $('#btnPaid'));
        }
      });
  });
  </script>
@endpush
