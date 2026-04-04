@extends('layouts.app')

@section('title', config('app.name') . ' | CNC Production Entry')

@push('styles')
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <style>
    .prod-row td { vertical-align: middle !important; padding: 6px 8px; }
    .form-control-sm { font-size: .85rem; }
    #grandTotalBar { background: #f8f9fa; border-top: 2px solid #17a2b8; }
    .target-badge { font-size: 0.75rem; }
    .incentive-col { background: #fff8e1; }
  </style>
@endpush

@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-cog mr-2"></i>CNC Production Entry</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('cnc-productions.index') }}">CNC Register</a></li>
          <li class="breadcrumb-item active">New Entry</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
      </div>
    @endif

    <form action="{{ route('cnc-productions.store') }}" method="POST" id="cncForm">
      @csrf

      {{-- ── Header Card: Employee + Date + Shift ─────────────────── --}}
      <div class="card card-info card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-user-cog mr-1"></i>Entry Details</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Employee <span class="text-danger">*</span></label>
                <select name="employee_id" id="employee_id" class="form-control select2" required>
                  <option value="">-- Select Employee --</option>
                  @foreach($employees as $emp)
                    <option value="{{ $emp->id }}"
                      data-payment="{{ $emp->cnc_payment_type }}"
                      data-target="{{ $emp->cnc_target_per_shift }}"
                      data-incentive="{{ $emp->cnc_incentive_rate }}">
                      {{ $emp->emp_code }} – {{ $emp->name }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Date <span class="text-danger">*</span></label>
                <input type="date" name="date" id="entry_date" class="form-control"
                  value="{{ now()->toDateString() }}" max="{{ now()->toDateString() }}" required>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Shift <span class="text-danger">*</span></label>
                <select name="shift" id="shift" class="form-control select2" required>
                  <option value="day">Day</option>
                  <option value="night">Night</option>
                  <option value="A">A</option>
                  <option value="B">B</option>
                  <option value="general" selected>General</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Machine</label>
                <select name="machine_id" id="machine_id" class="form-control select2">
                  <option value="">-- Select Machine --</option>
                  @foreach($machines as $m)
                    <option value="{{ $m->id }}">{{ $m->machine_name }} ({{ $m->machine_number }})</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-2">
              {{-- Employee payment model info badge --}}
              <div class="form-group">
                <label>&nbsp;</label>
                <div id="paymentModelBadge" class="d-none mt-1">
                  <span id="paymentModelLabel" class="badge p-2" style="font-size:0.85rem;"></span>
                  <br><small id="targetInfo" class="text-muted mt-1 d-block"></small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ── Work Rows ──────────────────────────────────────────────── --}}
      <div class="card card-warning card-outline">
        <div class="card-header d-flex align-items-center">
          <h3 class="card-title mb-0"><i class="fas fa-list mr-1"></i>Production Details</h3>
          <button type="button" id="addRowBtn" class="btn btn-sm btn-success ml-auto" disabled>
            <i class="fas fa-plus"></i> Add Row
          </button>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0" id="rowsTable">
              <thead class="thead-light">
                <tr>
                  <th style="width:40px">#</th>
                  <th style="min-width:160px">Vendor <span class="text-danger">*</span></th>
                  <th style="min-width:150px">Part No. <span class="text-danger">*</span></th>
                  <th style="min-width:140px">Operation Type <span class="text-danger">*</span></th>
                  <th style="width:90px">Produced Qty <span class="text-danger">*</span></th>
                  <th style="width:80px">Target Qty</th>
                  <th style="width:80px" class="incentive-col">Incentive</th>
                  <th style="width:100px" id="rateColHeader">Rate (₹)</th>
                  <th style="width:100px">Amount (₹)</th>
                  <th style="min-width:140px">Downtime</th>
                  <th style="width:80px">Half Day</th>
                  <th style="min-width:140px">Remark</th>
                  <th style="width:40px"></th>
                </tr>
              </thead>
              <tbody id="rowsBody"></tbody>
            </table>
          </div>
        </div>
        <div class="card-footer p-0" id="grandTotalBar">
          <div class="px-3 py-2 d-flex justify-content-end align-items-center flex-wrap">
            <span class="mr-4 text-muted">Total Produced: <strong id="totalQty">0</strong> pcs</span>
            <span class="mr-4 text-muted">Total Incentive: <strong id="totalIncentive" class="text-warning">0</strong> pcs</span>
            <span class="text-info font-weight-bold">Total Amount: ₹ <span id="grandTotal">0.00</span></span>
          </div>
        </div>
      </div>

      <div class="card-footer text-right bg-transparent border-0 pr-0 pb-0">
        <a href="{{ route('cnc-productions.index') }}" class="btn btn-secondary mr-2">
          <i class="fas fa-times mr-1"></i> Cancel
        </a>
        <button type="submit" id="submitBtn" class="btn btn-info" disabled>
          <i class="fas fa-save mr-1"></i> Save Entries
        </button>
      </div>

    </form>
  </div>
</section>

