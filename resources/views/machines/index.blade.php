@extends('layouts.app')

@section('title', config('app.name') . ' | Machines')

@push('styles')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
            @can('create machines')
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-module-popup">
              <i class="fas fa-plus"></i> Add Machine
            </button>
            @endcan
          </div>
        </div>
        <div class="card-body">
          <table id="machines-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Machine Name</th>
                <th>Machine No.</th>
                <th>Type</th>
                <th>Working / Use Case</th>
                <th>Status</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>

    </div>

    {{-- Add Popup Modal --}}
    <div class="modal fade" id="add-module-popup" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Machine</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="addMachineForm">
              @csrf
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="machine_name">Machine Name <span class="text-danger">*</span></label>
                    <input type="text" name="machine_name" id="machine_name" class="form-control" placeholder="Enter Machine Name" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="machine_number">Machine Number <span class="text-danger">*</span></label>
                    <input type="text" name="machine_number" id="machine_number" class="form-control" placeholder="Enter Machine Number" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="machine_type_id">Machine Type <span class="text-danger">*</span></label>
                    <select name="machine_type_id" id="machine_type_id" class="form-control select2-type" required>
                      <option value="">Select Type</option>
                      @foreach($machineTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="is_active">Status</label>
                    <div class="custom-control custom-switch mt-2">
                      <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" checked>
                      <label class="custom-control-label" for="is_active">Active</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="working">Working / Use Case</label>
                    <textarea name="working" id="working" class="form-control" rows="3" placeholder="Describe how this machine is used, its purpose, or any operational notes..."></textarea>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="addMachineForm" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    {{-- End Add popup Modal --}}

    {{-- Edit Popup Modal --}}
    <div class="modal fade" id="edit-module-popup" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Machine</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="editMachineForm">
              @csrf
              @method('PUT')
              <input type="hidden" name="id" id="edit_machine_id">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edit_machine_name">Machine Name <span class="text-danger">*</span></label>
                    <input type="text" name="machine_name" id="edit_machine_name" class="form-control" placeholder="Enter Machine Name" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edit_machine_number">Machine Number <span class="text-danger">*</span></label>
                    <input type="text" name="machine_number" id="edit_machine_number" class="form-control" placeholder="Enter Machine Number" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edit_machine_type_id">Machine Type <span class="text-danger">*</span></label>
                    <select name="machine_type_id" id="edit_machine_type_id" class="form-control select2-type" required>
                      <option value="">Select Type</option>
                      @foreach($machineTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edit_is_active">Status</label>
                    <div class="custom-control custom-switch mt-2">
                      <input type="checkbox" name="is_active" class="custom-control-input" id="edit_is_active" value="1">
                      <label class="custom-control-label" for="edit_is_active">Active</label>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="edit_working">Working / Use Case</label>
                    <textarea name="working" id="edit_working" class="form-control" rows="3" placeholder="Describe how this machine is used, its purpose, or any operational notes..."></textarea>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="editMachineForm" id="editSaveBtn" class="btn btn-primary" disabled>Save changes</button>
          </div>
        </div>
      </div>
    </div>
    {{-- End Edit Popup Modal --}}

    {{-- View Popup Modal --}}
    <div class="modal fade" id="view-module-popup" data-keyboard="false">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-info">
            <h4 class="modal-title text-white"><i class="fas fa-info-circle mr-1"></i> Machine Details</h4>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" id="viewModalBody">
            <div class="text-center py-4" id="viewLoadingSpinner">
              <i class="fas fa-spinner fa-spin fa-2x text-info"></i>
              <p class="mt-2 text-muted">Loading...</p>
            </div>
            <div id="viewContent" style="display:none;">
              <table class="table table-bordered table-sm">
                <tbody>
                  <tr>
                    <th width="35%" class="bg-light">Machine Name</th>
                    <td id="view_machine_name"></td>
                  </tr>
                  <tr>
                    <th class="bg-light">Machine Number</th>
                    <td id="view_machine_number"></td>
                  </tr>
                  <tr>
                    <th class="bg-light">Machine Type</th>
                    <td id="view_machine_type"></td>
                  </tr>
                  <tr>
                    <th class="bg-light">Status</th>
                    <td id="view_status"></td>
                  </tr>
                  <tr>
                    <th class="bg-light">Working / Use Case</th>
                    <td id="view_working"></td>
                  </tr>
                  <tr>
                    <th class="bg-light">Description</th>
                    <td id="view_description"></td>
                  </tr>
                  <tr>
                    <th class="bg-light">Added On</th>
                    <td id="view_created_at"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    {{-- End View Popup Modal --}}

  </section>

@endsection

@push('scripts')
  <!-- jQuery Validation -->
  <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
  <!-- SweetAlert2 -->
  <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <!-- Select2 -->
  <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>

  <script>
    $(function () {
      // Init Select2 for Machine Type (Add & Edit modals)
      $('#machine_type_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Search Type...',
        dropdownParent: $('#add-module-popup')
      });

      $('#edit_machine_type_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Search Type...',
        dropdownParent: $('#edit-module-popup')
      });

      // Reset Select2 on Add modal close
      $('#add-module-popup').on('hidden.bs.modal', function () {
        $('#machine_type_id').val(null).trigger('change');
      });

      $('#machines-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [],
        ajax: {
          url: '{{ route('machines.index') }}',
          data: function (d) {
            d.status = $('#status-filter').val();
            d.type   = $('#type-filter').val();
          }
        },
        columns: [
          { data: 'DT_RowIndex',    name: 'DT_RowIndex', orderable: false, searchable: false },
          { data: 'machine_name',   name: 'machine_name' },
          { data: 'machine_number', name: 'machine_number' },
          { data: 'machine_type_id', name: 'machine_type_id', searchable: false },
          { data: 'working', name: 'working', orderable: false, createdCell: function(td) { $(td).css({'max-width':'280px','white-space':'pre-wrap','word-break':'break-word'}); } },
          { data: 'is_active',      name: 'is_active' },
          { data: 'action',         name: 'action', orderable: false, searchable: false },
        ],
        initComplete: function () {
          var filterHtml =
            '<span class="d-inline-block ml-3"><label>Status:&nbsp;<select id="status-filter" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="1">Active</option><option value="0">Inactive</option></select></label></span>' +
            '<span class="d-inline-block ml-3"><label>Type:&nbsp;<select id="type-filter" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option>@foreach($machineTypes as $type)<option value="{{ $type->id }}">{{ $type->type_name }}</option>@endforeach</select></label></span>' +
            '<span class="d-inline-block ml-2"><button id="clear-filters" class="btn btn-sm btn-outline-secondary" title="Clear Filters"><i class="fas fa-times"></i> Clear</button></span>';

          $('#machines-table_length').css('display', 'inline-block');
          $('#machines-table_length').after(filterHtml);

          $('#status-filter, #type-filter').on('change', function () {
            $('#machines-table').DataTable().ajax.reload();
          });

          $('#clear-filters').on('click', function () {
            $('#status-filter').val('');
            $('#type-filter').val('');
            $('#machines-table').DataTable().search('').ajax.reload();
          });
        }
      });
    });

    // ─── View Machine ────────────────────────────────────────────────────────────
    $(document).on('click', '.view-btn', function () {
      let id = $(this).data('id');
      $('#viewLoadingSpinner').show();
      $('#viewContent').hide();

      $.ajax({
        url: window.APP_URL + '/master/machines/' + id,
        type: 'GET',
        success: function (response) {
          if (response.success) {
            let d = response.data;
            $('#view_machine_name').text(d.machine_name || '—');
            $('#view_machine_number').text(d.machine_number || '—');
            $('#view_machine_type').text(d.machine_type ? d.machine_type.type_name : '—');
            $('#view_status').html(d.is_active
              ? '<span class="badge badge-success">Active</span>'
              : '<span class="badge badge-secondary">Inactive</span>');
            $('#view_working').text(d.working || '—');
            $('#view_description').text(d.description || '—');
            $('#view_created_at').text(d.created_at ? new Date(d.created_at).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }) : '—');
            $('#viewLoadingSpinner').hide();
            $('#viewContent').show();
          }
        },
        error: function () {
          $('#view-module-popup').modal('hide');
          Swal.fire('Error', 'Failed to fetch machine details.', 'error');
        }
      });
    });

    // Reset view modal on close
    $('#view-module-popup').on('hidden.bs.modal', function () {
      $('#viewLoadingSpinner').show();
      $('#viewContent').hide();
    });

    // ─── Delete Machine ──────────────────────────────────────────────────────────
    $(document).on('click', '.delete-btn', function () {
      let id = $(this).data('id');

      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash"></i> Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: window.APP_URL + '/master/machines/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (response) {
              if (response.success) {
                Toast.fire({ icon: 'success', title: response.message });
                $('#machines-table').DataTable().ajax.reload();
              }
            },
            error: function () {
              Swal.fire('Error!', 'Something went wrong while deleting.', 'error');
            }
          });
        }
      });
    });

    // ─── SweetAlert2 Toast ───────────────────────────────────────────────────────
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 4000,
      timerProgressBar: true,
      background: '#f4f6f9',
      iconColor: '#28a745',
      customClass: { title: 'text-success font-weight-bold ml-2' },
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
      }
    });

    // ─── jQuery Validation Defaults ──────────────────────────────────────────────
    $.validator.setDefaults({
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element) {
        $(element).removeClass('is-invalid');
      }
    });

    // ─── Add Machine ─────────────────────────────────────────────────────────────
    $('#addMachineForm').validate({
      rules: {
        machine_name:    { required: true, maxlength: 255 },
        machine_number:  { required: true, maxlength: 255 },
        machine_type_id: { required: true }
      },
      messages: {
        machine_name:    { required: "Please enter a machine name." },
        machine_number:  { required: "Please enter a machine number." },
        machine_type_id: { required: "Please select a machine type." }
      },
      submitHandler: function (form, e) {
        e.preventDefault();
        let submitBtn = $(form).closest('.modal-content').find('button[type="submit"]');
        let originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
          url: '{{ route('machines.store') }}',
          type: 'POST',
          data: $(form).serialize(),
          success: function (response) {
            submitBtn.html(originalText).prop('disabled', false);
            if (response.success) {
              Toast.fire({ icon: 'success', title: response.message });
              $('#add-module-popup').modal('hide');
              form.reset();
              $('#machines-table').DataTable().ajax.reload();
            }
          },
          error: function (xhr) {
            submitBtn.html(originalText).prop('disabled', false);
            let errorMessage = 'Something went wrong. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
              errorMessage = Object.values(xhr.responseJSON.errors).map(err => err.join('<br>')).join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            }
            Swal.fire({
              icon: 'error', title: 'Action Failed',
              html: '<div class="text-left text-danger">' + errorMessage + '</div>',
              confirmButtonText: '<i class="fas fa-times-circle"></i> Close',
              confirmButtonColor: '#dc3545', backdrop: 'rgba(0,0,123,0.4)'
            });
          }
        });
      }
    });

    // ─── Populate Edit Machine Form ───────────────────────────────────────────────
    $(document).on('click', '.edit-btn', function () {
      let id = $(this).data('id');
      $('#editMachineForm')[0].reset();
      $('#editSaveBtn').prop('disabled', true);
      $('#editMachineForm').data('initial-state', '');

      $.ajax({
        url: window.APP_URL + '/master/machines/' + id + '/edit',
        type: 'GET',
        success: function (response) {
          if (response.success) {
            let data = response.data;
            $('#edit_machine_id').val(data.id);
            $('#edit_machine_name').val(data.machine_name);
            $('#edit_machine_number').val(data.machine_number);
            $('#edit_machine_type_id').val(data.machine_type_id).trigger('change');
            $('#edit_is_active').prop('checked', data.is_active ? true : false);
            $('#edit_working').val(data.working || '');
            $('#editMachineForm').data('initial-state', $('#editMachineForm').serialize());
          }
        },
        error: function () {
          Swal.fire('Error', 'Failed to fetch machine data', 'error');
        }
      });
    });

    // Detect form changes to enable Save button
    $('#editMachineForm').on('change input', function () {
      let currentState = $(this).serialize();
      let initialState = $(this).data('initial-state');
      if (currentState !== initialState && initialState !== '') {
        $('#editSaveBtn').prop('disabled', false);
      } else {
        $('#editSaveBtn').prop('disabled', true);
      }
    });

    // ─── Edit Machine Submit ──────────────────────────────────────────────────────
    $('#editMachineForm').validate({
      rules: {
        machine_name:    { required: true, maxlength: 255 },
        machine_number:  { required: true, maxlength: 255 },
        machine_type_id: { required: true }
      },
      messages: {
        machine_name:    { required: "Please enter a machine name." },
        machine_number:  { required: "Please enter a machine number." },
        machine_type_id: { required: "Please select a machine type." }
      },
      submitHandler: function (form, e) {
        e.preventDefault();
        let submitBtn = $('#editSaveBtn');
        let originalText = submitBtn.html();
        let id = $('#edit_machine_id').val();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
          url: window.APP_URL + '/master/machines/' + id,
          type: 'POST',
          data: $(form).serialize(),
          success: function (response) {
            submitBtn.html(originalText).prop('disabled', true);
            if (response.success) {
              Toast.fire({ icon: 'success', title: response.message });
              $('#edit-module-popup').modal('hide');
              $(form).data('initial-state', $(form).serialize());
              $('#machines-table').DataTable().ajax.reload();
            }
          },
          error: function (xhr) {
            submitBtn.html(originalText).prop('disabled', false);
            let errorMessage = 'Something went wrong. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
              errorMessage = Object.values(xhr.responseJSON.errors).map(err => err.join('<br>')).join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
              errorMessage = xhr.responseJSON.message;
            }
            Swal.fire({
              icon: 'error', title: 'Action Failed',
              html: '<div class="text-left text-danger">' + errorMessage + '</div>',
              confirmButtonText: '<i class="fas fa-times-circle"></i> Close',
              confirmButtonColor: '#dc3545', backdrop: 'rgba(0,0,123,0.4)'
            });
          }
        });
      }
    });
  </script>
@endpush
