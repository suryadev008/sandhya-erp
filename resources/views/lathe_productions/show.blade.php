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
                <label class="mb-1"><small>Date (optional)</small></label>
                <input type="date" name="date" class="form-control form-control-sm"
                  value="{{ $date ?? '' }}">
              </div>
            </div>
            <div class="col-md-2 d-flex align-items-end pb-1">
              <button type="submit" class="btn btn-sm btn-primary mr-1">
                <i class="fas fa-search mr-1"></i> Apply
              </button>
              <button type="button" class="btn btn-sm btn-outline-secondary" id="clearDateBtn" {{ !$date ? 'disabled' : '' }}>
                <i class="fas fa-times mr-1"></i> Clear Date
              </button>
            </div>
            <div class="col-md-4 d-flex align-items-end justify-content-end pb-1">
              @if($payroll)
                <span class="badge badge-{{ $payroll->status === 'paid' ? 'success' : ($payroll->status === 'approved' ? 'info' : 'warning') }} locked-badge mr-2 p-2">
                  Payroll: {{ ucfirst($payroll->status) }}
                </span>
              @endif
              @if(!$locked)
                <a href="{{ route('lathe-productions.create') }}" class="btn btn-sm btn-success">
                  <i class="fas fa-plus mr-1"></i> New Entry
                </a>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- Entries Table --}}
    <div class="card card-primary card-outline">
      <div class="card-header d-flex align-items-center">
        <h3 class="card-title mb-0">
          <i class="fas fa-list mr-1"></i>
          Entries — {{ date('F', mktime(0,0,0,$month,1)) }} {{ $year }}
          @if($date) <small class="text-muted">/ {{ \Carbon\Carbon::parse($date)->format('d M') }}</small> @endif
        </h3>
        @if($locked)
          <span class="badge badge-danger ml-2 p-2">Locked — Payroll {{ ucfirst($payroll->status) }}</span>
        @endif
        <span class="ml-auto text-muted small">{{ $entries->count() }} record(s)</span>
      </div>
      <div class="card-body p-0">
        @if($entries->isEmpty())
          <div class="p-4 text-center text-muted">
            <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
            No entries found for this period.
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover mb-0" id="entriesTable">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th>Shift</th>
                  <th>Company</th>
                  <th>Part No.</th>
                  <th>Operation</th>
                  <th>Machine</th>
                  <th class="text-right">Qty</th>
                  <th class="text-right">Rate (₹)</th>
                  <th class="text-right">Amount (₹)</th>
                  <th>Remarks</th>
                  @if(!$locked)<th class="text-center">Action</th>@endif
                </tr>
              </thead>
              <tbody>
                @foreach($entries as $i => $entry)
                <tr id="row-{{ $entry->id }}">
                  <td>{{ $i + 1 }}</td>
                  <td>{{ $entry->date->format('d M Y') }}</td>
                  <td style="text-transform:capitalize;">{{ $entry->shift }}</td>
                  <td>{{ $entry->company?->company_name ?? '—' }}</td>
                  <td>{{ $entry->part?->part_number ?? '—' }}</td>
                  <td>{{ $entry->operation?->operation_name ?? '—' }}</td>
                  <td>{{ $entry->machine?->machine_name ?? '—' }}</td>
                  <td class="text-right">{{ $entry->qty }}</td>
                  <td class="text-right">{{ number_format($entry->rate, 2) }}</td>
                  <td class="text-right">{{ number_format($entry->amount, 2) }}</td>
                  <td>{{ $entry->remarks ?? '—' }}</td>
                  @if(!$locked)
                  <td class="text-center">
                    <button class="btn btn-xs btn-warning edit-btn"
                      data-id="{{ $entry->id }}"
                      data-date="{{ $entry->date->format('Y-m-d') }}"
                      data-shift="{{ $entry->shift }}"
                      data-company="{{ $entry->company_id }}"
                      data-part="{{ $entry->part_id }}"
                      data-operation="{{ $entry->operation_id }}"
                      data-machine="{{ $entry->machine_id }}"
                      data-qty="{{ $entry->qty }}"
                      data-remarks="{{ $entry->remarks }}"
                      title="Edit">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-xs btn-danger delete-btn" data-id="{{ $entry->id }}" title="Delete">
                      <i class="fas fa-trash"></i>
                    </button>
                  </td>
                  @endif
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="px-3 py-2 border-top bg-light d-flex justify-content-end">
            <span class="mr-4"><strong>Total Qty:</strong> {{ $entries->sum('qty') }}</span>
            <span><strong>Total Amount:</strong> ₹ {{ number_format($entries->sum('amount'), 2) }}</span>
          </div>
        @endif
      </div>
    </div>

  </div>
</section>