{{-- Row Template --}}
<template id="rowTemplate">
  <tr class="prod-row">
    <td class="sno-cell text-center align-middle font-weight-bold"></td>

    <td>
      <select name="rows[__IDX__][company_id]" class="form-control form-control-sm company-select" required>
        <option value="">-- Vendor --</option>
        @foreach($companies as $c)
          <option value="{{ $c->id }}">{{ $c->company_name }}</option>
        @endforeach
      </select>
    </td>

    <td>
      <select name="rows[__IDX__][part_id]" class="form-control form-control-sm part-select" disabled required>
        <option value="">-- Select Vendor First --</option>
      </select>
    </td>

    <td>
      <select name="rows[__IDX__][operation_type]" class="form-control form-control-sm" required>
        <option value="">-- Type --</option>
        @foreach(\App\Models\CncProduction::operationTypeLabels() as $val => $label)
          <option value="{{ $val }}">{{ $label }}</option>
        @endforeach
      </select>
    </td>

    <td class="qty-col">
      <input type="number" name="rows[__IDX__][production_qty]" class="form-control form-control-sm qty-input"
        min="0" value="0" required>
    </td>

    <td>
      <input type="number" name="rows[__IDX__][target_qty]" class="form-control form-control-sm target-input"
        min="0" placeholder="auto">
      <small class="text-muted target-badge d-block"></small>
    </td>

    <td class="incentive-col text-center align-middle">
      <strong class="incentive-display text-warning">0</strong>
      <small class="text-muted d-block">pcs</small>
    </td>

    <td>
      <span class="rate-display text-muted">—</span>
      <input type="hidden" name="rows[__IDX__][rate_per_piece]" class="rate-hidden" value="0">
    </td>

    <td>
      <strong class="amount-display text-info">0.00</strong>
      <input type="hidden" name="rows[__IDX__][amount]" class="amount-hidden" value="0">
    </td>

    <td>
      <select name="rows[__IDX__][downtime_type]" class="form-control form-control-sm downtime-select">
        <option value="">None</option>
        <option value="machine_breakdown">Machine Breakdown</option>
        <option value="power_cut">Power Cut</option>
        <option value="other">Other</option>
      </select>
      <input type="number" name="rows[__IDX__][downtime_minutes]" class="form-control form-control-sm mt-1 downtime-minutes d-none"
        min="0" placeholder="Minutes">
    </td>

    <td class="text-center align-middle">
      <input type="checkbox" name="rows[__IDX__][is_half_day]" class="half-day-check" value="1">
      <small class="d-block text-muted">Half</small>
    </td>

    <td>
      <input type="text" name="rows[__IDX__][remark]" class="form-control form-control-sm"
        placeholder="Optional remark" maxlength="500">
    </td>

    <td class="text-center align-middle">
      <button type="button" class="btn btn-sm btn-danger delete-row-btn">
        <i class="fas fa-times"></i>
      </button>
    </td>
  </tr>
</template>

