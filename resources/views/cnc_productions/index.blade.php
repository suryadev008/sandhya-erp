@extends('layouts.app')

@section('title', config('app.name') . ' | CNC Production Register')

@push('styles')
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-cog mr-2"></i>CNC Production Register</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="breadcrumb-item active">CNC Register</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    <div class="card card-info card-outline">
      <div class="card-header d-flex align-items-center">
        <h3 class="card-title mb-0"><i class="fas fa-users mr-1"></i> CNC Employees</h3>
        <a href="{{ route('cnc-productions.create') }}" class="btn btn-sm btn-success ml-auto">
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
              <th>Payment Model</th>
              <th>Status</th>
              <th class="text-center">Register</th>
            </tr>
          </thead>
          <tbody>
            @foreach($employees as $i => $emp)
            <tr>
              <td>{{ $i + 1 }}</td>
              <td>
                <a href="{{ route('cnc-productions.show', $emp->id) }}" class="font-weight-bold">
                  {{ $emp->emp_code }}
                </a>
              </td>
              <td>
                <a href="{{ route('cnc-productions.show', $emp->id) }}">{{ $emp->name }}</a>
              </td>
              <td>
                <span class="badge badge-info">{{ ucfirst($emp->employee_type) }}</span>
              </td>
              <td>
                @if($emp->cnc_payment_type === 'per_piece')
                  <span class="badge badge-primary">Per Piece</span>
                @else
                  <span class="badge badge-secondary">Day Rate + Incentive</span>
                @endif
              </td>
              <td>
                @php $sc = ['active'=>'success','inactive'=>'secondary','terminated'=>'danger'][$emp->status] ?? 'secondary'; @endphp
                <span class="badge badge-{{ $sc }}">{{ ucfirst($emp->status) }}</span>
              </td>
              <td class="text-center">
                <a href="{{ route('cnc-productions.show', $emp->id) }}" class="btn btn-xs btn-outline-info">
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
        columnDefs: [{ orderable: false, targets: [0, 6] }]
      });
    });
  </script>
@endpush