{{-- Edit Modal --}}
@if(!$locked)
<div class="modal fade" id="editModal" tabindex="-1">
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
              <label>Company <span class="text-danger">*</span></label>
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
              <input type="text" id="edit_rate" class="form-control" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Remarks</label>
              <input type="text" id="edit_remarks" class="form-control" maxlength="255">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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

    // DataTable
    @if(!$entries->isEmpty())
    $('#entriesTable').DataTable({
      responsive: true,
      order: [[1, 'asc']],
      pageLength: 25,
      @if(!$locked)
      columnDefs: [{ orderable: false, targets: -1 }],
      @endif
    });
    @endif

    // Clear Date button
    $('#clearDateBtn').on('click', function () {
      $('input[name="date"]').val('');
      $('#filterForm').submit();
    });

    // Select2 in modal
    $('.select2-modal').select2({ theme: 'bootstrap4', dropdownParent: $('#editModal') });

    @if(!$locked)
    var _base         = window.location.origin;
    var partsUrl      = _base + '{{ parse_url(route("lathe-productions.parts-by-company"), PHP_URL_PATH) }}';
    var operationsUrl = _base + '{{ parse_url(route("lathe-productions.operations-by-company"), PHP_URL_PATH) }}';
    var rateUrl       = _base + '{{ parse_url(route("lathe-productions.operation-rate"), PHP_URL_PATH) }}';
    var updateBase    = _base + '{{ parse_url(route("lathe-productions.update", "__ID__"), PHP_URL_PATH) }}';
    var deleteBase    = _base + '{{ parse_url(route("lathe-productions.destroy", "__ID__"), PHP_URL_PATH) }}';
    var csrfToken     = '{{ csrf_token() }}';

    // Toast helper
    function toast(icon, msg) {
      Swal.fire({ toast: true, position: 'top-end', icon: icon, title: msg, showConfirmButton: false, timer: 3500 });
    }

    // Load parts when company changes in modal
    $('#edit_company').on('change', function () {
      var cId = $(this).val();
      $('#edit_part').html('<option value="">-- Loading --</option>');
      $('#edit_operation').html('<option value="">-- Select --</option>');
      $('#edit_rate').val('');
      if (!cId) return;
      $.getJSON(partsUrl, { company_id: cId }, function (parts) {
        var opts = '<option value="">-- Select --</option>';
        $.each(parts, function (_, p) { opts += '<option value="' + p.id + '">' + p.part_number + ' – ' + p.part_name + '</option>'; });
        $('#edit_part').html(opts);
      });
      $.getJSON(operationsUrl, { company_id: cId }, function (ops) {
        var opts = '<option value="">-- Select --</option>';
        $.each(ops, function (_, o) { opts += '<option value="' + o.id + '">' + o.operation_name + '</option>'; });
        $('#edit_operation').html(opts);
      });
    });

    // Load rate when operation/date changes
    function fetchRate() {
      var opId = $('#edit_operation').val();
      var d    = $('#edit_date').val();
      if (!opId || !d) return;
      $.getJSON(rateUrl, { operation_id: opId, date: d }, function (res) {
        $('#edit_rate').val(res.rate ? parseFloat(res.rate).toFixed(2) : '0.00');
      });
    }
    $('#edit_operation, #edit_date').on('change', fetchRate);

    // Open edit modal
    $(document).on('click', '.edit-btn', function () {
      var $btn = $(this);
      var id   = $btn.data('id');
      var cId  = $btn.data('company');
      var date = $btn.data('date');

      $('#edit_id').val(id);
      $('#edit_date').val(date);
      $('#edit_shift').val($btn.data('shift'));
      $('#edit_machine').val($btn.data('machine')).trigger('change');
      $('#edit_qty').val($btn.data('qty'));
      $('#edit_remarks').val($btn.data('remarks'));

      // Load parts & operations for the stored company, then set values
      $('#edit_company').val(cId).trigger('change.select2');
      if (cId) {
        $.when(
          $.getJSON(partsUrl, { company_id: cId }),
          $.getJSON(operationsUrl, { company_id: cId })
        ).done(function (partsRes, opsRes) {
          var parts = partsRes[0], ops = opsRes[0];
          var pOpts = '<option value="">-- Select --</option>';
          $.each(parts, function (_, p) { pOpts += '<option value="' + p.id + '">' + p.part_number + ' – ' + p.part_name + '</option>'; });
          $('#edit_part').html(pOpts).val($btn.data('part')).trigger('change.select2');

          var oOpts = '<option value="">-- Select --</option>';
          $.each(ops, function (_, o) { oOpts += '<option value="' + o.id + '">' + o.operation_name + '</option>'; });
          $('#edit_operation').html(oOpts).val($btn.data('operation')).trigger('change.select2');

          fetchRate();
        });
      }

      $('#editModal').modal('show');
    });

    // Save edit
    $('#saveEditBtn').on('click', function () {
      var id  = $('#edit_id').val();
      var url = updateBase.replace('__ID__', id);
      $.ajax({
        url: url,
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
          qty:          $('#edit_qty').val(),
          remarks:      $('#edit_remarks').val(),
        },
        success: function (res) {
          $('#editModal').modal('hide');
          toast('success', 'Entry updated successfully.');
          setTimeout(function () { location.reload(); }, 1200);
        },
        error: function (xhr) {
          var msg = xhr.responseJSON?.error ?? xhr.responseJSON?.message ?? 'Update failed.';
          toast('error', msg);
        }
      });
    });

    // Delete entry
    $(document).on('click', '.delete-btn', function () {
      var id  = $(this).data('id');
      var url = deleteBase.replace('__ID__', id);
      Swal.fire({
        title: 'Delete this entry?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete',
      }).then(function (result) {
        if (!result.isConfirmed) return;
        $.ajax({
          url: url,
          method: 'POST',
          data: { _method: 'DELETE', _token: csrfToken },
          success: function () {
            $('#row-' + id).remove();
            toast('success', 'Entry deleted.');
          },
          error: function (xhr) {
            var msg = xhr.responseJSON?.error ?? 'Delete failed.';
            toast('error', msg);
          }
        });
      });
    });
    @endif

  });
  </script>
@endpush
