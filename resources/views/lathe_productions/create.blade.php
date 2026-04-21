@extends('layouts.app')

@section('title', config('app.name') . ' | Lathe Production Entry')

@push('styles')
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet"
    href="{{ asset('public/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <style>
    .prod-row td {
      vertical-align: middle !important;
      padding: 6px 8px;
    }

    .form-control-sm {
      font-size: .85rem;
    }

    #grandTotalBar {
      background: #f8f9fa;
      border-top: 2px solid #17a2b8;
    }

    .rate-display {
      font-weight: 600;
      color: #17a2b8;
    }

    .amount-display {
      font-weight: 700;
      color: #28a745;
    }
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
            <li class="breadcrumb-item"><a href="{{ route('lathe-productions.index') }}">Lathe Register</a></li>
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

      <form action="{{ route('lathe-productions.store') }}" method="POST" id="productionForm">
        @csrf

        {{-- ── Header Card ────────────────────────────────────────────── --}}
        <div class="card card-info card-outline">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-cog mr-1"></i>Entry Details</h3>
          </div>
          <div class="card-body">
            <div class="row">

              {{-- Employee --}}
              <div class="col-md-3">
                <div class="form-group">
                  <label>Employee <span class="text-danger">*</span></label>
                  <select name="employee_id" id="employee_id" class="form-control select2" required>
                    <option value="">-- Select Employee --</option>
                    @foreach($employees as $emp)
                      <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->emp_code }} – {{ $emp->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              {{-- Date --}}
              <div class="col-md-2">
                <div class="form-group">
                  <label>Date <span class="text-danger">*</span></label>
                  <input type="date" name="date" id="date" class="form-control"
                    value="{{ old('date', now()->toDateString()) }}" max="{{ now()->toDateString() }}" required>
                </div>
              </div>

              {{-- Shift --}}
              <div class="col-md-2">
                <div class="form-group">
                  <label>Shift <span class="text-danger">*</span></label>
                  <select name="shift" id="shift" class="form-control select2" required>
                    <option value="day" {{ old('shift') == 'day' ? 'selected' : '' }}>Day</option>
                    <option value="night" {{ old('shift') == 'night' ? 'selected' : '' }}>Night</option>
                    <option value="A" {{ old('shift') == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ old('shift') == 'B' ? 'selected' : '' }}>B</option>
                    <option value="general" {{ old('shift', 'general') == 'general' ? 'selected' : '' }}>General</option>
                  </select>
                </div>
              </div>

              {{-- Machine --}}
              <div class="col-md-3">
                <div class="form-group">
                  <label>Machine</label>
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

              {{-- Employee type badge --}}
              <!-- <div class="col-md-2">
                <div class="form-group">
                  <label>&nbsp;</label>
                  <div id="empTypeBadge" class="d-none mt-1">
                    <span class="badge badge-primary p-2" style="font-size:0.85rem;">Per Piece Model</span>
                    <br><small class="text-muted mt-1 d-block">Rate set per operation</small>
                  </div>
                </div>
              </div> -->

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
                    <th style="min-width:160px">Vendor/Company <span class="text-danger">*</span></th>
                    <th style="min-width:180px">Part No. <span class="text-danger">*</span></th>
                    <th style="min-width:180px">Operation <span class="text-danger">*</span></th>
                    <th style="width:80px">Qty <span class="text-danger">*</span></th>
                    <th style="width:110px">Rate (₹)</th>
                    <th style="width:110px">Amount (₹)</th>
                    <th style="min-width:140px">Downtime</th>
                    <th style="width:80px">Half Day</th>
                    <th style="min-width:160px">Remarks</th>
                    <th style="width:40px"></th>
                  </tr>
                </thead>
                <tbody id="rowsBody"></tbody>
              </table>
            </div>
          </div>
          <div class="card-footer p-0" id="grandTotalBar">
            <div class="px-3 py-2 d-flex justify-content-end align-items-center flex-wrap">
              <span class="mr-4 text-muted">Total Pieces: <strong id="totalQty">0</strong> pcs</span>
              <span class="text-info font-weight-bold">Total Amount: ₹ <span id="grandTotal">0.00</span></span>
            </div>
          </div>
        </div>

        <div class="card-footer text-right bg-transparent border-0 pr-0 pb-0">
          <a href="{{ route('lathe-productions.index') }}" class="btn btn-secondary mr-2">
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
        <select name="rows[__IDX__][operation_id]" class="form-control form-control-sm operation-select" disabled
          required>
          <option value="">-- Select Vendor First --</option>
        </select>
      </td>

      <td>
        <input type="number" name="rows[__IDX__][qty]" class="form-control form-control-sm qty-input" min="1" value="1"
          required>
      </td>

      <td>
        <span class="rate-display">0.00</span>
        <input type="hidden" name="rows[__IDX__][rate]" class="rate-hidden" value="0">
      </td>

      <td>
        <strong class="amount-display">0.00</strong>
      </td>

      <td>
        <select name="rows[__IDX__][downtime_type]" class="form-control form-control-sm downtime-select">
          <option value="">None</option>
          <option value="machine_breakdown">Machine Breakdown</option>
          <option value="power_cut">Power Cut</option>
          <option value="other">Other</option>
        </select>
        <input type="number" name="rows[__IDX__][downtime_minutes]"
          class="form-control form-control-sm mt-1 downtime-minutes d-none" min="0" placeholder="Minutes">
      </td>

      <td class="text-center align-middle">
        <input type="checkbox" name="rows[__IDX__][is_half_day]" class="half-day-check" value="1">
        <small class="d-block text-muted">Half</small>
      </td>

      <td>
        <input type="text" name="rows[__IDX__][remarks]" class="form-control form-control-sm"
          placeholder="Optional remark" maxlength="255">
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
  <script src="{{ asset('public/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

  <script>
    $(function () {

      // ── Select2 Init ────────────────────────────────────────────────
      $('.select2').select2({ theme: 'bootstrap4', width: '100%' });

      var partsUrl = '{{ route("lathe-productions.parts-by-company") }}';
      var operationsUrl = '{{ route("lathe-productions.operations-by-company") }}';
      var rateUrl = '{{ route("lathe-productions.operation-rate") }}';

      var rowCounter = 0;
      var currentEmployeeId = null;

      // ── Employee select → enable Add Row + submit ────────────────────
      $('#employee_id').on('change', function () {
        currentEmployeeId = $(this).val() || null;
        if (currentEmployeeId) {
          $('#empTypeBadge').removeClass('d-none');
          $('#addRowBtn').prop('disabled', false);
          $('#submitBtn').prop('disabled', $('#rowsBody .prod-row').length === 0);
          // Re-fetch rates for any existing rows
          var date = $('#date').val();
          $('#rowsBody .prod-row').each(function () {
            var $row = $(this);
            var opId = $row.find('.operation-select').val();
            if (opId) fetchRate($row, opId, date);
          });
        } else {
          $('#empTypeBadge').addClass('d-none');
          $('#addRowBtn').prop('disabled', true);
          $('#submitBtn').prop('disabled', true);
        }
      });

      // ── Add Row ──────────────────────────────────────────────────────
      $('#addRowBtn').on('click', addRow);

      function addRow() {
        var tmpl = document.getElementById('rowTemplate');
        var clone = document.importNode(tmpl.content, true);
        var html = $(clone).find('tr')[0].outerHTML.replace(/__IDX__/g, rowCounter++);
        $('#rowsBody').append($(html));
        reindexRows();
        updateTotals();
        $('#submitBtn').prop('disabled', false);
      }

      function reindexRows() {
        $('#rowsBody .prod-row').each(function (i) {
          $(this).find('.sno-cell').text(i + 1);
        });
      }

      // ── Company change → load parts + operations ─────────────────────
      $(document).on('change', '.company-select', function () {
        var $row = $(this).closest('.prod-row');
        var cid = $(this).val();
        var $part = $row.find('.part-select');
        var $op = $row.find('.operation-select');

        $part.html('<option value="">Loading...</option>').prop('disabled', true);
        $op.html('<option value="">Loading...</option>').prop('disabled', true);
        $row.find('.rate-display').text('0.00');
        $row.find('.rate-hidden').val(0);
        $row.find('.amount-display').text('0.00');
        updateTotals();

        if (!cid) {
          $part.html('<option value="">-- Select Vendor First --</option>');
          $op.html('<option value="">-- Select Vendor First --</option>');
          return;
        }

        // Load parts
        $.getJSON(partsUrl, { company_id: cid }, function (parts) {
          var opts = '<option value="">-- Part No. --</option>';
          $.each(parts, function (i, p) {
            opts += '<option value="' + p.id + '">' + p.part_number + (p.part_name ? ' — ' + p.part_name : '') + '</option>';
          });
          $part.html(opts).prop('disabled', false);
        }).fail(function () {
          $part.html('<option value="">Error loading parts</option>');
        });

        // Load operations
        $.getJSON(operationsUrl, { company_id: cid }, function (ops) {
          var opts = '<option value="">-- Operation --</option>';
          $.each(ops, function (i, op) {
            opts += '<option value="' + op.id + '">' + op.operation_name + '</option>';
          });
          $op.html(opts).prop('disabled', false);
        }).fail(function () {
          $op.html('<option value="">Error loading operations</option>');
        });
      });

      // ── Operation change → fetch rate ────────────────────────────────
      $(document).on('change', '.operation-select', function () {
        var $row = $(this).closest('.prod-row');
        var opId = $(this).val();
        if (!opId) {
          $row.find('.rate-display').text('0.00');
          $row.find('.rate-hidden').val(0);
          calcRowAmount($row);
          updateTotals();
          return;
        }
        fetchRate($row, opId, $('#date').val());
      });

      function fetchRate($row, opId, date) {
        $.getJSON(rateUrl, { operation_id: opId, employee_id: currentEmployeeId, date: date }, function (res) {
          var rate = parseFloat(res.rate) || 0;
          $row.find('.rate-display').text(rate.toFixed(2));
          $row.find('.rate-hidden').val(rate);
          calcRowAmount($row);
          updateTotals();
        }).fail(function () {
          $row.find('.rate-display').text('0.00');
          $row.find('.rate-hidden').val(0);
          calcRowAmount($row);
          updateTotals();
        });
      }

      // ── Date change → re-fetch rates for all rows ────────────────────
      $('#date').on('change', function () {
        var date = $(this).val();
        $('#rowsBody .prod-row').each(function () {
          var $row = $(this);
          var opId = $row.find('.operation-select').val();
          if (opId) fetchRate($row, opId, date);
        });
      });

      // ── Qty change → recalc ──────────────────────────────────────────
      $(document).on('input', '.qty-input', function () {
        calcRowAmount($(this).closest('.prod-row'));
        updateTotals();
      });

      function calcRowAmount($row) {
        var rate = parseFloat($row.find('.rate-hidden').val()) || 0;
        var qty = parseInt($row.find('.qty-input').val()) || 0;
        $row.find('.amount-display').text((rate * qty).toFixed(2));
      }

      function updateTotals() {
        var totalQty = 0, grandTotal = 0;
        $('#rowsBody .prod-row').each(function () {
          totalQty += parseInt($(this).find('.qty-input').val()) || 0;
          var rate = parseFloat($(this).find('.rate-hidden').val()) || 0;
          var qty = parseInt($(this).find('.qty-input').val()) || 0;
          grandTotal += rate * qty;
        });
        $('#totalQty').text(totalQty);
        $('#grandTotal').text(grandTotal.toFixed(2));
      }

      // ── Downtime minutes show/hide ───────────────────────────────────
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
      $('#productionForm').on('submit', function (e) {
        if ($('#rowsBody .prod-row').length === 0) {
          e.preventDefault();
          Swal.fire({ icon: 'warning', title: 'No Rows', text: 'Please add at least one production row.' });
          return;
        }
        var $btn = $('#submitBtn');
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
      });

    });
  </script>
@endpush