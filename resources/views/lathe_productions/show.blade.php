@extends('layouts.app')

@section('title', config('app.name') . ' | ' . $employee->name . ' – Lathe Entries')

@push('styles')
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <style>
    .filter-card .form-group { margin-bottom: 0; }
    .locked-badge { font-size: 12px; }
    .tfoot-total td { font-weight: 700; background: #f1f3f5; }
  </style>
@endpush

@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">
          <i class="fas fa-industry mr-2"></i>
          {{ $employee->name }}
          <small class="text-muted" style="font-size:14px;">{{ $employee->emp_code }}</small>
          <span class="badge badge-primary ml-2" style="font-size:11px;">{{ ucfirst($employee->employee_type) }}</span>
        </h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('lathe-productions.index') }}">Lathe Register</a></li>
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
        <form method="GET" action="{{ route('lathe-productions.show', $employee->id) }}" id="filterForm">
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
                      {{ date('F', mktime(0,0,0,$m,1)) }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label class="mb-1"><small>Specific Date</small></label>
                <input type="date" name="date" class="form-control form-control-sm"
                  value="{{ $date ?? '' }}">
              </div>
            </div>
            <div class="col-md-4 d-flex align-items-end pb-1">
              <button type="submit" class="btn btn-sm btn-primary mr-2">
                <i class="fas fa-search mr-1"></i> Apply
              </button>
              <a href="{{ route('lathe-productions.show', $employee->id) }}" class="btn btn-sm btn-outline-secondary mr-2">
                <i class="fas fa-times mr-1"></i> Clear
              </a>
              @if(!$locked)
                <a href="{{ route('lathe-productions.create') }}" class="btn btn-sm btn-success ml-auto">
                  <i class="fas fa-plus mr-1"></i> New Entry
                </a>
              @endif
            </div>
            <div class="col-md-2 d-flex align-items-end justify-content-end pb-1">
              @if($payroll)
                <span class="badge badge-{{ $payroll->status === 'paid' ? 'success' : ($payroll->status === 'approved' ? 'info' : 'warning') }} locked-badge p-2">
                  Payroll: {{ ucfirst($payroll->status) }}
                </span>
              @endif
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
            <span class="info-box-text">Total Pieces</span>
            <span class="info-box-number">{{ number_format($totalQty) }} pcs</span>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="info-box bg-warning">
          <span class="info-box-icon"><i class="fas fa-list-ol"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Entries</span>
            <span class="info-box-number">{{ $entries->count() }}</span>
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
    <div class="card card-primary card-outline">
      <div class="card-header d-flex align-items-center">
        <h3 class="card-title mb-0">
          <i class="fas fa-table mr-1"></i>
          Lathe Entries — {{ date('F', mktime(0,0,0,$month,1)) }} {{ $year }}
          @if($date) <small class="text-muted">(Filtered: {{ \Carbon\Carbon::parse($date)->format('d M Y') }})</small> @endif
        </h3>
        <span class="badge badge-primary ml-2 p-2">{{ $entries->count() }} record(s)</span>
      </div>
      <div class="card-body p-0">
        @if($entries->isEmpty())
          <div class="text-center py-5 text-muted">
            <i class="fas fa-inbox fa-2x mb-2"></i>
            <p>No entries found for this period.</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover mb-0" id="entriesTable">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th>Shift</th>
                  <th>Vendor</th>
                  <th>Part No.</th>
                  <th>Operation</th>
                  <th>Machine</th>
                  <th class="text-right">Qty</th>
                  <th class="text-right">Rate (₹)</th>
                  <th class="text-right">Amount (₹)</th>
                  <th>Downtime</th>
                  <th class="text-center">Half Day</th>
                  <th>Remarks</th>
                  @if(!$locked)<th class="text-center" style="width:70px">Action</th>@endif
                </tr>
              </thead>
              <tbody>
                @foreach($entries as $i => $entry)
                <tr id="row-{{ $entry->id }}"
                  data-id="{{ (int) $entry->id }}"
                  data-date="{{ $entry->date->format('Y-m-d') }}"
                  data-shift="{{ e($entry->shift) }}"
                  data-company="{{ (int) $entry->company_id }}"
                  data-part="{{ (int) $entry->part_id }}"
                  data-operation="{{ (int) $entry->operation_id }}"
                  data-machine="{{ (int) $entry->machine_id }}"
                  data-qty="{{ (int) $entry->qty }}"
                  data-downtime-type="{{ e($entry->downtime_type ?? '') }}"
                  data-downtime-minutes="{{ $entry->downtime_minutes ?? '' }}"
                  data-half-day="{{ $entry->is_half_day ? '1' : '0' }}"
                  data-remarks="{{ e($entry->remarks ?? '') }}">
                  <td>{{ $i + 1 }}</td>
                  <td class="text-nowrap">{{ $entry->date->format('d M Y') }}</td>
                  <td>{{ ucfirst($entry->shift) }}</td>
                  <td>{{ $entry->company?->company_name ?? '—' }}</td>
                  <td>{{ $entry->part?->part_number ?? '—' }}</td>
                  <td>{{ $entry->operation?->operation_name ?? '—' }}</td>
                  <td>{{ $entry->machine?->machine_name ?? '—' }}</td>
                  <td class="text-right font-weight-bold">{{ $entry->qty }}</td>
                  <td class="text-right">{{ number_format($entry->rate, 2) }}</td>
                  <td class="text-right font-weight-bold text-primary">₹ {{ number_format($entry->amount, 2) }}</td>
                  <td class="text-nowrap">
                    @if($entry->downtime_type)
                      <span class="badge badge-warning">{{ str_replace('_', ' ', ucfirst($entry->downtime_type)) }}</span>
                      @if($entry->downtime_minutes) <small class="text-muted">{{ $entry->downtime_minutes }}m</small> @endif
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($entry->is_half_day)
                      <span class="badge badge-info">Half</span>
                    @else
                      <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td class="text-muted">{{ $entry->remarks ?? '—' }}</td>
                  @if(!$locked)
                  <td class="text-center">
                    <button class="btn btn-xs btn-warning edit-btn" title="Edit">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-xs btn-danger delete-btn mt-1" data-id="{{ (int) $entry->id }}" title="Delete">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                  @endif
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr class="tfoot-total">
                  <td colspan="7" class="text-right pr-3">Totals:</td>
                  <td class="text-right">{{ $totalQty }}</td>
                  <td></td>
                  <td class="text-right text-primary">₹ {{ number_format($totalAmount, 2) }}</td>
                  <td colspan="{{ $locked ? 3 : 4 }}"></td>
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
@if(!$locked)
<div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title"><i class="fas fa-edit mr-1"></i> Edit Entry</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit_id">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Date <span class="text-danger">*</span></label>
              <input type="date" id="edit_date" class="form-control" max="{{ date('Y-m-d') }}" required>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Shift <span class="text-danger">*</span></label>
              <select id="edit_shift" class="form-control">
                <option value="day">Day</option>
                <option value="night">Night</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="general">General</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Machine</label>
              <select id="edit_machine" class="form-control select2-modal">
                <option value="">-- None --</option>
                @foreach($machines as $m)
                  <option value="{{ $m->id }}">{{ $m->machine_name }} ({{ $m->machine_number }})</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Vendor <span class="text-danger">*</span></label>
              <select id="edit_company" class="form-control select2-modal">
                <option value="">-- Select --</option>
                @foreach($companies as $c)
                  <option value="{{ $c->id }}">{{ $c->company_name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Part <span class="text-danger">*</span></label>
              <select id="edit_part" class="form-control select2-modal">
                <option value="">-- Select --</option>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Operation <span class="text-danger">*</span></label>
              <select id="edit_operation" class="form-control select2-modal">
                <option value="">-- Select --</option>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Qty <span class="text-danger">*</span></label>
              <input type="number" id="edit_qty" class="form-control" min="1">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Rate (₹)</label>
              <input type="text" id="edit_rate" class="form-control bg-light" readonly>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Downtime</label>
              <select id="edit_downtime_type" class="form-control">
                <option value="">None</option>
                <option value="machine_breakdown">Machine Breakdown</option>
                <option value="power_cut">Power Cut</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>
          <div class="col-md-3" id="edit_downtime_minutes_wrap" style="display:none">
            <div class="form-group">
              <label>Downtime (min)</label>
              <input type="number" id="edit_downtime_minutes" class="form-control" min="0" placeholder="Minutes">
            </div>
          </div>
          <div class="col-md-3 d-flex align-items-center pt-3">
            <div class="form-check">
              <input type="checkbox" id="edit_is_half_day" class="form-check-input" value="1">
              <label class="form-check-label" for="edit_is_half_day">Half Day</label>
            </div>
          </div>
          <div class="col-md-{{ isset($edit_downtime_minutes_wrap) ? '6' : '6' }}">
            <div class="form-group">
              <label>Remarks</label>
              <input type="text" id="edit_remarks" class="form-control" maxlength="255">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-warning" id="saveEditBtn">
          <i class="fas fa-save mr-1"></i> Save Changes
        </button>
      </div>
    </div>
  </div>
</div>
@endif

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

    var Toast = Swal.mixin({
      toast: true, position: 'top-end', showConfirmButton: false, timer: 3500, timerProgressBar: true,
    });

    @if(!$entries->isEmpty())
    $('#entriesTable').DataTable({
      responsive: true,
      order: [[1, 'asc']],
      pageLength: 50,
      @if(!$locked)
      columnDefs: [{ orderable: false, targets: -1 }],
      @endif
    });
    @endif

    $('.select2-modal').select2({ theme: 'bootstrap4', dropdownParent: $('#editModal'), width: '100%' });

    @if(!$locked)
    var partsUrl      = '{{ route("lathe-productions.parts-by-company") }}';
    var operationsUrl = '{{ route("lathe-productions.operations-by-company") }}';
    var rateUrl       = '{{ route("lathe-productions.operation-rate") }}';
    var csrfToken     = '{{ csrf_token() }}';
    var empId         = {{ $employee->id }};

    $('#edit_company').on('change', function () {
      var cId = $(this).val();
      $('#edit_part').html('<option value="">-- Loading --</option>');
      $('#edit_operation').html('<option value="">-- Select --</option>');
      $('#edit_rate').val('');
      if (!cId) return;
      $.getJSON(partsUrl, { company_id: cId }, function (parts) {
        var opts = '<option value="">-- Select --</option>';
        $.each(parts, function (_, p) { opts += '<option value="' + p.id + '">' + p.part_number + (p.part_name ? ' – ' + p.part_name : '') + '</option>'; });
        $('#edit_part').html(opts);
      });
      $.getJSON(operationsUrl, { company_id: cId }, function (ops) {
        var opts = '<option value="">-- Select --</option>';
        $.each(ops, function (_, o) { opts += '<option value="' + o.id + '">' + o.operation_name + '</option>'; });
        $('#edit_operation').html(opts);
      });
    });

    function fetchRate() {
      var opId = $('#edit_operation').val();
      var d    = $('#edit_date').val();
      if (!opId || !d) return;
      $.getJSON(rateUrl, { operation_id: opId, employee_id: empId, date: d }, function (res) {
        $('#edit_rate').val(res.rate ? parseFloat(res.rate).toFixed(2) : '0.00');
      });
    }
    $('#edit_operation, #edit_date').on('change', fetchRate);

    $('#edit_downtime_type').on('change', function () {
      $('#edit_downtime_minutes_wrap').toggle(!!$(this).val());
      if (!$(this).val()) $('#edit_downtime_minutes').val('');
    });

    $(document).on('click', '.edit-btn', function () {
      var $tr = $(this).closest('tr');
      var cId = $tr.data('company');

      $('#edit_id').val($tr.data('id'));
      $('#edit_date').val($tr.data('date'));
      $('#edit_shift').val($tr.data('shift'));
      $('#edit_machine').val($tr.data('machine')).trigger('change');
      $('#edit_qty').val($tr.data('qty'));
      $('#edit_remarks').val($tr.data('remarks'));
      var dt = $tr.data('downtime-type') || '';
      $('#edit_downtime_type').val(dt);
      $('#edit_downtime_minutes_wrap').toggle(!!dt);
      $('#edit_downtime_minutes').val($tr.data('downtime-minutes') || '');
      $('#edit_is_half_day').prop('checked', $tr.data('half-day') == '1');

      $('#edit_company').val(cId).trigger('change.select2');
      if (cId) {
        $.when(
          $.getJSON(partsUrl, { company_id: cId }),
          $.getJSON(operationsUrl, { company_id: cId })
        ).done(function (partsRes, opsRes) {
          var pOpts = '<option value="">-- Select --</option>';
          $.each(partsRes[0], function (_, p) { pOpts += '<option value="' + p.id + '">' + p.part_number + (p.part_name ? ' – ' + p.part_name : '') + '</option>'; });
          $('#edit_part').html(pOpts).val($tr.data('part')).trigger('change.select2');

          var oOpts = '<option value="">-- Select --</option>';
          $.each(opsRes[0], function (_, o) { oOpts += '<option value="' + o.id + '">' + o.operation_name + '</option>'; });
          $('#edit_operation').html(oOpts).val($tr.data('operation')).trigger('change.select2');
          fetchRate();
        });
      }
      $('#editModal').modal('show');
    });

    $('#saveEditBtn').on('click', function () {
      var btn = $(this);
      btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
      var id = $('#edit_id').val();
      $.ajax({
        url: '/register/lathe-productions/' + id,
        method: 'POST',
        data: {
          _method:      'PUT',
          _token:       csrfToken,
          date:         $('#edit_date').val(),
          shift:        $('#edit_shift').val(),
          company_id:   $('#edit_company').val(),
          part_id:      $('#edit_part').val(),
          operation_id: $('#edit_operation').val(),
          machine_id:   $('#edit_machine').val(),
          qty:              $('#edit_qty').val(),
          remarks:          $('#edit_remarks').val(),
          downtime_type:    $('#edit_downtime_type').val(),
          downtime_minutes: $('#edit_downtime_minutes').val(),
          is_half_day:      $('#edit_is_half_day').is(':checked') ? 1 : 0,
        },
        success: function () {
          btn.html('<i class="fas fa-save mr-1"></i> Save Changes').prop('disabled', false);
          $('#editModal').modal('hide');
          Toast.fire({ icon: 'success', title: 'Entry updated successfully.' });
          setTimeout(() => location.reload(), 1200);
        },
        error: function (xhr) {
          btn.html('<i class="fas fa-save mr-1"></i> Save Changes').prop('disabled', false);
          var msg = xhr.responseJSON?.error ?? xhr.responseJSON?.message ?? 'Update failed.';
          Swal.fire({ icon: 'error', title: 'Error', text: msg });
        }
      });
    });

    $(document).on('click', '.delete-btn', function () {
      var id = $(this).data('id');
      Swal.fire({
        title: 'Delete this entry?', text: 'This action cannot be undone.',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#d33', confirmButtonText: 'Yes, delete',
      }).then(function (result) {
        if (!result.isConfirmed) return;
        $.ajax({
          url: '/register/lathe-productions/' + id,
          method: 'POST',
          data: { _method: 'DELETE', _token: csrfToken },
          success: function () {
            Toast.fire({ icon: 'success', title: 'Entry deleted.' });
            setTimeout(() => location.reload(), 1200);
          },
          error: function (xhr) {
            Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.error ?? 'Delete failed.' });
          }
        });
      });
    });
    @endif

  });
  </script>
@endpush
