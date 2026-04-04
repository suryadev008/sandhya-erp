@extends('layouts.app')

@section('title', config('app.name') . ' | Operations')

@push('styles')
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Operations</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Operations</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Operation List</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-module-popup">
              <i class="fas fa-plus"></i> Add Operation
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="operations-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Operation Name</th>
                <th>Vendor</th>
                <th>Current Price (₹)</th>
                <th>Applicable For</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <!-- Data populated by DataTables via AJAX -->
            </tbody>
          </table>
        </div>
      </div>

    </div>

    {{-- Add Popup Modal --}}
    <div class="modal fade" id="add-module-popup" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Operation</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="addOperationForm">
              @csrf
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="company_id">Vendor <span class="text-danger">*</span></label>
                    <select name="company_id" id="company_id" class="form-control" required>
                      <option value="">Select Vendor</option>
                      @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="operation_name">Operation Name <span class="text-danger">*</span></label>
                    <input type="text" name="operation_name" id="operation_name" class="form-control" placeholder="Enter Operation Name" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="applicable_for">Applicable For <span class="text-danger">*</span></label>
                    <select name="applicable_for" id="applicable_for" class="form-control" required>
                      <option value="">Select Type</option>
                      <option value="lathe">Lathe</option>
                      <option value="cnc">CNC</option>
                      <option value="both">Both</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="price">Initial Price (₹) <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="price" id="price" class="form-control" placeholder="Enter Initial Price" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="applicable_from">Price Applicable From <span class="text-danger">*</span></label>
                    <input type="date" name="applicable_from" id="applicable_from" class="form-control" value="{{ date('Y-m-d') }}" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="remark">Remark</label>
                    <input type="text" name="remark" id="remark" class="form-control" placeholder="Enter Remark">
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
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="addOperationForm" class="btn btn-primary">Save changes</button>
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
            <h4 class="modal-title">Edit Operation</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="editOperationForm">
              @csrf
              @method('PUT')
              <input type="hidden" name="id" id="edit_operation_id">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edit_company_id">Vendor <span class="text-danger">*</span></label>
                    <select name="company_id" id="edit_company_id" class="form-control" required>
                      <option value="">Select Vendor</option>
                      @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edit_operation_name">Operation Name <span class="text-danger">*</span></label>
                    <input type="text" name="operation_name" id="edit_operation_name" class="form-control" placeholder="Enter Operation Name" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edit_applicable_for">Applicable For <span class="text-danger">*</span></label>
                    <select name="applicable_for" id="edit_applicable_for" class="form-control" required>
                      <option value="">Select Type</option>
                      <option value="lathe">Lathe</option>
                      <option value="cnc">CNC</option>
                      <option value="both">Both</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edit_remark">Remark</label>
                    <input type="text" name="edit_remark" id="edit_remark" class="form-control" placeholder="Enter Remark">
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
                <div class="col-12">
                  <div class="alert alert-info mb-0 py-2">
                    <i class="fas fa-info-circle"></i>
                    To change the price, use the <strong>View</strong> button to open the operation detail page and add a new price entry from there.
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="editOperationForm" id="editSaveBtn" class="btn btn-primary" disabled>Save changes</button>
          </div>
        </div>
      </div>
    </div>
    {{-- End Edit Popup Modal --}}

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

  <script>
    $(function () {
      $('#operations-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [],
        ajax: {
          url: '{{ route('operations.index') }}',
          data: function (d) {
            d.status         = $('#status-filter').val();
            d.applicable_for = $('#applicable-filter').val();
            d.company_id     = $('#company-filter').val();
          }
        },
        columns: [
          { data: 'DT_RowIndex',    name: 'DT_RowIndex',    orderable: false, searchable: false },
          { data: 'operation_name', name: 'operation_name' },
          { data: 'company_name',   name: 'company.company_name' },
          { data: 'current_price',  name: 'current_price',  orderable: false, searchable: false },
          { data: 'applicable_for', name: 'applicable_for' },
          { data: 'is_active',      name: 'is_active' },
          { data: 'action',         name: 'action',         orderable: false, searchable: false },
        ],
        initComplete: function () {
          var companiesHtml = '<option value="">All</option>';
          @foreach($companies as $company)
            companiesHtml += '<option value="{{ $company->id }}">{{ addslashes($company->company_name) }}</option>';
          @endforeach

          var filterHtml =
            '<span class="d-inline-block ml-3"><label>Vendor:&nbsp;<select id="company-filter" class="custom-select custom-select-sm form-control form-control-sm">' + companiesHtml + '</select></label></span>' +
            '<span class="d-inline-block ml-3"><label>Status:&nbsp;<select id="status-filter" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="1">Active</option><option value="0">Inactive</option></select></label></span>' +
            '<span class="d-inline-block ml-3"><label>Application:&nbsp;<select id="applicable-filter" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="lathe">Lathe</option><option value="cnc">CNC</option><option value="both">Both</option></select></label></span>' +
            '<span class="d-inline-block ml-2"><button id="clear-filters" class="btn btn-sm btn-outline-secondary" title="Clear Filters"><i class="fas fa-times"></i> Clear</button></span>';

          $('#operations-table_length').css('display', 'inline-block');
          $('#operations-table_length').after(filterHtml);

          $('#company-filter, #status-filter, #applicable-filter').on('change', function () {
            $('#operations-table').DataTable().ajax.reload();
          });

          $('#clear-filters').on('click', function () {
            $('#company-filter').val('');
            $('#status-filter').val('');
            $('#applicable-filter').val('');
            $('#operations-table').DataTable().search('').ajax.reload();
          });
        }
      });
    });

    // jQuery Validation Defaults
    $.validator.setDefaults({
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
      },
      unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
      }
    });

    // SweetAlert2 Toast configuration
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 4000,
      timerProgressBar: true,
      background: '#f4f6f9',
      iconColor: '#28a745',
      customClass: {
        title: 'text-success font-weight-bold ml-2'
      },
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
      }
    });

    // Add Operation Validation
    $('#addOperationForm').validate({
      rules: {
        company_id:     { required: true },
        operation_name: { required: true, maxlength: 255 },
        applicable_for: { required: true },
        price:          { required: true, number: true, min: 0 },
        applicable_from:{ required: true }
      },
      messages: {
        company_id:     { required: "Please select a vendor." },
        operation_name: { required: "Please enter a name." },
        applicable_for: { required: "Please select an applicable type." },
        price:          { required: "Please enter a price.", number: "Please enter a valid number." },
        applicable_from:{ required: "Please select a date." }
      },
      submitHandler: function (form, e) {
        e.preventDefault();
        let submitBtn = $(form).closest('.modal-content').find('button[type="submit"]');
        let originalText = submitBtn.html();

        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
          url: '{{ route('operations.store') }}',
          type: 'POST',
          data: $(form).serialize(),
          success: function (response) {
            submitBtn.html(originalText).prop('disabled', false);

            if (response.success) {
              Toast.fire({ icon: 'success', title: response.message });
              $('#add-module-popup').modal('hide');
              form.reset();
              $('#applicable_from').val('{{ date('Y-m-d') }}');
              $('#operations-table').DataTable().ajax.reload();
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
              icon: 'error',
              title: 'Action Failed',
              html: '<div class="text-left text-danger">' + errorMessage + '</div>',
              confirmButtonText: '<i class="fas fa-times-circle"></i> Close',
              confirmButtonColor: '#dc3545',
              buttonsStyling: true,
              backdrop: `rgba(0,0,123,0.4)`
            });
          }
        });
      }
    });

    // Populate Edit Form
    $(document).on('click', '.edit-btn', function() {
      let id = $(this).data('id');

      $('#editOperationForm')[0].reset();
      $('#editSaveBtn').prop('disabled', true);
      $('#editOperationForm').data('initial-state', '');

      $.ajax({
        url: '/master/operations/' + id + '/edit',
        type: 'GET',
        success: function(response) {
          if(response.success) {
            let data = response.data;
            $('#edit_operation_id').val(data.id);
            $('#edit_company_id').val(data.company_id);
            $('#edit_operation_name').val(data.operation_name);
            $('#edit_applicable_for').val(data.applicable_for);
            $('#edit_remark').val(data.remark);
            $('#edit_is_active').prop('checked', data.is_active ? true : false);

            $('#editOperationForm').data('initial-state', $('#editOperationForm').serialize());
          }
        },
        error: function(err) {
          Swal.fire('Error', 'Failed to fetch data', 'error');
        }
      });
    });

    // Detect form changes to enable Save button
    $('#editOperationForm').on('change input', function() {
      let currentState = $(this).serialize();
      let initialState = $(this).data('initial-state');
      if (currentState !== initialState && initialState !== '') {
        $('#editSaveBtn').prop('disabled', false);
      } else {
        $('#editSaveBtn').prop('disabled', true);
      }
    });

    // Edit Form AJAX Submit Validation
    $('#editOperationForm').validate({
      rules: {
        company_id:     { required: true },
        operation_name: { required: true, maxlength: 255 },
        applicable_for: { required: true }
      },
      messages: {
        company_id:     { required: "Please select a vendor." },
        operation_name: { required: "Please enter a name." },
        applicable_for: { required: "Please select an applicable type." }
      },
      submitHandler: function (form, e) {
        e.preventDefault();

        let submitBtn = $('#editSaveBtn');
        let originalText = submitBtn.html();
        let id = $('#edit_operation_id').val();

        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
          url: '/master/operations/' + id,
          type: 'POST',
          data: $(form).serialize(),
          success: function (response) {
            submitBtn.html(originalText).prop('disabled', true);

            if (response.success) {
              Toast.fire({ icon: 'success', title: response.message });
              $('#edit-module-popup').modal('hide');
              $(form).data('initial-state', $(form).serialize());
              $('#operations-table').DataTable().ajax.reload();
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
              icon: 'error',
              title: 'Action Failed',
              html: '<div class="text-left text-danger">' + errorMessage + '</div>',
              confirmButtonText: '<i class="fas fa-times-circle"></i> Close',
              confirmButtonColor: '#dc3545',
              buttonsStyling: true,
              backdrop: `rgba(0,0,123,0.4)`
            });
          }
        });
      }
    });

    // Delete
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
            url: '/master/operations/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (response) {
              if(response.success) {
                Toast.fire({ icon: 'success', title: response.message });
                $('#operations-table').DataTable().ajax.reload();
              }
            },
            error: function (xhr) {
              let msg = 'Something went wrong.';
              if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
              Swal.fire('Error!', msg, 'error');
            }
          });
        }
      });
    });
  </script>
@endpush
