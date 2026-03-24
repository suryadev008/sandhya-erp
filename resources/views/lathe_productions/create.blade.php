{{-- ============================================================
resources/views/lathe_productions/create.blade.php
============================================================ --}}

@extends('layouts.app')

@section('title', config('app.name') . ' | Lathe Production Entry')

@push('styles')
<style>
  .row-table th, .row-table td { vertical-align: middle; }
  .row-table .sno-col  { width: 50px;  text-align: center; }
  .row-table .qty-col  { width: 90px;  }
  .row-table .rate-col { width: 100px; }
  .row-table .amt-col  { width: 110px; }
  .row-table .del-col  { width: 50px;  text-align: center; }
  .rate-display { font-weight: 600; color: #17a2b8; }
  .amount-display { font-weight: 700; color: #28a745; }
</style>
@endpush

@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-industry mr-2"></i>Lathe Production Entry</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item">Production Register</li>
          <li class="breadcrumb-item active">Lathe Entry</li>
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
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong><i class="fas fa-exclamation-triangle mr-1"></i> Please fix the errors below:</strong>
        <ul class="mb-0 mt-1">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('lathe-productions.store') }}" method="POST" id="productionForm">
      @csrf

      {{-- ===== Header Section ===== --}}
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-user-cog mr-1"></i> Entry Details</h3>
        </div>
        <div class="card-body">
          <div class="row">

            {{-- Employee --}}
            <div class="col-md-4">
              <div class="form-group">
                <label for="employee_id">Employee <span class="text-danger">*</span></label>
                <select name="employee_id" id="employee_id" class="form-control select2 @error('employee_id') is-invalid @enderror" required>
                  <option value="">-- Select Employee --</option>
                  @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                      {{ $emp->emp_code }} – {{ $emp->name }}
                    </option>
                  @endforeach
                </select>
                @error('employee_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            {{-- Date --}}
            <div class="col-md-2">
              <div class="form-group">
                <label for="date">Date <span class="text-danger">*</span></label>
                <input type="date" name="date" id="date"
                  class="form-control @error('date') is-invalid @enderror"
                  value="{{ old('date') }}"
                  max="{{ date('Y-m-d') }}"
                  required>
                @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            {{-- Shift --}}
            <div class="col-md-2">
              <div class="form-group">
                <label for="shift">Shift <span class="text-danger">*</span></label>
                <select name="shift" id="shift" class="form-control @error('shift') is-invalid @enderror" required>
                  <option value="">-- Shift --</option>
                  <option value="day"     {{ old('shift') == 'day'     ? 'selected' : '' }}>Day</option>
                  <option value="night"   {{ old('shift') == 'night'   ? 'selected' : '' }}>Night</option>
                  <option value="A"       {{ old('shift') == 'A'       ? 'selected' : '' }}>A</option>
                  <option value="B"       {{ old('shift') == 'B'       ? 'selected' : '' }}>B</option>
                  <option value="general" {{ old('shift') == 'general' ? 'selected' : '' }}>General</option>
                </select>
                @error('shift')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            {{-- Machine (optional) --}}
            <div class="col-md-4">
              <div class="form-group">
                <label for="machine_id">Machine <span class="text-muted">(optional)</span></label>
                <select name="machine_id" id="machine_id" class="form-control select2">
                  <option value="">-- Select Machine --</option>
                  @foreach($machines as $machine)
                    <option value="{{ $machine->id }}" {{ old('machine_id') == $machine->id ? 'selected' : '' }}>
                      {{ $machine->machine_name }} ({{ $machine->machine_number }})
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

          </div>
        </div>
      </div>
      {{-- /.Header Section --}}

      {{-- ===== Work Rows Section ===== --}}
      <div class="card card-warning card-outline">
        <div class="card-header d-flex align-items-center">
          <h3 class="card-title mb-0"><i class="fas fa-list mr-1"></i> Work Details</h3>
          <button type="button" class="btn btn-sm btn-success ml-auto" id="addRowBtn">
            <i class="fas fa-plus"></i> Add Row
          </button>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-bordered table-sm mb-0 row-table" id="rowsTable">
              <thead class="thead-light">
                <tr>
                  <th class="sno-col">#</th>
                  <th>Company <span class="text-danger">*</span></th>
                  <th>Part No. <span class="text-danger">*</span></th>
                  <th>Operation <span class="text-danger">*</span></th>
                  <th class="qty-col">Qty <span class="text-danger">*</span></th>
                  <th class="rate-col">Rate (₹)</th>
                  <th class="amt-col">Amount (₹)</th>
                  <th>Remarks</th>
                  <th class="del-col"><i class="fas fa-trash-alt text-danger"></i></th>
                </tr>
              </thead>
              <tbody id="rowsBody">
                {{-- rows appended by JS --}}
              </tbody>
              <tfoot>
                <tr class="table-light">
                  <td colspan="6" class="text-right font-weight-bold pr-3">Total Amount:</td>
                  <td class="font-weight-bold text-success" id="grandTotal">₹ 0.00</td>
                  <td colspan="2"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="button" class="btn btn-secondary mr-2" onclick="window.location='{{ route('dashboard') }}'">
            <i class="fas fa-times mr-1"></i> Cancel
          </button>
          <button type="submit" class="btn btn-primary" id="submitBtn">
            <i class="fas fa-save mr-1"></i> Save Production Entry
          </button>
        </div>
      </div>
      {{-- /.Work Rows Section --}}

    </form>

  </div>
</section>

{{-- Hidden template row (cloned by JS) --}}
<template id="rowTemplate">
  <tr class="prod-row">
    <td class="sno-col sno-cell text-center align-middle"></td>

    <td>
      <select name="rows[__IDX__][company_id]" class="form-control form-control-sm company-select" required>
        <option value="">-- Company --</option>
        @foreach($companies as $c)
          <option value="{{ $c->id }}">{{ $c->company_name }}</option>
        @endforeach
      </select>
    </td>

    <td>
      <select name="rows[__IDX__][part_id]" class="form-control form-control-sm part-select" required disabled>
        <option value="">-- Select Company First --</option>
      </select>
    </td>

    <td>
      <select name="rows[__IDX__][operation_id]" class="form-control form-control-sm operation-select" required disabled>
        <option value="">-- Select Company First --</option>
      </select>
    </td>

    <td class="qty-col">
      <input type="number" name="rows[__IDX__][qty]" class="form-control form-control-sm qty-input"
        min="1" value="1" required>
    </td>

    <td class="rate-col">
      <span class="rate-display">0.00</span>
      <input type="hidden" name="rows[__IDX__][rate]" class="rate-hidden" value="0">
    </td>

    <td class="amt-col">
      <span class="amount-display">0.00</span>
    </td>

    <td>
      <input type="text" name="rows[__IDX__][remarks]" class="form-control form-control-sm" placeholder="Optional">
    </td>

    <td class="del-col text-center align-middle">
      <button type="button" class="btn btn-sm btn-danger delete-row-btn" title="Remove">
        <i class="fas fa-times"></i>
      </button>
    </td>
  </tr>
</template>

@endsection

@push('scripts')
{{-- Select2 --}}
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>

<script>
$(document).ready(function () {

  // Init Select2 for header fields
  $('#employee_id, #machine_id').select2({ theme: 'bootstrap4', width: '100%' });

  var _base         = window.location.origin;
  var partsUrl      = _base + '{{ parse_url(route('lathe-productions.parts-by-company'), PHP_URL_PATH) }}';
  var operationsUrl = _base + '{{ parse_url(route('lathe-productions.operations-by-company'), PHP_URL_PATH) }}';
  var rateUrl       = _base + '{{ parse_url(route('lathe-productions.operation-rate'), PHP_URL_PATH) }}';

  // ── Add first row on load ──────────────────────────────────────────
  addRow();

  // ── Add Row Button ─────────────────────────────────────────────────
  $('#addRowBtn').on('click', addRow);

  // ── Delete Row ─────────────────────────────────────────────────────
  $(document).on('click', '.delete-row-btn', function () {
    if ($('#rowsBody .prod-row').length === 1) {
      alert('At least one row is required.');
      return;
    }
    $(this).closest('tr.prod-row').remove();
    reindexRows();
    updateGrandTotal();
  });

  // ── Company Change → Load Parts + Operations ───────────────────────
  $(document).on('change', '.company-select', function () {
    var $row      = $(this).closest('tr.prod-row');
    var companyId = $(this).val();

    var $partSel = $row.find('.part-select');
    var $opSel   = $row.find('.operation-select');

    // Reset parts
    $partSel.prop('disabled', true).html('<option value="">Loading...</option>');
    // Reset operations
    $opSel.prop('disabled', true).html('<option value="">Loading...</option>');
    // Reset rate/amount
    $row.find('.rate-display').text('0.00');
    $row.find('.rate-hidden').val(0);
    $row.find('.amount-display').text('0.00');
    updateGrandTotal();

    if (!companyId) {
      $partSel.html('<option value="">-- Select Company First --</option>');
      $opSel.html('<option value="">-- Select Company First --</option>');
      return;
    }

    // Load parts
    $.getJSON(partsUrl, { company_id: companyId }, function (parts) {
      var opts = '<option value="">-- Part No. --</option>';
      $.each(parts, function (i, p) {
        opts += '<option value="' + p.id + '">' + p.part_number + (p.part_name ? ' – ' + p.part_name : '') + '</option>';
      });
      $partSel.html(opts).prop('disabled', false);
    }).fail(function () {
      $partSel.html('<option value="">-- Error loading parts --</option>').prop('disabled', false);
    });

    // Load operations
    $.getJSON(operationsUrl, { company_id: companyId }, function (ops) {
      var opts = '<option value="">-- Operation --</option>';
      $.each(ops, function (i, op) {
        opts += '<option value="' + op.id + '">' + op.operation_name + '</option>';
      });
      $opSel.html(opts).prop('disabled', false);
    }).fail(function () {
      $opSel.html('<option value="">-- Error loading operations --</option>').prop('disabled', false);
    });
  });

  // ── Operation Change → Fetch Rate via AJAX ────────────────────────
  $(document).on('change', '.operation-select', function () {
    var $row        = $(this).closest('tr.prod-row');
    var operationId = $(this).val();
    var date        = $('#date').val();

    if (!operationId) {
      $row.find('.rate-display').text('0.00');
      $row.find('.rate-hidden').val(0);
      calcRowAmount($row);
      updateGrandTotal();
      return;
    }

    $.getJSON(rateUrl, { operation_id: operationId, date: date }, function (res) {
      var rate = parseFloat(res.rate) || 0;
      $row.find('.rate-display').text(rate.toFixed(2));
      $row.find('.rate-hidden').val(rate);
      calcRowAmount($row);
      updateGrandTotal();
    }).fail(function () {
      $row.find('.rate-display').text('0.00');
      $row.find('.rate-hidden').val(0);
      calcRowAmount($row);
      updateGrandTotal();
    });
  });

  // ── Date Change → Re-fetch Rates for All Rows ────────────────────
  $('#date').on('change', function () {
    var date = $(this).val();
    $('#rowsBody .prod-row').each(function () {
      var $row        = $(this);
      var operationId = $row.find('.operation-select').val();
      if (!operationId) return;

      $.getJSON(rateUrl, { operation_id: operationId, date: date }, function (res) {
        var rate = parseFloat(res.rate) || 0;
        $row.find('.rate-display').text(rate.toFixed(2));
        $row.find('.rate-hidden').val(rate);
        calcRowAmount($row);
        updateGrandTotal();
      });
    });
  });

  // ── Qty Change → Recalc Amount ─────────────────────────────────────
  $(document).on('input', '.qty-input', function () {
    var $row = $(this).closest('tr.prod-row');
    calcRowAmount($row);
    updateGrandTotal();
  });

  // ── Form Submit Validation ──────────────────────────────────────────
  $('#productionForm').on('submit', function (e) {
    if ($('#rowsBody .prod-row').length === 0) {
      e.preventDefault();
      alert('Please add at least one work row before submitting.');
      return false;
    }
  });

  // ─────────────────────────────────────────────────────────────────────
  // Helper Functions
  // ─────────────────────────────────────────────────────────────────────

  var rowCounter = 0;

  function addRow() {
    var template = document.getElementById('rowTemplate');
    var clone    = document.importNode(template.content, true);
    var $tr      = $(clone).find('tr');
    var html     = $tr[0].outerHTML.replace(/__IDX__/g, rowCounter);
    rowCounter++;
    $('#rowsBody').append($(html));
    reindexRows();
  }

  function reindexRows() {
    $('#rowsBody .prod-row').each(function (i) {
      $(this).find('.sno-cell').text(i + 1);
    });
  }

  function calcRowAmount($row) {
    var rate   = parseFloat($row.find('.rate-hidden').val()) || 0;
    var qty    = parseInt($row.find('.qty-input').val())     || 0;
    $row.find('.amount-display').text((rate * qty).toFixed(2));
  }

  function updateGrandTotal() {
    var total = 0;
    $('#rowsBody .prod-row').each(function () {
      var rate = parseFloat($(this).find('.rate-hidden').val()) || 0;
      var qty  = parseInt($(this).find('.qty-input').val())     || 0;
      total   += rate * qty;
    });
    $('#grandTotal').text('₹ ' + total.toFixed(2));
  }

});
</script>
@endpush
