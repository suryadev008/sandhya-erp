@extends('layouts.app')

@section('title', config('app.name') . ' | User Accounts')

@push('styles')
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">User Accounts</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Users</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-users mr-1"></i> All Users</h3>
          <div class="card-tools">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-user-modal">
              <i class="fas fa-user-plus"></i> Add User
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="users-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined</th>
                <th style="width:90px">Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

    </div>
  </section>

  {{-- ===== ADD USER MODAL ===== --}}
  <div class="modal fade" id="add-user-modal" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white"><i class="fas fa-user-plus mr-1"></i> Add New User</h5>
          <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="addUserForm">
            @csrf
            <div class="form-group">
              <label>Full Name <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
            </div>
            <div class="form-group">
              <label>Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control" placeholder="Enter email address" required>
            </div>
            <div class="form-group">
              <label>Role <span class="text-danger">*</span></label>
              <select name="role" class="form-control" required>
                <option value="" disabled selected>-- Select Role --</option>
                @foreach($roles as $role)
                  <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label>Password <span class="text-danger">*</span></label>
              <input type="password" name="password" class="form-control" placeholder="Set password" required>
            </div>
            <div class="form-group">
              <label>Confirm Password <span class="text-danger">*</span></label>
              <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" id="addUserBtn" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> Create User
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== EDIT USER MODAL ===== --}}
  <div class="modal fade" id="edit-user-modal" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title"><i class="fas fa-user-edit mr-1"></i> Edit User</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="editUserForm">
            @csrf
            <input type="hidden" id="edit_user_id">
            <div class="form-group">
              <label>Full Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="edit_name" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Email <span class="text-danger">*</span></label>
              <input type="email" name="email" id="edit_email" class="form-control" required>
            </div>
            <div class="form-group">
              <label>Role <span class="text-danger">*</span></label>
              <select name="role" id="edit_role" class="form-control" required>
                @foreach($roles as $role)
                  <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                @endforeach
              </select>
            </div>
            <hr>
            <p class="text-muted mb-2" style="font-size:12px">
              <i class="fas fa-info-circle mr-1"></i> Leave password blank to keep existing password.
            </p>
            <div class="form-group">
              <label>New Password</label>
              <input type="password" name="password" id="edit_password" class="form-control" placeholder="New password (optional)">
            </div>
            <div class="form-group mb-0">
              <label>Confirm New Password</label>
              <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" id="editUserBtn" class="btn btn-warning">
            <i class="fas fa-save mr-1"></i> Update User
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
    var table = $('#users-table').DataTable({
      processing: true,
      responsive: true,
      order: [[0, 'asc']],
      ajax: {
        url: '{{ route('users.data') }}',
        type: 'GET',
        dataSrc: 'data'
      },
      columns: [
        { data: 'DT_RowIndex',  name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'name',         name: 'name' },
        { data: 'email',        name: 'email' },
        { data: 'role',         name: 'role', orderable: false },
        { data: 'created_at',   name: 'created_at' },
        { data: 'action',       name: 'action', orderable: false, searchable: false },
      ],
      columnDefs: [{ targets: 5, width: '90px' }]
    });

    // ── Add User ───────────────────────────────────────────────────────
    $('#addUserBtn').on('click', function () {
      var $btn = $(this);
      var orig = $btn.html();
      $btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

      $.ajax({
        url: '{{ route('users.store') }}',
        type: 'POST',
        data: $('#addUserForm').serialize(),
        success: function (res) {
          $btn.html(orig).prop('disabled', false);
          if (res.success) {
            Toast.fire({ icon: 'success', title: res.message });
            $('#add-user-modal').modal('hide');
            $('#addUserForm')[0].reset();
            table.ajax.reload();
          }
        },
        error: function (xhr) {
          $btn.html(orig).prop('disabled', false);
          showError(parseErrors(xhr));
        }
      });
    });

    // Reset form on modal close
    $('#add-user-modal').on('hidden.bs.modal', function () {
      $('#addUserForm')[0].reset();
    });

    // ── Open Edit Modal ────────────────────────────────────────────────
    $(document).on('click', '.edit-btn', function () {
      var $btn = $(this);
      $('#editUserForm')[0].reset();
      $('#edit_user_id').val($btn.data('id'));
      $('#edit_name').val($btn.data('name'));
      $('#edit_email').val($btn.data('email'));
      $('#edit_role').val($btn.data('role'));
      $('#edit-user-modal').modal('show');
    });

    // ── Update User ────────────────────────────────────────────────────
    $('#editUserBtn').on('click', function () {
      var $btn = $(this);
      var orig = $btn.html();
      var id   = $('#edit_user_id').val();

      $btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

      $.ajax({
        url: window.APP_URL + '/master/users/' + id,
        type: 'POST',
        data: $('#editUserForm').serialize() + '&_method=PUT',
        success: function (res) {
          $btn.html(orig).prop('disabled', false);
          if (res.success) {
            Toast.fire({ icon: 'success', title: res.message });
            $('#edit-user-modal').modal('hide');
            table.ajax.reload();
          }
        },
        error: function (xhr) {
          $btn.html(orig).prop('disabled', false);
          showError(parseErrors(xhr));
        }
      });
    });

    // ── Delete User ────────────────────────────────────────────────────
    $(document).on('click', '.delete-btn', function () {
      var id = $(this).data('id');
      Swal.fire({
        title: 'Delete this user?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash"></i> Yes, delete!',
        cancelButtonText: 'Cancel'
      }).then(function (result) {
        if (!result.isConfirmed) return;
        $.ajax({
          url: window.APP_URL + '/master/users/' + id,
          type: 'DELETE',
          data: { _token: csrfToken },
          success: function (res) {
            if (res.success) {
              Toast.fire({ icon: 'success', title: res.message });
              table.ajax.reload();
            }
          },
          error: function (xhr) {
            showError(parseErrors(xhr));
          }
        });
      });
    });

  });
  </script>
@endpush
