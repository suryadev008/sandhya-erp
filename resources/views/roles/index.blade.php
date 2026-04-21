@extends('layouts.app')

@section('title', config('app.name') . ' | Roles & Permissions')

@push('styles')
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <style>
    .perm-group-title { font-weight: 700; font-size: 11px; text-transform: uppercase; color: #6c757d; margin-bottom: 5px; }
    .perm-check-grid  { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 3px 10px; }
  </style>
@endpush

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Roles & Permissions</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Roles</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-user-shield mr-1"></i> All Roles</h3>
          <div class="card-tools">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-role-modal">
              <i class="fas fa-plus"></i> Add Role
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="roles-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th style="width:40px">#</th>
                <th>Role Name</th>
                <th>Permissions</th>
                <th style="width:100px">Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

    </div>
  </section>

  {{-- ===== ADD ROLE MODAL ===== --}}
  <div class="modal fade" id="add-role-modal" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white"><i class="fas fa-user-shield mr-1"></i> Add New Role</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="addRoleForm">
            @csrf
            <div class="form-group">
              <label>Role Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" placeholder="e.g. accountant" required>
            </div>
            <label class="font-weight-bold">Assign Permissions</label>
            @foreach($permissions as $module => $perms)
              <div class="mb-3 mt-2">
                <div class="perm-group-title">
                  <i class="fas fa-circle mr-1" style="font-size:7px"></i> {{ ucfirst($module) }}
                </div>
                <div class="perm-check-grid">
                  @foreach($perms as $perm)
                    <div class="form-check">
                      <input class="form-check-input add-perm" type="checkbox"
                        name="permissions[]" value="{{ $perm->name }}" id="add_{{ $perm->id }}">
                      <label class="form-check-label" for="add_{{ $perm->id }}">
                        {{ ucfirst(explode(' ', $perm->name)[0]) }}
                      </label>
                    </div>
                  @endforeach
                </div>
              </div>
            @endforeach
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" id="addRoleBtn" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> Save Role
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== EDIT ROLE MODAL ===== --}}
  <div class="modal fade" id="edit-role-modal" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title"><i class="fas fa-edit mr-1"></i> Edit Role</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="editRoleForm">
            @csrf
            <input type="hidden" id="edit_role_id">
            <div class="form-group">
              <label>Role Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="edit_role_name" class="form-control" required>
            </div>
            <label class="font-weight-bold">Assign Permissions</label>
            @foreach($permissions as $module => $perms)
              <div class="mb-3 mt-2">
                <div class="perm-group-title">
                  <i class="fas fa-circle mr-1" style="font-size:7px"></i> {{ ucfirst($module) }}
                </div>
                <div class="perm-check-grid">
                  @foreach($perms as $perm)
                    <div class="form-check">
                      <input class="form-check-input edit-perm" type="checkbox"
                        name="permissions[]" value="{{ $perm->name }}" id="edit_{{ $perm->id }}">
                      <label class="form-check-label" for="edit_{{ $perm->id }}">
                        {{ ucfirst(explode(' ', $perm->name)[0]) }}
                      </label>
                    </div>
                  @endforeach
                </div>
              </div>
            @endforeach
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" id="editRoleBtn" class="btn btn-warning">
            <i class="fas fa-save mr-1"></i> Update Role
          </button>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <script src="{{ asset('public/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

  <script>
  $(function () {

    var csrfToken = '{{ csrf_token() }}';

    // ── Toast ──────────────────────────────────────────────────────────
    var Toast = Swal.mixin({
      toast: true, position: 'top-end', showConfirmButton: false,
      timer: 4000, timerProgressBar: true,
      didOpen: function (t) {
        t.addEventListener('mouseenter', Swal.stopTimer);
        t.addEventListener('mouseleave', Swal.resumeTimer);
      }
    });

    function showError(msg) {
      Swal.fire({
        icon: 'error', title: 'Error',
        html: '<div class="text-left text-danger">' + msg + '</div>',
        confirmButtonColor: '#dc3545'
      });
    }

    function parseErrors(xhr) {
      if (xhr.responseJSON && xhr.responseJSON.errors)
        return Object.values(xhr.responseJSON.errors).map(function (e) { return e.join('<br>'); }).join('<br>');
      return (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Something went wrong.';
    }

    // ── DataTable ──────────────────────────────────────────────────────
    var table = $('#roles-table').DataTable({
      processing: true,
      responsive: true,
      order: [[0, 'asc']],
      ajax: { url: '{{ route('roles.data') }}', type: 'GET', dataSrc: 'data' },
      columns: [
        { data: 'DT_RowIndex',  orderable: false, searchable: false },
        { data: 'name' },
        { data: 'permissions',  orderable: false },
        { data: 'action',       orderable: false, searchable: false },
      ],
      columnDefs: [{ targets: 3, width: '100px' }]
    });

    // ── Add Role ───────────────────────────────────────────────────────
    $('#addRoleBtn').on('click', function () {
      var $btn = $(this), orig = $btn.html();
      $btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

      $.ajax({
        url: '{{ route('roles.store') }}',
        type: 'POST',
        data: $('#addRoleForm').serialize(),
        success: function (res) {
          $btn.html(orig).prop('disabled', false);
          if (res.success) {
            Toast.fire({ icon: 'success', title: res.message });
            $('#add-role-modal').modal('hide');
            $('#addRoleForm')[0].reset();
            table.ajax.reload();
          }
        },
        error: function (xhr) { $btn.html(orig).prop('disabled', false); showError(parseErrors(xhr)); }
      });
    });

    $('#add-role-modal').on('hidden.bs.modal', function () {
      $('#addRoleForm')[0].reset();
    });

    // ── Open Edit Modal ────────────────────────────────────────────────
    $(document).on('click', '.edit-btn', function () {
      var id    = $(this).data('id');
      var name  = $(this).data('name');
      var perms = $(this).data('permissions');

      // perms comes as JSON string from data attribute
      if (typeof perms === 'string') {
        try { perms = JSON.parse(perms); } catch (e) { perms = []; }
      }

      $('#edit_role_id').val(id);
      $('#edit_role_name').val(name);

      // Reset then check assigned
      $('.edit-perm').prop('checked', false);
      $.each(perms, function (i, p) {
        $('.edit-perm[value="' + p + '"]').prop('checked', true);
      });

      $('#edit-role-modal').modal('show');
    });

    // ── Update Role ────────────────────────────────────────────────────
    $('#editRoleBtn').on('click', function () {
      var $btn = $(this), orig = $btn.html();
      var id   = $('#edit_role_id').val();

      $btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);

      $.ajax({
        url: window.APP_URL + '/master/roles/' + id,
        type: 'POST',
        data: $('#editRoleForm').serialize() + '&_method=PUT',
        success: function (res) {
          $btn.html(orig).prop('disabled', false);
          if (res.success) {
            Toast.fire({ icon: 'success', title: res.message });
            $('#edit-role-modal').modal('hide');
            table.ajax.reload();
          }
        },
        error: function (xhr) { $btn.html(orig).prop('disabled', false); showError(parseErrors(xhr)); }
      });
    });

    // ── Delete Role ────────────────────────────────────────────────────
    $(document).on('click', '.delete-btn', function () {
      var id = $(this).data('id');
      Swal.fire({
        title: 'Delete this role?',
        text: 'Users with this role will lose their access.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash"></i> Yes, delete!'
      }).then(function (result) {
        if (!result.isConfirmed) return;
        $.ajax({
          url: window.APP_URL + '/master/roles/' + id,
          type: 'DELETE',
          data: { _token: csrfToken },
          success: function (res) {
            if (res.success) {
              Toast.fire({ icon: 'success', title: res.message });
              table.ajax.reload();
            }
          },
          error: function (xhr) { showError(parseErrors(xhr)); }
        });
      });
    });

  });
  </script>
@endpush