@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function () {

  // ── Select2 Init ────────────────────────────────────────────────
  $('.select2').select2({ theme: 'bootstrap4', width: '100%' });

  var partsUrl      = '{{ route("cnc-productions.parts-by-company") }}';
  var empSettingUrl = '{{ route("cnc-productions.employee-settings") }}';
  var csrfToken     = '{{ csrf_token() }}';

  var rowCounter    = 0;
  var empPayType    = 'day_rate';
  var empTarget     = 90;
  var empIncentiveRate = 0;

  // ── Employee select → fetch settings ────────────────────────────
  $('#employee_id').on('change', function () {
    var sel = $(this).find(':selected');
    var empId = $(this).val();
    if (!empId) {
      $('#paymentModelBadge').addClass('d-none');
      $('#addRowBtn, #submitBtn').prop('disabled', true);
      return;
    }

    empPayType       = sel.data('payment') || 'day_rate';
    empTarget        = parseInt(sel.data('target')) || 90;
    empIncentiveRate = parseFloat(sel.data('incentive')) || 0;

    // Update badge
    var badge = $('#paymentModelLabel');
    if (empPayType === 'per_piece') {
      badge.text('Per Piece Model').removeClass('badge-secondary').addClass('badge-primary');
      $('#targetInfo').text('Rate set per operation — see Operation Rates');
      $('#rateColHeader').text('Rate (₹/pc)');
    } else {
      badge.text('Day Rate + Incentive').removeClass('badge-primary').addClass('badge-secondary');
      $('#targetInfo').text('Target: ' + empTarget + ' pcs/shift | Incentive: ₹' + empIncentiveRate.toFixed(2) + '/pc above');
      $('#rateColHeader').text('Incentive Rate');
    }
    $('#paymentModelBadge').removeClass('d-none');
    $('#addRowBtn').prop('disabled', false);
    $('#submitBtn').prop('disabled', rowCounter === 0);

    // Update existing rows
    $('#rowsBody .prod-row').each(function () {
      updateRowCalc($(this));
    });
  });

  // ── Add Row ──────────────────────────────────────────────────────
  $('#addRowBtn').on('click', addRow);

  function addRow() {
    var tmpl = document.getElementById('rowTemplate');
    var clone = document.importNode(tmpl.content, true);
    var html = $(clone).find('tr')[0].outerHTML.replace(/__IDX__/g, rowCounter++);
    var $tr = $(html);
    $('#rowsBody').append($tr);

    // Set default target from employee
    $tr.find('.target-input').val(empTarget);
    $tr.find('.target-badge').text('Default: ' + empTarget);

    // Show rate info based on payment model
    if (empPayType === 'day_rate') {
      $tr.find('.rate-display').text('₹' + empIncentiveRate.toFixed(2) + '/pc');
      $tr.find('.rate-hidden').val(empIncentiveRate);
    }

    reindexRows();
    updateTotals();
    $('#submitBtn').prop('disabled', false);
  }

  // ── Reindex S.No ─────────────────────────────────────────────────
  function reindexRows() {
    $('#rowsBody .prod-row').each(function (i) {
      $(this).find('.sno-cell').text(i + 1);
    });
  }

  // ── Company change → load parts ──────────────────────────────────
  $(document).on('change', '.company-select', function () {
    var $row = $(this).closest('.prod-row');
    var cid  = $(this).val();
    var $part = $row.find('.part-select');

    $part.html('<option value="">Loading...</option>').prop('disabled', true);
    if (!cid) { $part.html('<option value="">-- Select Vendor First --</option>'); return; }

    $.getJSON(partsUrl, { company_id: cid }, function (parts) {
      var opts = '<option value="">-- Part No. --</option>';
      $.each(parts, function (i, p) {
        opts += '<option value="' + p.id + '">' + p.part_number + (p.part_name ? ' — ' + p.part_name : '') + '</option>';
      });
      $part.html(opts).prop('disabled', false);
    }).fail(function () {
      $part.html('<option value="">Error loading parts</option>');
    });
  });

  // ── Qty change → recalc ──────────────────────────────────────────
  $(document).on('input', '.qty-input, .target-input', function () {
    updateRowCalc($(this).closest('.prod-row'));
    updateTotals();
  });

  function updateRowCalc($row) {
    var qty    = parseInt($row.find('.qty-input').val()) || 0;
    var target = parseInt($row.find('.target-input').val()) || empTarget;
    var incentive = Math.max(0, qty - target);
    $row.find('.incentive-display').text(incentive);

    var amount = 0;
    if (empPayType === 'per_piece') {
      var rate = parseFloat($row.find('.rate-hidden').val()) || 0;
      amount = qty * rate;
      $row.find('.rate-display').text(rate > 0 ? '₹' + rate.toFixed(2) : '—');
    } else {
      amount = incentive * empIncentiveRate;
      $row.find('.rate-display').text('₹' + empIncentiveRate.toFixed(2));
      $row.find('.rate-hidden').val(empIncentiveRate);
    }
    $row.find('.amount-display').text(amount.toFixed(2));
    $row.find('.amount-hidden').val(amount.toFixed(2));
  }

  // ── Grand Totals ─────────────────────────────────────────────────
  function updateTotals() {
    var totalQty = 0, totalIncentive = 0, grandTotal = 0;
    $('#rowsBody .prod-row').each(function () {
      totalQty       += parseInt($(this).find('.qty-input').val()) || 0;
      totalIncentive += parseInt($(this).find('.incentive-display').text()) || 0;
      grandTotal     += parseFloat($(this).find('.amount-hidden').val()) || 0;
    });
    $('#totalQty').text(totalQty);
    $('#totalIncentive').text(totalIncentive);
    $('#grandTotal').text(grandTotal.toFixed(2));
  }

  // ── Downtime minutes show/hide ────────────────────────────────────
  $(document).on('change', '.downtime-select', function () {
    var $row = $(this).closest('.prod-row');
    if ($(this).val()) {
      $row.find('.downtime-minutes').removeClass('d-none');
    } else {
      $row.find('.downtime-minutes').addClass('d-none').val('');
    }
  });

  // ── Delete row ───────────────────────────────────────────────────
  $(document).on('click', '.delete-row-btn', function () {
    $(this).closest('.prod-row').remove();
    reindexRows();
    updateTotals();
    if ($('#rowsBody .prod-row').length === 0) {
      $('#submitBtn').prop('disabled', true);
    }
  });

  // ── Form submit ──────────────────────────────────────────────────
  $('#cncForm').on('submit', function (e) {
    if ($('#rowsBody .prod-row').length === 0) {
      e.preventDefault();
      Swal.fire({ icon: 'warning', title: 'No Rows', text: 'Please add at least one production row.' });
      return;
    }
    var $btn = $('#submitBtn');
    $btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
  });

  // ── Date change → mark sunday ────────────────────────────────────
  $('#entry_date').on('change', function () {
    var d = new Date($(this).val());
    if (d.getDay() === 0) { // Sunday
      Swal.fire({ icon: 'info', title: 'Sunday', text: 'Sunday entries will be marked as half-day automatically.', timer: 2500, showConfirmButton: false });
    }
  });

});
</script>
@endpush
