@extends('layouts.app')

@section('title', config('app.name') . ' | Machine Types')

@push('styles')
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Machine Types</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Machine Types</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Machine Type List</h3>
          <div class="card-tools">
            @can('create machine-types')
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-module-popup">
              <i class="fas fa-plus"></i> Add Machine Type
            </button>
            @endcan
          </div>
        </div>
        <div class="card-body">
          <table id="machine-types-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Type Name</th>
                <th>Remark</th>
                <th>Status</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>

    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="add-module-popup" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Machine Type</h4>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <form id="addForm">
              @csrf
              <div class="form-group">
                <label>Type Name <span class="text-danger">*</span></label>
                <input type="text" name="type_name" id="type_name" class="form-control" placeholder="e.g. Lathe" required>
              </div>
              <div class="form-group">
                <label>Remark</label>
                <input type="text" name="remark" id="remark" class="form-control" placeholder="Optional remark">
              </div>
              <div class="form-group">
                <label>Status</label>
                <div class="custom-control custom-switch mt-1">
                  <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" checked>
                  <label class="custom-control-label" for="is_active">Active</label>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="addForm" class="btn btn-primary">Save</button>
          </div>
        </div>
      </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="edit-module-popup" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Machine Type</h4>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <form id="editForm">
              @csrf
              @method('PUT')
              <input type="hidden" name="id" id="edit_id">
              <div class="form-group">
                <label>Type Name <span class="text-danger">*</span></label>
                <input type="text" name="type_name" id="edit_type_name" class="form-control" required>
              </div>
              <div class="form-group">
                <label>Remark</label>
                <input type="text" name="remark" id="edit_remark" class="form-control">
              </div>
              <div class="form-group">
                <label>Status</label>
                <div class="custom-control custom-switch mt-1">
                  <input type="checkbox" name="is_active" class="custom-control-input" id="edit_is_active" value="1">
                  <label class="custom-control-label" for="edit_is_active">Active</label>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="editForm" id="editSaveBtn" class="btn btn-primary" disabled>Save changes</button>
          </div>
        </div>
      </div>
    </div>

  </section>

@endsection

