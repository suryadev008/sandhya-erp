@extends('layouts.app')

@section('title', config('app.name') . ' | ' . $employee->name . ' – CNC Entries')

@push('styles')
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <style>
    .filter-card .form-group { margin-bottom: 0; }
    .locked-badge { font-size: 12px; }
    .tfoot-total td { font-weight: 700; background: #f1f3f5; }
    .sunday-row { background: #fff8e1 !important; }
    .halfday-row { background: #f3e5f5 !important; }
    .target-not-met { color: #dc3545; }
    .target-met { color: #28a745; }
  </style>
@endpush

@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">
          <i class="fas fa-cog mr-2"></i>
          {{ $employee->name }}
          <small class="text-muted" style="font-size:14px;">{{ $employee->emp_code }}</small>
          @if($employee->cnc_payment_type === 'per_piece')
            <span class="badge badge-primary ml-2" style="font-size:11px;">Per Piece</span>
          @else
            <span class="badge badge-secondary ml-2" style="font-size:11px;">Day Rate + Incentive</span>
          @endif
        </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('cnc-productions.index') }}">CNC Register</a></li>
          <li class="breadcrumb-item active">{{ $employee->name }}</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    {{-- Filters --}}
    <div class="card card-outline card-secondary filter-card mb-3">
      <div class="card-body py-2">
        <form method="GET" action="{{ route('cnc-productions.show', $employee->id) }}" id="filterForm">
          <div class="row align-items-end">
            <div class="col-md-2">
              <div class="form-group">
                <label class="mb-1"><small>Year</small></label>
                <select name="year" class="form-control form-control-sm">
                  @foreach($years as $y)
                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label class="mb-1"><small>Month</small></label>
                <select name="month" class="form-control form-control-sm">
                  @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                      {{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label class="mb-1"><small>Specific Date</small></label>
                <input type="date" name="date" class="form-control form-control-sm"
                  value="{{ $date }}" max="{{ now()->toDateString() }}">
              </div>
            </div>
            <div class="col-md-4 d-flex align-items-end pb-1">
              <button type="submit" class="btn btn-sm btn-info mr-2">
                <i class="fas fa-search mr-1"></i> Apply
              </button>
              <a href="{{ route('cnc-productions.show', $employee->id) }}" class="btn btn-sm btn-outline-secondary mr-2">
                <i class="fas fa-times mr-1"></i> Clear
              </a>
              <a href="{{ route('cnc-productions.create') }}" class="btn btn-sm btn-success ml-auto">
                <i class="fas fa-plus mr-1"></i> New Entry
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row mb-3">
      <div class="col-md-3">
        <div class="info-box bg-info">
          <span class="info-box-icon"><i class="fas fa-calendar-check"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Present Days</span>
            <span class="info-box-number">{{ $presentDays }}</span>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="info-box bg-primary">
          <span class="info-box-icon"><i class="fas fa-boxes"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Produced</span>
            <span class="info-box-number">{{ number_format($totalQty) }} pcs</span>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="info-box bg-warning">
          <span class="info-box-icon"><i class="fas fa-star"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Incentive Pieces</span>
            <span class="info-box-number">{{ number_format($totalIncentive) }} pcs</span>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="info-box bg-success">
          <span class="info-box-icon"><i class="fas fa-rupee-sign"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Amount</span>
            <span class="info-box-number">₹ {{ number_format($totalAmount, 2) }}</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Payroll Lock Notice --}}
    @if($locked)
      <div class="alert alert-warning">
        <i class="fas fa-lock mr-1"></i>
        <strong>Payroll Locked</strong> — This month's payroll is <strong>{{ ucfirst($payroll->status) }}</strong>.
        Entries can only be edited by Admin after unlocking the payroll.
      </div>
    @endif

    {{-- Entries Table --}}
    <div class="card card-info card-outline">
      <div class="card-header d-flex align-items-center">
        <h3 class="card-title mb-0">
          <i class="fas fa-table mr-1"></i>
          CNC Entries —
          {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}
          @if($date)
            <small class="text-muted">(Filtered: {{ \Carbon\Carbon::parse($date)->format('d M Y') }})</small>
          @endif
        </h3>
        <span class="badge badge-info ml-2 p-2">{{ $entries->count() }} record(s)</span>
      </div>
      <div class="card-body p-0">
        @if($entries->isEmpty())
          <div class="text-center py-5 text-muted">
            <i class="fas fa-inbox fa-2x mb-2"></i>
            <p>No entries found for this period.</p>
          </div>
        @else
        <div class="table-responsive">
          <table id="entriesTable" class="table table-bordered table-sm table-hover mb-0">
            <thead class="thead-light">
              <tr>
                <th>#</th>
                <th>Date</th>
                <th>Shift</th>
                <th>Machine</th>
                <th>Vendor</th>
                <th>Part</th>
                <th>Op. Type</th>
                <th class="text-center">Produced</th>
                <th class="text-center">Target</th>
                <th class="text-center">Incentive</th>
                <th class="text-right">Amount (₹)</th>
                <th>Downtime</th>
                <th>Flags</th>
                @if(!$locked)<th class="text-center" style="width:70px">Action</th>@endif
              </tr>
            </thead>
            <tbody>
              @foreach($entries as $i => $entry)
              @php
                $rowClass = $entry->is_sunday ? 'sunday-row' : ($entry->is_half_day ? 'halfday-row' : '');
              @endphp
              <tr class="{{ $rowClass }}"
                data-id="{{ (int) $entry->id }}"
                data-date="{{ $entry->date->format('Y-m-d') }}"
                data-shift="{{ e($entry->shift) }}"
                data-company="{{ (int) $entry->company_id }}"
                data-part="{{ (int) $entry->part_id }}"
                data-operation-type="{{ e($entry->operation_type) }}"
                data-qty="{{ (int) $entry->production_qty }}"
                data-target="{{ (int) $entry->target_qty }}"
                data-machine="{{ (int) $entry->machine_id }}"
                data-downtime="{{ e($entry->downtime_type ?? '') }}"
                data-downtime-min="{{ (int) $entry->downtime_minutes }}"
                data-half-day="{{ $entry->is_half_day ? '1' : '0' }}"
                data-remark="{{ e($entry->remark ?? '') }}">
                <td>{{ $i + 1 }}</td>
                <td class="text-nowrap">{{ $entry->date->format('d M Y') }}</td>
                <td>{{ ucfirst($entry->shift) }}</td>
                <td>{{ $entry->machine ? $entry->machine->machine_name : '—' }}</td>
                <td>{{ $entry->company->company_name ?? '—' }}</td>
                <td>{{ $entry->part->part_number ?? '—' }}</td>
                <td>
                  {{ \App\Models\CncProduction::operationTypeLabels()[$entry->operation_type] ?? $entry->operation_type }}
                </td>
                <td class="text-center font-weight-bold">{{ $entry->production_qty }}</td>
                <td class="text-center text-muted">{{ $entry->target_qty }}</td>
                <td class="text-center">
                  @if($entry->incentive_qty > 0)
                    <span class="badge badge-warning">+{{ $entry->incentive_qty }}</span>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td class="text-right font-weight-bold text-info">
                  ₹ {{ number_format($entry->amount, 2) }}
                </td>
                <td>
                  @if($entry->downtime_type)
                    <span class="badge badge-danger">{{ ucfirst(str_replace('_',' ',$entry->downtime_type)) }}</span>
                    @if($entry->downtime_minutes)
                      <small class="d-block text-muted">{{ $entry->downtime_minutes }} min</small>
                    @endif
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td>
                  @if($entry->is_sunday) <span class="badge badge-warning mr-1">Sun</span> @endif
                  @if($entry->is_half_day) <span class="badge badge-secondary">½ Day</span> @endif
                  @if(!$entry->target_met) <span class="badge badge-danger ml-1" title="Target not met">↓</span> @endif
                </td>
                @if(!$locked)
                <td class="text-center">
                  <button class="btn btn-xs btn-warning edit-entry-btn" title="Edit">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="btn btn-xs btn-danger delete-entry-btn mt-1" title="Delete">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
                @endif
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr class="tfoot-total">
                <td colspan="{{ $locked ? 7 : 7 }}" class="text-right pr-3">Totals:</td>
                <td class="text-center">{{ $totalQty }}</td>
                <td></td>
                <td class="text-center text-warning font-weight-bold">{{ $totalIncentive }}</td>
                <td class="text-right text-info">₹ {{ number_format($totalAmount, 2) }}</td>
                <td colspan="{{ $locked ? 2 : 3 }}"></td>
              </tr>
            </tfoot>
          </table>
        </div>
        @endif
      </div>
    </div>

  </div>
</section>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h4 class="modal-title"><i class="fas fa-edit mr-2"></i>Edit CNC Entry</h4>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          @csrf
          <input type="hidden" id="edit_id">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Date <span class="text-danger">*</span></label>
                <input type="date" id="edit_date" class="form-control" max="{{ now()->toDateString() }}" required>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Shift <span class="text-danger">*</span></label>
                <select id="edit_shift" class="form-control select2-modal" required>
                  <option value="day">Day</option>
                  <option value="night">Night</option>
                  <option value="A">A</option>
                  <option value="B">B</option>
                  <option value="general">General</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Vendor <span class="text-danger">*</span></label>
                <select id="edit_company" class="form-control select2-modal" required>
                  <option value="">-- Vendor --</option>
                  @foreach($companies as $c)
                    <option value="{{ $c->id }}">{{ $c->company_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Machine</label>
                <select id="edit_machine" class="form-control select2-modal">
                  <option value="">-- Machine --</option>
                  @foreach($machines as $m)
                    <option value="{{ $m->id }}">{{ $m->machine_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Part <span class="text-danger">*</span></label>
                <select id="edit_part" class="form-control select2-modal" required>
                  <option value="">-- Select Vendor First --</option>
                  @foreach($entries->pluck('part')->unique('id')->filter() as $p)
                    <option value="{{ $p->id }}">{{ $p->part_number }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Operation Type <span class="text-danger">*</span></label>
                <select id="edit_operation_type" class="form-control select2-modal" required>
                  @foreach(\App\Models\CncProduction::operationTypeLabels() as $val => $label)
                    <option value="{{ $val }}">{{ $label }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Produced Qty <span class="text-danger">*</span></label>
                <input type="number" id="edit_qty" class="form-control" min="0" required>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Target Qty</label>
                <input type="number" id="edit_target" class="form-control" min="0">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Downtime</label>
                <select id="edit_downtime" class="form-control">
                  <option value="">None</option>
                  <option value="machine_breakdown">Machine Breakdown</option>
                  <option value="power_cut">Power Cut</option>
                  <option value="other">Other</option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Downtime Min</label>
                <input type="number" id="edit_downtime_min" class="form-control" min="0" placeholder="0">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Half Day</label>
                <div class="mt-2">
                  <input type="checkbox" id="edit_half_day"> <label for="edit_half_day" class="mb-0">Yes</label>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label>Remark</label>
                <input type="text" id="edit_remark" class="form-control" maxlength="500">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="saveEditBtn" class="btn btn-warning">
          <i class="fas fa-save mr-1"></i> Update Entry
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
$(function () {

  var csrfToken = '{{ csrf_token() }}';
  var partsUrl  = '{{ route("cnc-productions.parts-by-company") }}';

  var Toast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false,
    timer: 3500, timerProgressBar: true,
  });

  function esc(str) { return $('<div>').text(str).html(); }

  // DataTable
  @if(!$entries->isEmpty())
  $('#entriesTable').DataTable({
    responsive: true,
    order: [[1, 'asc'], [0, 'asc']],
    pageLength: 50,
    columnDefs: [
      { orderable: false, targets: {{ $locked ? 12 : 13 }} },
    ]
  });
  @endif

  // Select2 in modal
  $('.select2-modal').select2({ theme: 'bootstrap4', dropdownParent: $('#editModal'), width: '100%' });

  // ── Open Edit Modal ──────────────────────────────────────────────
  $(document).on('click', '.edit-entry-btn', function () {
    var $tr = $(this).closest('tr');
    $('#edit_id').val($tr.data('id'));
    $('#edit_date').val($tr.data('date'));
    $('#edit_shift').val($tr.data('shift')).trigger('change');
    $('#edit_company').val($tr.data('company')).trigger('change');
    $('#edit_machine').val($tr.data('machine')).trigger('change');
    $('#edit_operation_type').val($tr.data('operation-type')).trigger('change');
    $('#edit_qty').val($tr.data('qty'));
    $('#edit_target').val($tr.data('target'));
    $('#edit_downtime').val($tr.data('downtime')).trigger('change');
    $('#edit_downtime_min').val($tr.data('downtime-min'));
    $('#edit_half_day').prop('checked', $tr.data('half-day') == '1');
    $('#edit_remark').val($tr.data('remark'));

    // Load parts for this company
    var cid = $tr.data('company');
    var pid = $tr.data('part');
    if (cid) {
      $.getJSON(partsUrl, { company_id: cid }, function (parts) {
        var opts = '<option value="">-- Part --</option>';
        $.each(parts, function (i, p) {
          opts += '<option value="' + p.id + '" ' + (p.id == pid ? 'selected' : '') + '>'
            + esc(p.part_number) + '</option>';
        });
        $('#edit_part').html(opts).trigger('change');
      });
    }

    $('#editModal').modal('show');
  });

  // Company change in edit modal → reload parts
  $('#edit_company').on('change', function () {
    var cid = $(this).val();
    if (!cid) return;
    $.getJSON(partsUrl, { company_id: cid }, function (parts) {
      var opts = '<option value="">-- Part --</option>';
      $.each(parts, function (i, p) {
        opts += '<option value="' + p.id + '">' + esc(p.part_number) + '</option>';
      });
      $('#edit_part').html(opts).trigger('change');
    });
  });

  // ── Save Edit ────────────────────────────────────────────────────
  $('#saveEditBtn').on('click', function () {
    var btn = $(this);
    btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

    var id = $('#edit_id').val();

    $.ajax({
      url: '/register/cnc-productions/' + id,
      method: 'POST',
      data: {
        _method:          'PUT',
        _token:           csrfToken,
        date:             $('#edit_date').val(),
        shift:            $('#edit_shift').val(),
        company_id:       $('#edit_company').val(),
        part_id:          $('#edit_part').val(),
        machine_id:       $('#edit_machine').val(),
        operation_type:   $('#edit_operation_type').val(),
        production_qty:   $('#edit_qty').val(),
        target_qty:       $('#edit_target').val(),
        downtime_type:    $('#edit_downtime').val(),
        downtime_minutes: $('#edit_downtime_min').val(),
        is_half_day:      $('#edit_half_day').is(':checked') ? 1 : 0,
        remark:           $('#edit_remark').val(),
      },
      success: function (res) {
        btn.html('<i class="fas fa-save mr-1"></i> Update Entry').prop('disabled', false);
        if (res.success) {
          Toast.fire({ icon: 'success', title: 'Entry updated.' });
          $('#editModal').modal('hide');
          setTimeout(() => location.reload(), 1200);
        }
      },
      error: function (xhr) {
        btn.html('<i class="fas fa-save mr-1"></i> Update Entry').prop('disabled', false);
        var msg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Update failed.';
        Swal.fire({ icon: 'error', title: 'Error', text: msg });
      }
    });
  });

  // ── Delete Entry ─────────────────────────────────────────────────
  $(document).on('click', '.delete-entry-btn', function () {
    var id = $(this).closest('tr').data('id');
    Swal.fire({
      title: 'Delete this entry?', icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete'
    }).then(function (result) {
      if (!result.isConfirmed) return;
      $.ajax({
        url: '/register/cnc-productions/' + id,
        method: 'POST',
        data: { _method: 'DELETE', _token: csrfToken },
        success: function () {
          Toast.fire({ icon: 'success', title: 'Entry deleted.' });
          setTimeout(() => location.reload(), 1200);
        },
        error: function (xhr) {
          var msg = xhr.responseJSON?.error || 'Delete failed.';
          Swal.fire({ icon: 'error', title: 'Error', text: msg });
        }
      });
    });
  });

});
</script>
@endpush
