@extends('layouts.app')

@section('title', config('app.name') . ' | Salaries')

@push('styles')
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Salaries</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Salaries</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Employee Salary Overview</h3>
        </div>
        <div class="card-body">
          <table id="salaries-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Emp Code</th>
                <th>Name</th>
                <th>Type</th>
                <th>Per Day (₹)</th>
                <th>Per Month (₹)</th>
                <th>Effect From</th>
                <th>Remark</th>
                <th>Status</th>
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
      $.ajax({
        url: '{{ route("salaries.data") }}',
        type: 'GET',
        success: function (res) {
          let rows = res.data.map(function (emp, i) {
            let statusText = emp.status ? emp.status.charAt(0).toUpperCase() + emp.status.slice(1) : '';

            let nameLink = '<a href="/payroll/employees/' + emp.id + '">' + emp.name + '</a>';
            let codeLink = '<a href="/payroll/employees/' + emp.id + '">' + emp.emp_code + '</a>';

            return [
              i + 1,
              codeLink,
              nameLink,
              emp.type,
              emp.per_day ? '₹ ' + emp.per_day : '<span class="text-muted">—</span>',
              emp.per_month ? '₹ ' + emp.per_month : '<span class="text-muted">—</span>',
              emp.effect_from || '<span class="text-muted">—</span>',
              emp.remark || '<span class="text-muted">—</span>',
              statusText,
            ];
          });

          $('#salaries-table').DataTable({
            data: rows,
            responsive: true,
            order: [[1, 'asc']],
            columnDefs: [
              { orderable: false, targets: [0] },
            ],
            initComplete: function () {
              // Type filter
              var typeHtml = '<span class="d-inline-block ml-3"><label>Type: <select id="type-filter" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="Lathe">Lathe</option><option value="Cnc">CNC</option><option value="Both">Both</option></select></label></span>';
              // Salary filter
              var salaryHtml = '<span class="d-inline-block ml-3"><label>Salary: <select id="salary-filter" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="assigned">Assigned</option><option value="not_assigned">Not Assigned</option></select></label></span>';

              $('#salaries-table_length').css('display', 'inline-block');
              $('#salaries-table_length').after(typeHtml + salaryHtml);

              var table = $('#salaries-table').DataTable();

              $('#type-filter').on('change', function () {
                table.column(3).search($(this).val()).draw();
              });

              $('#salary-filter').on('change', function () {
                if ($(this).val() === 'not_assigned') {
                  table.column(4).search('—').draw();
                } else {
                  table.column(4).search('').draw();
                }
              });
            }
          });
        }
      });
    });
  </script>
@endpush
