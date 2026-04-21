@extends('layouts.app')

@section('title', config('app.name') . ' | Parts')

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
          <h1 class="m-0">Parts</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Parts</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Part List</h3>
          <div class="card-tools">
            @can('create parts')
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-module-popup">
              <i class="fas fa-plus"></i> Add Part
            </button>
            @endcan
          </div>
        </div>
        <div class="card-body">
          <table id="parts-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Vendor</th>
                <th>Part Number</th>
                <th>Part Name</th>
                <th>Description</th>
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
              <h4 class="modal-title">Add Part</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">  
              <form id="addPartsForm">
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
                      <label for="part_number">Part Number <span class="text-danger">*</span></label>
                      <input type="text" name="part_number" id="part_number" class="form-control" placeholder="Enter Part Number" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="part_name">Part Name</label>
                      <input type="text" name="part_name" id="part_name" class="form-control" placeholder="Enter Part Name">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="description">Description</label>
                      <textarea name="description" id="description" class="form-control" placeholder="Enter Description"></textarea>
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
              <button type="submit" form="addPartsForm" class="btn btn-primary">Save changes</button>
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
              <h4 class="modal-title">Edit Part</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">  
              <form id="editPartsForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_part_id">
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
                      <label for="edit_part_number">Part Number <span class="text-danger">*</span></label>
                      <input type="text" name="part_number" id="edit_part_number" class="form-control" placeholder="Enter Part Number" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="edit_part_name">Part Name</label>
                      <input type="text" name="part_name" id="edit_part_name" class="form-control" placeholder="Enter Part Name">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="edit_description">Description</label>
                      <textarea name="description" id="edit_description" class="form-control" placeholder="Enter Description"></textarea>
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
              <button type="submit" form="editPartsForm" id="editSaveBtn" class="btn btn-primary" disabled>Save changes</button>
            </div>
          </div>
        </div>
      </div>
      {{-- End popup Modal --}}

  </section>
@endsection

@push('scripts')
  <!-- Select2 -->
  <script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
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
      // Initialize Select2 for Add modal
      $('#company_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Search Vendor...',
        allowClear: true,
        dropdownParent: $('#add-module-popup')
      });

      // Initialize Select2 for Edit modal
      $('#edit_company_id').select2({
        theme: 'bootstrap4',
        placeholder: 'Search Vendor...',
        allowClear: true,
        dropdownParent: $('#edit-module-popup')
      });

      // Reset Select2 when Add modal closes
      $('#add-module-popup').on('hidden.bs.modal', function () {
        $('#company_id').val('').trigger('change');
      });

      $('#parts-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [],
        ajax: {
          url: '{{ route('parts.index') }}',
          data: function (d) {
            d.status     = $('#status-filter').val();
            d.company_id = $('#company-filter').val();
          }
        },
        columns: [
          { data: 'DT_RowIndex',  name: 'DT_RowIndex', orderable: false, searchable: false },
          { data: 'company_name', name: 'company.company_name' },
          { data: 'part_number',  name: 'part_number' },
          { data: 'part_name',    name: 'part_name' },
          { data: 'description',  name: 'description' },
          { data: 'is_active',    name: 'is_active' },
          { data: 'action',       name: 'action', orderable: false, searchable: false },
        ],
        initComplete: function () {
          var companyOptions = '<option value="">All</option>';
          @foreach($companies as $company)
            companyOptions += '<option value="{{ $company->id }}">{{ addslashes($company->company_name) }}</option>';
          @endforeach

          var filterHtml =
            '<span class="d-inline-block ml-3"><label>Status:&nbsp;<select id="status-filter" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="1">Active</option><option value="0">Inactive</option></select></label></span>' +
            '<span class="d-inline-block ml-3"><label>Vendor:&nbsp;<select id="company-filter" class="custom-select custom-select-sm form-control form-control-sm" style="max-width:200px">' + companyOptions + '</select></label></span>' +
            '<span class="d-inline-block ml-2"><button id="clear-filters" class="btn btn-sm btn-outline-secondary" title="Clear Filters"><i class="fas fa-times"></i> Clear</button></span>';

          $('#parts-table_length').css('display', 'inline-block');
          $('#parts-table_length').after(filterHtml);

          $('#status-filter, #company-filter').on('change', function () {
            $('#parts-table').DataTable().ajax.reload();
          });

          $('#clear-filters').on('click', function () {
            $('#status-filter').val('');
            $('#company-filter').val('');
            $('#parts-table').DataTable().search('').ajax.reload();
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

    // Add Part Validation
    $('#addPartsForm').validate({
      rules: {
        company_id: { required: true },
        part_number: { required: true, maxlength: 100 }
      },
      messages: {
        company_id: { required: "Please select a vendor." },
        part_number: { required: "Please enter a part number." }
      },
      submitHandler: function (form, e) {
        e.preventDefault();
        let submitBtn = $(form).closest('.modal-content').find('button[type="submit"]');
        let originalText = submitBtn.html();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
          url: '{{ route('parts.store') }}',
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
              $('#parts-table').DataTable().ajax.reload();
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
      
      $('#editPartsForm')[0].reset();
      $('#editSaveBtn').prop('disabled', true);
      $('#editPartsForm').data('initial-state', '');

      $.ajax({
        url: window.APP_URL + '/master/parts/' + id + '/edit',
        type: 'GET',
        success: function(response) {
          if(response.success) {
            let data = response.data;
            $('#edit_part_id').val(data.id);
            $('#edit_company_id').val(data.company_id).trigger('change'); // trigger Select2 UI update
            $('#edit_part_number').val(data.part_number);
            $('#edit_part_name').val(data.part_name);
            $('#edit_description').val(data.description);
            $('#edit_is_active').prop('checked', data.is_active ? true : false);
            
            $('#editPartsForm').data('initial-state', $('#editPartsForm').serialize());
          }
        },
        error: function(err) {
          Swal.fire('Error', 'Failed to fetch data', 'error');
        }
      });
    });

    // Detect form changes to enable Save button
    $('#editPartsForm').on('change input', function() {
      let currentState = $(this).serialize();
      let initialState = $(this).data('initial-state');
      if (currentState !== initialState && initialState !== '') {
        $('#editSaveBtn').prop('disabled', false);
      } else {
        $('#editSaveBtn').prop('disabled', true);
      }
    });

    // Edit Form AJAX Submit Validation
    $('#editPartsForm').validate({
      rules: {
        company_id: { required: true },
        part_number: { required: true, maxlength: 100 }
      },
      messages: {
        company_id: { required: "Please select a vendor." },
        part_number: { required: "Please enter a part number." }
      },
      submitHandler: function (form, e) {
        e.preventDefault();
        
        let submitBtn = $('#editSaveBtn');
        let originalText = submitBtn.html();
        let id = $('#edit_part_id').val();
        
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
          url: window.APP_URL + '/master/parts/' + id,
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
              $('#parts-table').DataTable().ajax.reload();
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
            url: window.APP_URL + '/master/parts/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (response) {
              if(response.success) {
                Toast.fire({
                  icon: 'success',
                  title: response.message
                });
                $('#parts-table').DataTable().ajax.reload();
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
