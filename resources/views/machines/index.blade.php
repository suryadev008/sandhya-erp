@extends('layouts.app')

@section('title', config('app.name') . ' | Machines')

@push('styles')
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Machines</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active">Machines</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      {{-- Flash Message --}}
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
          {{ session('success') }}
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
      @endif

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Machine List</h3>
          <div class="card-tools">
            <a href="{{ route('machines.create') }}" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Add Machine
            </a>
          </div>
        </div>
        <div class="card-body">
          <table id="machines-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Machine Name</th>
                <th>Machine No.</th>
                <th>Type</th>
                <th>Status</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
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
      $('#machines-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('machines.index') }}',
        columns: [
          { data: 'id',             name: 'id' },
          { data: 'machine_name',   name: 'machine_name' },
          { data: 'machine_number', name: 'machine_number' },
          { data: 'machine_type',   name: 'machine_type' },
          { data: 'is_active',      name: 'is_active' },
          { data: 'action',         name: 'action', orderable: false, searchable: false },
        ]
      });
    });

    // Delete
    $(document).on('click', '.delete-btn', function () {
      if (confirm('Are you sure you want to delete this machine?')) {
        $.ajax({
          url: '/machines/' + $(this).data('id'),
          type: 'DELETE',
          data: { _token: '{{ csrf_token() }}' },
          success: function () {
            $('#machines-table').DataTable().ajax.reload();
          }
        });
      }
    });
  </script>
@endpush