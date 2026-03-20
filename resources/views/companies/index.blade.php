@extends('layouts.app')

@section('title', config('app.name') . ' | Companies')

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
          <h1 class="m-0">Companies</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Companies</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Company List</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-module-popup">
              <i class="fas fa-plus"></i> Add Company
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="companies-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Company Name</th>
                <th>Plant Name</th>
                <th>Contact Person</th>
                <th>Phone</th>
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
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Company</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="addCompanyForm">
              @csrf
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="company_name">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" id="company_name" class="form-control"
                      placeholder="Enter Company Name" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="plant_name">Plant Name</label>
                    <input type="text" name="plant_name" id="plant_name" class="form-control"
                      placeholder="Enter Plant Name">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="contact_person">Contact Person</label>
                    <input type="text" name="contact_person" id="contact_person" class="form-control"
                      placeholder="Enter Contact Person">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="contact_phone">Contact Phone</label>
                    <input type="text" name="contact_phone" id="contact_phone" class="form-control"
                      placeholder="Enter Phone Number">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" class="form-control" placeholder="Enter Address"></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="remark">Remark</label>
                    <textarea name="remark" id="remark" class="form-control" placeholder="Enter Remark"></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="is_active">Status</label>
                    <div class="custom-control custom-switch mt-2">
                      <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1"
                        checked>
                      <label class="custom-control-label" for="is_active">Active</label>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="addCompanyForm" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    {{-- End Add popup Modal --}}

    {{-- Edit popup Modal --}}
    <div class="modal fade" id="edit-module-popup" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Company</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form id="editCompanyForm">
              @csrf
              @method('PUT')
              <input type="hidden" name="id" id="edit_company_id">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edit_company_name">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" id="edit_company_name" class="form-control"
                      placeholder="Enter Company Name" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edit_plant_name">Plant Name</label>
                    <input type="text" name="plant_name" id="edit_plant_name" class="form-control"
                      placeholder="Enter Plant Name">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edit_contact_person">Contact Person</label>
                    <input type="text" name="contact_person" id="edit_contact_person" class="form-control"
                      placeholder="Enter Contact Person">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edit_contact_phone">Contact Phone</label>
                    <input type="text" name="contact_phone" id="edit_contact_phone" class="form-control"
                      placeholder="Enter Phone Number">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edit_address">Address</label>
                    <textarea name="address" id="edit_address" class="form-control"
                      placeholder="Enter Address"></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="edit_remark">Remark</label>
                    <textarea name="remark" id="edit_remark" class="form-control" placeholder="Enter Remark"></textarea>
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
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="editCompanyForm" id="editSaveBtn" class="btn btn-primary" disabled>Save
              changes</button>
          </div>
        </div>
      </div>
    </div>
    {{-- End popup Modal --}}

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
      $('#companies-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [],
        ajax: {
          url: '{{ route('companies.index') }}',
          data: function (d) {
            d.status = $('#status-filter').val();
          }
        },
        columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
          { data: 'company_name', name: 'company_name' },
          { data: 'plant_name', name: 'plant_name' },
          { data: 'contact_person', name: 'contact_person' },
          { data: 'contact_phone', name: 'contact_phone' },
          { data: 'is_active', name: 'is_active' },
          { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        initComplete: function () {
          var filterHtml = '<span class="d-inline-block ml-3"><label>Status: <select id="status-filter" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="1">Active</option><option value="0">Inactive</option></select></label></span>';
          $('#companies-table_length').css('display', 'inline-block');
          $('#companies-table_length').after(filterHtml);

          $('#status-filter').on('change', function () {
            $('#companies-table').DataTable().ajax.reload();
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

    // Add Company Validation
    $('#addCompanyForm').validate({
      rules: {
        company_name: { required: true, maxlength: 255 }
      },
      messages: {
        company_name: { required: "Please enter a company name." }
      },
      submitHandler: function (form, e) {
        e.preventDefault();
        let submitBtn = $(form).closest('.modal-content').find('button[type="submit"]');
        let originalText = submitBtn.html();

        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
          url: '{{ route('companies.store') }}',
          type: 'POST',
          data: $(form).serialize(),
          success: function (response) {
            submitBtn.html(originalText).prop('disabled', false);

            if (response.success) {
              Toast.fire({
                icon: 'success',
                title: response.message
              });
              $('#add-module-popup').modal('hide');
              form.reset();
              $('#companies-table').DataTable().ajax.reload();
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
    $(document).on('click', '.edit-btn', function () {
      let id = $(this).data('id');

      $('#editCompanyForm')[0].reset();
      $('#editSaveBtn').prop('disabled', true);
      $('#editCompanyForm').data('initial-state', '');

      $.ajax({
        url: '/master/companies/' + id + '/edit',
        type: 'GET',
        success: function (response) {
          if (response.success) {
            let data = response.data;
            $('#edit_company_id').val(data.id);
            $('#edit_company_name').val(data.company_name);
            $('#edit_plant_name').val(data.plant_name);
            $('#edit_contact_person').val(data.contact_person);
            $('#edit_contact_phone').val(data.contact_phone);
            $('#edit_address').val(data.address);
            $('#edit_remark').val(data.remark);
            $('#edit_is_active').prop('checked', data.is_active ? true : false);

            $('#editCompanyForm').data('initial-state', $('#editCompanyForm').serialize());
          }
        },
        error: function (err) {
          Swal.fire('Error', 'Failed to fetch data', 'error');
        }
      });
    });

    // Detect form changes to enable Save button
    $('#editCompanyForm').on('change input', function () {
      let currentState = $(this).serialize();
      let initialState = $(this).data('initial-state');
      if (currentState !== initialState && initialState !== '') {
        $('#editSaveBtn').prop('disabled', false);
      } else {
        $('#editSaveBtn').prop('disabled', true);
      }
    });

    // Edit Form AJAX Submit Validation
    $('#editCompanyForm').validate({
      rules: {
        company_name: { required: true, maxlength: 255 }
      },
      messages: {
        company_name: { required: "Please enter a company name." }
      },
      submitHandler: function (form, e) {
        e.preventDefault();

        let submitBtn = $('#editSaveBtn');
        let originalText = submitBtn.html();
        let id = $('#edit_company_id').val();

        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
          url: '/master/companies/' + id,
          type: 'POST', // Form specifies @method('PUT') inside
          data: $(form).serialize(),
          success: function (response) {
            submitBtn.html(originalText).prop('disabled', true);

            if (response.success) {
              Toast.fire({
                icon: 'success',
                title: response.message
              });
              $('#edit-module-popup').modal('hide');
              $(form).data('initial-state', $(form).serialize());
              $('#companies-table').DataTable().ajax.reload();
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
            url: '/master/companies/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (response) {
              if (response.success) {
                Toast.fire({
                  icon: 'success',
                  title: response.message
                });
                $('#companies-table').DataTable().ajax.reload();
              }
            },
            error: function (xhr) {
              Swal.fire('Error!', 'Something went wrong.', 'error');
            }
          });
        }
      });
    });
  </script>
@endpush