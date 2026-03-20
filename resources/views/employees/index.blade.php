@extends('layouts.app')

@section('title', config('app.name') . ' | Employees')

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
          <h1 class="m-0">Employees</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Employees</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Employee List</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-module-popup">
              <i class="fas fa-plus"></i> Add Employee
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="employees-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Emp Code</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Type</th>
                <th>Joining Date</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Add Popup Modal --}}
    <div class="modal fade" id="add-module-popup" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Employee</h4>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <form id="addEmployeesForm" enctype="multipart/form-data">
              @csrf
              <div class="row">
                {{-- Personal Info --}}
                <div class="col-12"><h6 class="text-muted font-weight-bold mb-2 border-bottom pb-1">Personal Information</h6></div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Emp Code</label>
                    <input type="text" name="emp_code" id="emp_code" class="form-control bg-light" readonly>
                    <small class="text-muted">Auto-generated</small>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Full Name">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Aadhar No</label>
                    <input type="text" name="aadhar_no" id="aadhar_no" class="form-control" placeholder="12-digit Aadhar">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Aadhar Image <small class="text-muted">(jpg/png, max 2MB)</small></label>
                    <input type="file" name="aadhar_image" id="aadhar_image" class="form-control-file" accept="image/jpg,image/jpeg,image/png">
                    <div id="aadhar_image_preview" class="mt-2 d-none">
                      <img src="" alt="Preview" style="max-height:100px;border-radius:4px;">
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Mobile (Primary) <span class="text-danger">*</span></label>
                    <input type="text" name="mobile_primary" id="mobile_primary" class="form-control" placeholder="10-digit number">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Mobile (Secondary)</label>
                    <input type="text" name="mobile_secondary" id="mobile_secondary" class="form-control" placeholder="10-digit number">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>WhatsApp No</label>
                    <input type="text" name="whatsapp_no" id="whatsapp_no" class="form-control" placeholder="10-digit number">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Permanent Address</label>
                    <textarea name="permanent_address" id="permanent_address" class="form-control" rows="2" placeholder="Permanent Address"></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Present Address <span class="text-danger">*</span></label>
                    <textarea name="present_address" id="present_address" class="form-control" rows="2" placeholder="Present Address"></textarea>
                  </div>
                </div>

                {{-- Bank & Payment --}}
                <div class="col-12"><h6 class="text-muted font-weight-bold mb-2 border-bottom pb-1 mt-2">Bank & Payment Details</h6></div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Bank Account No</label>
                    <input type="text" name="bank_account_no" id="bank_account_no" class="form-control" placeholder="Account Number">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="e.g. SBI">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>IFSC Code</label>
                    <input type="text" name="ifsc_code" id="ifsc_code" class="form-control" placeholder="e.g. SBIN0001234">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>UPI Number</label>
                    <input type="text" name="upi_number" id="upi_number" class="form-control" placeholder="UPI ID or Number">
                  </div>
                </div>

                {{-- Job Info --}}
                <div class="col-12"><h6 class="text-muted font-weight-bold mb-2 border-bottom pb-1 mt-2">Job Details</h6></div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Employee Type <span class="text-danger">*</span></label>
                    <select name="employee_type" id="employee_type" class="form-control">
                      <option value="">Select Type</option>
                      <option value="lathe">Lathe</option>
                      <option value="cnc">CNC</option>
                      <option value="both">Both</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Experience (Years)</label>
                    <input type="number" step="0.1" min="0" name="experience_years" id="experience_years" class="form-control" placeholder="e.g. 2.5">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Joining Date</label>
                    <input type="date" name="joining_date" id="joining_date" class="form-control">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-control">
                      <option value="active" selected>Active</option>
                      <option value="inactive">Inactive</option>
                      <option value="terminated">Terminated</option>
                    </select>
                  </div>
                </div>

                {{-- Salary --}}
                <div class="col-12"><h6 class="text-muted font-weight-bold mb-2 border-bottom pb-1 mt-2">Salary Details <small class="text-muted font-weight-normal">(optional)</small></h6></div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Per Day (₹)</label>
                    <input type="number" step="0.01" min="0" name="per_day" id="per_day" class="form-control" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Per Month (₹) <small class="text-muted">30 days</small></label>
                    <input type="number" step="0.01" min="0" name="per_month" id="per_month" class="form-control" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Effect From</label>
                    <input type="date" name="effect_from" id="effect_from" class="form-control">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Remark</label>
                    <input type="text" name="remark" id="salary_remark" class="form-control" placeholder="e.g. Joining Salary">
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="addEmployeesForm" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    {{-- End Add Modal --}}

    {{-- Edit Popup Modal --}}
    <div class="modal fade" id="edit-module-popup" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Employee</h4>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <form id="editEmployeesForm" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <input type="hidden" name="id" id="edit_emp_id">
              <div class="row">
                {{-- Personal Info --}}
                <div class="col-12"><h6 class="text-muted font-weight-bold mb-2 border-bottom pb-1">Personal Information</h6></div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Emp Code <span class="text-danger">*</span></label>
                    <input type="text" name="emp_code" id="edit_emp_code" class="form-control" placeholder="e.g. EMP001">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-control" placeholder="Enter Full Name">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Aadhar No</label>
                    <input type="text" name="aadhar_no" id="edit_aadhar_no" class="form-control" placeholder="12-digit Aadhar">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Aadhar Image <small class="text-muted">(jpg/png, max 2MB)</small></label>
                    <input type="file" name="aadhar_image" id="edit_aadhar_image" class="form-control-file" accept="image/jpg,image/jpeg,image/png">
                    <div id="edit_aadhar_image_preview" class="mt-2 d-none">
                      <img src="" alt="Current" style="max-height:100px;border-radius:4px;">
                      <small class="d-block text-muted">Current image</small>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Mobile (Primary) <span class="text-danger">*</span></label>
                    <input type="text" name="mobile_primary" id="edit_mobile_primary" class="form-control" placeholder="10-digit number">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Mobile (Secondary)</label>
                    <input type="text" name="mobile_secondary" id="edit_mobile_secondary" class="form-control" placeholder="10-digit number">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>WhatsApp No</label>
                    <input type="text" name="whatsapp_no" id="edit_whatsapp_no" class="form-control" placeholder="10-digit number">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Permanent Address</label>
                    <textarea name="permanent_address" id="edit_permanent_address" class="form-control" rows="2"></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Present Address <span class="text-danger">*</span></label>
                    <textarea name="present_address" id="edit_present_address" class="form-control" rows="2"></textarea>
                  </div>
                </div>

                {{-- Bank & Payment --}}
                <div class="col-12"><h6 class="text-muted font-weight-bold mb-2 border-bottom pb-1 mt-2">Bank & Payment Details</h6></div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Bank Account No</label>
                    <input type="text" name="bank_account_no" id="edit_bank_account_no" class="form-control">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" id="edit_bank_name" class="form-control">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>IFSC Code</label>
                    <input type="text" name="ifsc_code" id="edit_ifsc_code" class="form-control">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>UPI Number</label>
                    <input type="text" name="upi_number" id="edit_upi_number" class="form-control">
                  </div>
                </div>

                {{-- Job Info --}}
                <div class="col-12"><h6 class="text-muted font-weight-bold mb-2 border-bottom pb-1 mt-2">Job Details</h6></div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Employee Type <span class="text-danger">*</span></label>
                    <select name="employee_type" id="edit_employee_type" class="form-control">
                      <option value="">Select Type</option>
                      <option value="lathe">Lathe</option>
                      <option value="cnc">CNC</option>
                      <option value="both">Both</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Experience (Years)</label>
                    <input type="number" step="0.1" min="0" name="experience_years" id="edit_experience_years" class="form-control">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Joining Date</label>
                    <input type="date" name="joining_date" id="edit_joining_date" class="form-control">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Status <span class="text-danger">*</span></label>
                    <select name="status" id="edit_status" class="form-control">
                      <option value="active">Active</option>
                      <option value="inactive">Inactive</option>
                      <option value="terminated">Terminated</option>
                    </select>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="editEmployeesForm" id="editSaveBtn" class="btn btn-primary" disabled>Save changes</button>
          </div>
        </div>
      </div>
    </div>
    {{-- End Edit Modal --}}

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

      // Auto-fetch next emp_code when Add modal opens
      $('#add-module-popup').on('show.bs.modal', function () {
        $.getJSON('{{ route('employees.next-code') }}', function (res) {
          $('#emp_code').val(res.emp_code);
        });
      });

      $('#employees-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [],
        ajax: {
          url: '{{ route('employees.index') }}',
          data: function (d) {
            d.status = $('#status-filter').val();
          }
        },
        columns: [
          { data: 'DT_RowIndex',     name: 'DT_RowIndex', orderable: false, searchable: false },
          { data: 'emp_code',        name: 'emp_code' },
          { data: 'name',            name: 'name' },
          { data: 'mobile_primary',  name: 'mobile_primary' },
          { data: 'employee_type',   name: 'employee_type' },
          { data: 'joining_date',    name: 'joining_date' },
          { data: 'status',          name: 'status' },
          { data: 'action',          name: 'action', orderable: false, searchable: false },
        ],
        initComplete: function () {
          var filterHtml = '<span class="d-inline-block ml-3"><label>Status: <select id="status-filter" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="active">Active</option><option value="inactive">Inactive</option><option value="terminated">Terminated</option></select></label></span>';
          $('#employees-table_length').css('display', 'inline-block');
          $('#employees-table_length').after(filterHtml);
          $('#status-filter').on('change', function () {
            $('#employees-table').DataTable().ajax.reload();
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
      highlight: function (element) { $(element).addClass('is-invalid'); },
      unhighlight: function (element) { $(element).removeClass('is-invalid'); }
    });

    // SweetAlert Toast
    var Toast = Swal.mixin({
      toast: true, position: 'top-end', showConfirmButton: false,
      timer: 4000, timerProgressBar: true, background: '#f4f6f9', iconColor: '#28a745',
      customClass: { title: 'text-success font-weight-bold ml-2' },
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
      }
    });

    function showError(message) {
      Swal.fire({
        icon: 'error', title: 'Action Failed',
        html: '<div class="text-left text-danger">' + message + '</div>',
        confirmButtonText: '<i class="fas fa-times-circle"></i> Close',
        confirmButtonColor: '#dc3545', buttonsStyling: true, backdrop: 'rgba(0,0,123,0.4)'
      });
    }

    function parseErrors(xhr) {
      if (xhr.responseJSON && xhr.responseJSON.errors) {
        return Object.values(xhr.responseJSON.errors).map(e => e.join('<br>')).join('<br>');
      }
      return xhr.responseJSON?.message || 'Something went wrong. Please try again.';
    }

    // Salary auto-calculate — Add form
    $('#per_day').on('input', function () {
      let val = parseFloat($(this).val());
      if (!isNaN(val) && val > 0) {
        $('#per_month').val((val * 30).toFixed(2));
      }
    });
    $('#per_month').on('input', function () {
      let val = parseFloat($(this).val());
      if (!isNaN(val) && val > 0) {
        $('#per_day').val((val / 30).toFixed(2));
      }
    });

    // Aadhar image preview — Add form
    $('#aadhar_image').on('change', function () {
      let file = this.files[0];
      if (file) {
        let reader = new FileReader();
        reader.onload = function (e) {
          $('#aadhar_image_preview img').attr('src', e.target.result);
          $('#aadhar_image_preview').removeClass('d-none');
        };
        reader.readAsDataURL(file);
      }
    });

    // Aadhar image preview — Edit form
    $('#edit_aadhar_image').on('change', function () {
      let file = this.files[0];
      if (file) {
        let reader = new FileReader();
        reader.onload = function (e) {
          $('#edit_aadhar_image_preview img').attr('src', e.target.result);
          $('#edit_aadhar_image_preview').removeClass('d-none');
          $('#edit_aadhar_image_preview small').text('New image selected');
        };
        reader.readAsDataURL(file);
      }
    });

    // Add Employee
    $('#addEmployeesForm').validate({
      rules: {
        emp_code:       { required: true, maxlength: 20 },
        name:           { required: true, maxlength: 255 },
        mobile_primary: { required: true, minlength: 10, maxlength: 10, digits: true },
        present_address:{ required: true },
        employee_type:  { required: true },
        status:         { required: true },
      },
      messages: {
        emp_code:       { required: 'Please enter employee code.' },
        name:           { required: 'Please enter full name.' },
        mobile_primary: { required: 'Please enter primary mobile.', digits: 'Only digits allowed.', minlength: 'Must be 10 digits.', maxlength: 'Must be 10 digits.' },
        present_address:{ required: 'Please enter present address.' },
        employee_type:  { required: 'Please select employee type.' },
        status:         { required: 'Please select status.' },
      },
      submitHandler: function (form, e) {
        e.preventDefault();
        let submitBtn = $(form).closest('.modal-content').find('button[type="submit"]');
        let originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        let formData = new FormData(form);
        $.ajax({
          url: '{{ route('employees.store') }}', type: 'POST',
          data: formData, processData: false, contentType: false,
          success: function (response) {
            submitBtn.html(originalText).prop('disabled', false);
            if (response.success) {
              Toast.fire({ icon: 'success', title: response.message });
              $('#add-module-popup').modal('hide');
              form.reset();
              $('#aadhar_image_preview').addClass('d-none');
              $('#employees-table').DataTable().ajax.reload();
            }
          },
          error: function (xhr) {
            submitBtn.html(originalText).prop('disabled', false);
            showError(parseErrors(xhr));
          }
        });
      }
    });

    // Populate Edit Form
    $(document).on('click', '.edit-btn', function () {
      let id = $(this).data('id');
      $('#editEmployeesForm')[0].reset();
      $('#editSaveBtn').prop('disabled', true);
      $('#editEmployeesForm').data('initial-state', '');

      $.ajax({
        url: '/master/employees/' + id + '/edit', type: 'GET',
        success: function (response) {
          if (response.success) {
            let d = response.data;
            $('#edit_emp_id').val(d.id);
            $('#edit_emp_code').val(d.emp_code);
            $('#edit_name').val(d.name);
            $('#edit_aadhar_no').val(d.aadhar_no);
            $('#edit_mobile_primary').val(d.mobile_primary);
            $('#edit_mobile_secondary').val(d.mobile_secondary);
            $('#edit_whatsapp_no').val(d.whatsapp_no);
            $('#edit_upi_number').val(d.upi_number);
            $('#edit_permanent_address').val(d.permanent_address);
            $('#edit_present_address').val(d.present_address);
            $('#edit_bank_account_no').val(d.bank_account_no);
            $('#edit_bank_name').val(d.bank_name);
            $('#edit_ifsc_code').val(d.ifsc_code);
            $('#edit_employee_type').val(d.employee_type);
            $('#edit_experience_years').val(d.experience_years);
            $('#edit_joining_date').val(d.joining_date ? d.joining_date.substring(0, 10) : '');
            $('#edit_status').val(d.status);
            if (d.aadhar_image) {
              $('#edit_aadhar_image_preview img').attr('src', '/storage/' + d.aadhar_image);
              $('#edit_aadhar_image_preview').removeClass('d-none');
            } else {
              $('#edit_aadhar_image_preview').addClass('d-none');
            }
            $('#editEmployeesForm').data('initial-state', $('#editEmployeesForm').serialize());
          }
        },
        error: function () { Swal.fire('Error', 'Failed to fetch data', 'error'); }
      });
    });

    // Enable Save on change
    $('#editEmployeesForm').on('change input', function () {
      let cur = $(this).serialize(), init = $(this).data('initial-state');
      $('#editSaveBtn').prop('disabled', (cur === init || init === ''));
    });

    // Edit Submit
    $('#editEmployeesForm').validate({
      rules: {
        emp_code:       { required: true, maxlength: 20 },
        name:           { required: true, maxlength: 255 },
        mobile_primary: { required: true, minlength: 10, maxlength: 10, digits: true },
        present_address:{ required: true },
        employee_type:  { required: true },
        status:         { required: true },
      },
      messages: {
        emp_code:       { required: 'Please enter employee code.' },
        name:           { required: 'Please enter full name.' },
        mobile_primary: { required: 'Please enter primary mobile.', digits: 'Only digits allowed.', minlength: 'Must be 10 digits.', maxlength: 'Must be 10 digits.' },
        present_address:{ required: 'Please enter present address.' },
        employee_type:  { required: 'Please select employee type.' },
        status:         { required: 'Please select status.' },
      },
      submitHandler: function (form, e) {
        e.preventDefault();
        let submitBtn = $('#editSaveBtn');
        let originalText = submitBtn.html();
        let id = $('#edit_emp_id').val();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        let editFormData = new FormData(form);
        $.ajax({
          url: '/master/employees/' + id, type: 'POST',
          data: editFormData, processData: false, contentType: false,
          success: function (response) {
            submitBtn.html(originalText).prop('disabled', true);
            if (response.success) {
              Toast.fire({ icon: 'success', title: response.message });
              $('#edit-module-popup').modal('hide');
              $(form).data('initial-state', $(form).serialize());
              $('#employees-table').DataTable().ajax.reload();
            }
          },
          error: function (xhr) {
            submitBtn.html(originalText).prop('disabled', false);
            showError(parseErrors(xhr));
          }
        });
      }
    });

    // Delete
    $(document).on('click', '.delete-btn', function () {
      let id = $(this).data('id');
      Swal.fire({
        title: 'Are you sure?', text: "You won't be able to revert this!",
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash"></i> Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: '/master/employees/' + id, type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (response) {
              if (response.success) {
                Toast.fire({ icon: 'success', title: response.message });
                $('#employees-table').DataTable().ajax.reload();
              }
            },
            error: function () { Swal.fire('Error!', 'Something went wrong.', 'error'); }
          });
        }
      });
    });
  </script>
@endpush