@push('scripts')
  <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

  <script>
    $(function () {

      var Toast = Swal.mixin({
        toast: true, position: 'top-end', showConfirmButton: false,
        timer: 4000, timerProgressBar: true, background: '#f4f6f9', iconColor: '#28a745',
        customClass: { title: 'text-success font-weight-bold ml-2' },
        didOpen: (t) => { t.addEventListener('mouseenter', Swal.stopTimer); t.addEventListener('mouseleave', Swal.resumeTimer); }
      });

      $.validator.setDefaults({
        errorElement: 'span',
        errorPlacement: function (e, el) { e.addClass('invalid-feedback'); el.closest('.form-group').append(e); },
        highlight:   function (el) { $(el).addClass('is-invalid'); },
        unhighlight: function (el) { $(el).removeClass('is-invalid'); }
      });

      // ── DataTable ────────────────────────────────────────────────────
      $('#machine-types-table').DataTable({
        processing: true, serverSide: true, responsive: true, order: [],
        ajax: {
          url: '{{ route('machine-types.index') }}',
          data: function (d) { d.status = $('#status-filter').val(); }
        },
        columns: [
          { data: 'DT_RowIndex',  name: 'DT_RowIndex', orderable: false, searchable: false },
          { data: 'type_name',    name: 'type_name' },
          { data: 'remark',       name: 'remark' },
          { data: 'is_active',    name: 'is_active' },
          { data: 'action',       name: 'action', orderable: false, searchable: false },
        ],
        initComplete: function () {
          var filterHtml =
            '<span class="d-inline-block ml-3"><label>Status:&nbsp;<select id="status-filter" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="1">Active</option><option value="0">Inactive</option></select></label></span>' +
            '<span class="d-inline-block ml-2"><button id="clear-filters" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times"></i> Clear</button></span>';
          $('#machine-types-table_length').css('display', 'inline-block').after(filterHtml);
          $('#status-filter').on('change', function () { $('#machine-types-table').DataTable().ajax.reload(); });
          $('#clear-filters').on('click', function () {
            $('#status-filter').val('');
            $('#machine-types-table').DataTable().search('').ajax.reload();
          });
        }
      });

      // ── Add Form ─────────────────────────────────────────────────────
      $('#addForm').validate({
        rules: { type_name: { required: true, maxlength: 100 } },
        messages: { type_name: { required: 'Please enter a type name.' } },
        submitHandler: function (form, e) {
          e.preventDefault();
          var $btn = $(form).closest('.modal-content').find('button[type="submit"]');
          var orig = $btn.html();
          $btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
          $.ajax({
            url: '{{ route('machine-types.store') }}', type: 'POST', data: $(form).serialize(),
            success: function (r) {
              $btn.html(orig).prop('disabled', false);
              if (r.success) { Toast.fire({ icon: 'success', title: r.message }); $('#add-module-popup').modal('hide'); form.reset(); $('#machine-types-table').DataTable().ajax.reload(); }
            },
            error: function (xhr) {
              $btn.html(orig).prop('disabled', false);
              var msg = (xhr.responseJSON && xhr.responseJSON.errors) ? Object.values(xhr.responseJSON.errors).map(e => e.join('<br>')).join('<br>') : 'Something went wrong.';
              Swal.fire({ icon: 'error', title: 'Action Failed', html: msg, confirmButtonColor: '#dc3545' });
            }
          });
        }
      });

      // ── Populate Edit Form ────────────────────────────────────────────
      $(document).on('click', '.edit-btn', function () {
        var id = $(this).data('id');
        $('#editForm')[0].reset();
        $('#editSaveBtn').prop('disabled', true);
        $('#editForm').data('initial-state', '');
        $.ajax({
          url: window.APP_URL + '/master/machine-types/' + id + '/edit', type: 'GET',
          success: function (r) {
            if (r.success) {
              $('#edit_id').val(r.data.id);
              $('#edit_type_name').val(r.data.type_name);
              $('#edit_remark').val(r.data.remark);
              $('#edit_is_active').prop('checked', r.data.is_active);
              $('#editForm').data('initial-state', $('#editForm').serialize());
            }
          }
        });
      });

      $('#editForm').on('change input', function () {
        var cur = $(this).serialize(), init = $(this).data('initial-state');
        $('#editSaveBtn').prop('disabled', !(cur !== init && init !== ''));
      });

      // ── Edit Form Submit ───────────────────────────────────────────────
      $('#editForm').validate({
        rules: { type_name: { required: true, maxlength: 100 } },
        messages: { type_name: { required: 'Please enter a type name.' } },
        submitHandler: function (form, e) {
          e.preventDefault();
          var id = $('#edit_id').val();
          var $btn = $('#editSaveBtn');
          var orig = $btn.html();
          $btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
          $.ajax({
            url: window.APP_URL + '/master/machine-types/' + id, type: 'POST', data: $(form).serialize(),
            success: function (r) {
              $btn.html(orig).prop('disabled', true);
              if (r.success) { Toast.fire({ icon: 'success', title: r.message }); $('#edit-module-popup').modal('hide'); $(form).data('initial-state', $(form).serialize()); $('#machine-types-table').DataTable().ajax.reload(); }
            },
            error: function (xhr) {
              $btn.html(orig).prop('disabled', false);
              var msg = (xhr.responseJSON && xhr.responseJSON.errors) ? Object.values(xhr.responseJSON.errors).map(e => e.join('<br>')).join('<br>') : 'Something went wrong.';
              Swal.fire({ icon: 'error', title: 'Action Failed', html: msg, confirmButtonColor: '#dc3545' });
            }
          });
        }
      });

      // ── Delete ────────────────────────────────────────────────────────
      $(document).on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        Swal.fire({ title: 'Are you sure?', text: "You won't be able to revert this!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d', confirmButtonText: '<i class="fas fa-trash"></i> Yes, delete it!' })
        .then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: window.APP_URL + '/master/machine-types/' + id, type: 'DELETE', data: { _token: '{{ csrf_token() }}' },
              success: function (r) { if (r.success) { Toast.fire({ icon: 'success', title: r.message }); $('#machine-types-table').DataTable().ajax.reload(); } },
              error: function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Something went wrong.';
                Swal.fire('Error!', msg, 'error');
              }
            });
          }
        });
      });

    });
  </script>
@endpush
