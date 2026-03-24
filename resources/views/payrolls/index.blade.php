@extends('layouts.app')

@section('title', config('app.name') . ' | Payroll')

@push('styles')
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Payroll</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Payroll</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-users mr-1"></i> Employee Payroll Overview</h3>
        </div>
        <div class="card-body">
          <table id="payroll-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Emp Code</th>
                <th>Name</th>
                <th>Type</th>
                <th>Per Day (₹)</th>
                <th>Per Month (₹)</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

    </div>
  </section>
@endsection

@push('scripts')
  <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

  <script>
  $(function () {
    $('#payroll-table').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      order: [[1, 'asc']],
      ajax: {
        url: '{{ route('payrolls.index') }}',
        data: function (d) {
          d.status = $('#status-filter').val();
          d.type   = $('#type-filter').val();
        }
      },
      columns: [
        { data: 'DT_RowIndex',    name: 'DT_RowIndex',    orderable: false, searchable: false },
        { data: 'emp_code',       name: 'emp_code' },
        { data: 'name',           name: 'name' },
        { data: 'employee_type',  name: 'employee_type' },
        { data: 'per_day',        name: 'per_day',        orderable: false, searchable: false },
        { data: 'per_month',      name: 'per_month',      orderable: false, searchable: false },
        { data: 'status',         name: 'status' },
        { data: 'action',         name: 'action',         orderable: false, searchable: false },
      ],
      initComplete: function () {
        var filters =
          '<span class="d-inline-block ml-3"><label>Type:&nbsp;' +
          '<select id="type-filter" class="custom-select custom-select-sm form-control form-control-sm">' +
          '<option value="">All</option><option value="lathe">Lathe</option><option value="cnc">CNC</option><option value="both">Both</option>' +
          '</select></label></span>' +
          '<span class="d-inline-block ml-2"><label>Status:&nbsp;' +
          '<select id="status-filter" class="custom-select custom-select-sm form-control form-control-sm">' +
          '<option value="">All</option><option value="active">Active</option><option value="inactive">Inactive</option><option value="terminated">Terminated</option>' +
          '</select></label></span>' +
          '<span class="d-inline-block ml-2"><button id="clear-filters" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times"></i> Clear</button></span>';

        $('#payroll-table_length').css('display', 'inline-block').after(filters);

        $(document).on('change', '#type-filter, #status-filter', function () {
          $('#payroll-table').DataTable().ajax.reload();
        });
        $(document).on('click', '#clear-filters', function () {
          $('#type-filter, #status-filter').val('');
          $('#payroll-table').DataTable().search('').ajax.reload();
        });
      }
    });
  });
  </script>
@endpush
