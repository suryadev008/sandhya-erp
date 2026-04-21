@extends('layouts.app')

@section('title', config('app.name') . ' | Lathe Production Register')

@push('styles')
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-industry mr-2"></i>Lathe Production Register</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">Lathe Register</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    <div class="card card-primary card-outline">
      <div class="card-header d-flex align-items-center">
        <h3 class="card-title mb-0"><i class="fas fa-users mr-1"></i> Lathe Employees</h3>
        <a href="{{ route('lathe-productions.create') }}" class="btn btn-sm btn-success ml-auto">
          <i class="fas fa-plus mr-1"></i> New Entry
        </a>
      </div>
      <div class="card-body">
        <table id="empTable" class="table table-bordered table-hover table-sm w-100">
          <thead class="thead-light">
            <tr>
              <th>#</th>
              <th>Emp Code</th>
              <th>Name</th>
              <th>Type</th>
              <th>Status</th>
              <th class="text-center">Register</th>
            </tr>
          </thead>
          <tbody>
            @foreach($employees as $i => $emp)
            @php
              $typeColor  = ['lathe' => 'primary', 'cnc' => 'info', 'both' => 'warning'][$emp->employee_type] ?? 'secondary';
              $statColor  = ['active' => 'success', 'inactive' => 'secondary', 'terminated' => 'danger'][$emp->status] ?? 'secondary';
            @endphp
            <tr>
              <td>{{ $i + 1 }}</td>
              <td>
                <a href="{{ route('lathe-productions.show', $emp->id) }}" class="font-weight-bold">
                  {{ $emp->emp_code }}
                </a>
              </td>
              <td>
                <a href="{{ route('lathe-productions.show', $emp->id) }}">{{ $emp->name }}</a>
              </td>
              <td>
                <span class="badge badge-{{ $typeColor }}">{{ ucfirst($emp->employee_type) }}</span>
              </td>
              <td>
                <span class="badge badge-{{ $statColor }}">{{ ucfirst($emp->status) }}</span>
              </td>
              <td class="text-center">
                <a href="{{ route('lathe-productions.show', $emp->id) }}" class="btn btn-xs btn-outline-primary">
                  <i class="fas fa-list mr-1"></i> View
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
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
      $('#empTable').DataTable({
        responsive: true,
        order: [[1, 'asc']],
        columnDefs: [{ orderable: false, targets: [0, 5] }]
      });
    });
  </script>
@endpush
