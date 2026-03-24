@extends('layouts.app')

@section('title', config('app.name') . ' | Contacts')

@push('styles')
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Contacts</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Contacts</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Contact List</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-module-popup">
              <i class="fas fa-plus"></i> Add Contact
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="contacts-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Person Name</th>
                <th>Contact No</th>
                <th>WhatsApp No</th>
                <th>UPI No</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

    </div>

    {{-- ===== Add Modal ===== --}}
    <div class="modal fade" id="add-module-popup" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Contact</h4>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <form id="addContactForm">
              @csrf

              {{-- ── Basic Info ── --}}
              <h6 class="text-muted font-weight-bold mb-2 border-bottom pb-1">Basic Information</h6>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Person Name <span class="text-danger">*</span></label>
                    <input type="text" name="person_name" id="person_name" class="form-control" placeholder="Enter Person Name">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Contact No <span class="text-danger">*</span></label>
                    <input type="text" name="contact_no" id="contact_no" class="form-control" placeholder="Enter Contact Number">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>WhatsApp No</label>
                    <input type="text" name="whatsapp_no" id="whatsapp_no" class="form-control" placeholder="Enter WhatsApp Number">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>UPI No / ID</label>
                    <input type="text" name="upi_no" id="upi_no" class="form-control" placeholder="e.g. name@upi">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Remarks</label>
                    <input type="text" name="remarks" id="remarks" class="form-control" placeholder="Enter Remarks">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Status</label>
                    <div class="custom-control custom-switch mt-2">
                      <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" checked>
                      <label class="custom-control-label" for="is_active">Active</label>
                    </div>
                  </div>
                </div>
              </div>

              {{-- ── Bank Details ── --}}
              <h6 class="text-muted font-weight-bold mb-2 border-bottom pb-1 mt-2">Bank Details</h6>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Account Holder Name</label>
                    <input type="text" name="account_holder_name" id="account_holder_name" class="form-control" placeholder="As per bank records">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Account No</label>
                    <input type="text" name="account_no" id="account_no" class="form-control" placeholder="Enter Account Number">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>IFSC Code</label>
                    <input type="text" name="ifsc_code" id="ifsc_code" class="form-control text-uppercase" placeholder="e.g. SBIN0001234">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" id="bank_name" class="form-control" placeholder="e.g. State Bank of India">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Branch</label>
                    <input type="text" name="branch" id="branch" class="form-control" placeholder="Enter Branch Name">
                  </div>
                </div>
              </div>

            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="addContactForm" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    {{-- End Add Modal --}}

    {{-- ===== Edit Modal ===== --}}
    <div class="modal fade" id="edit-module-popup" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Contact</h4>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <form id="editContactForm">
              @csrf
              @method('PUT')
              <input type="hidden" name="id" id="edit_contact_id">

              {{-- ── Basic Info ── --}}
              <h6 class="text-muted font-weight-bold mb-2 border-bottom pb-1">Basic Information</h6>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Person Name <span class="text-danger">*</span></label>
                    <input type="text" name="person_name" id="edit_person_name" class="form-control" placeholder="Enter Person Name">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Contact No <span class="text-danger">*</span></label>
                    <input type="text" name="contact_no" id="edit_contact_no" class="form-control" placeholder="Enter Contact Number">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>WhatsApp No</label>
                    <input type="text" name="whatsapp_no" id="edit_whatsapp_no" class="form-control" placeholder="Enter WhatsApp Number">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>UPI No / ID</label>
                    <input type="text" name="upi_no" id="edit_upi_no" class="form-control" placeholder="e.g. name@upi">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Remarks</label>
                    <input type="text" name="remarks" id="edit_remarks" class="form-control" placeholder="Enter Remarks">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Status</label>
                    <div class="custom-control custom-switch mt-2">
                      <input type="checkbox" name="is_active" class="custom-control-input" id="edit_is_active" value="1">
                      <label class="custom-control-label" for="edit_is_active">Active</label>
                    </div>
                  </div>
                </div>
              </div>

              {{-- ── Bank Details ── --}}
              <h6 class="text-muted font-weight-bold mb-2 border-bottom pb-1 mt-2">Bank Details</h6>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Account Holder Name</label>
                    <input type="text" name="account_holder_name" id="edit_account_holder_name" class="form-control" placeholder="As per bank records">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Account No</label>
                    <input type="text" name="account_no" id="edit_account_no" class="form-control" placeholder="Enter Account Number">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>IFSC Code</label>
                    <input type="text" name="ifsc_code" id="edit_ifsc_code" class="form-control text-uppercase" placeholder="e.g. SBIN0001234">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" id="edit_bank_name" class="form-control" placeholder="e.g. State Bank of India">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Branch</label>
                    <input type="text" name="branch" id="edit_branch" class="form-control" placeholder="Enter Branch Name">
                  </div>
                </div>
              </div>

            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" form="editContactForm" id="editSaveBtn" class="btn btn-primary" disabled>Save changes</button>
          </div>
        </div>
      </div>
    </div>
    {{-- End Edit Modal --}}

  </section>
@endsection

@push('scripts')
  <script src="{{ asset('adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

  <script>
    $(function () {
      $('#contacts-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        order: [],
        ajax: '{{ route('contacts.index') }}',
        columns: [
          { data: 'DT_RowIndex',  name: 'DT_RowIndex',  orderable: false, searchable: false },
          { data: 'person_name',  name: 'person_name' },
          { data: 'contact_no',   name: 'contact_no' },
          { data: 'whatsapp_no',  name: 'whatsapp_no' },
          { data: 'upi_no',       name: 'upi_no' },
          { data: 'action',       name: 'action', orderable: false, searchable: false },
        ],
      });
    });

    $.validator.setDefaults({
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight:   function (el) { $(el).addClass('is-invalid'); },
      unhighlight: function (el) { $(el).removeClass('is-invalid'); }
    });

    var Toast = Swal.mixin({
      toast: true, position: 'top-end', showConfirmButton: false,
      timer: 4000, timerProgressBar: true, background: '#f4f6f9', iconColor: '#28a745',
      customClass: { title: 'text-success font-weight-bold ml-2' },
      didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
      }
    });

    // ── Add Form ──
    $('#addContactForm').validate({
      rules: {
        person_name: { required: true, maxlength: 255 },
        contact_no:  { required: true, maxlength: 20 }
      },
      messages: {
        person_name: { required: 'Please enter a person name.' },
        contact_no:  { required: 'Please enter a contact number.' }
      },
      submitHandler: function (form, e) {
        e.preventDefault();
        let submitBtn = $(form).closest('.modal-content').find('button[type="submit"]');
        let originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
          url: '{{ route('contacts.store') }}',
          type: 'POST',
          data: $(form).serialize(),
          success: function (response) {
            submitBtn.html(originalText).prop('disabled', false);
            if (response.success) {
              Toast.fire({ icon: 'success', title: response.message });
              $('#add-module-popup').modal('hide');
              form.reset();
              $('#contacts-table').DataTable().ajax.reload();
            }
          },
          error: function (xhr) {
            submitBtn.html(originalText).prop('disabled', false);
            let msg = 'Something went wrong. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.errors)
              msg = Object.values(xhr.responseJSON.errors).map(e => e.join('<br>')).join('<br>');
            else if (xhr.responseJSON && xhr.responseJSON.message)
              msg = xhr.responseJSON.message;
            Swal.fire({ icon: 'error', title: 'Action Failed', html: '<div class="text-left text-danger">' + msg + '</div>', confirmButtonText: '<i class="fas fa-times-circle"></i> Close', confirmButtonColor: '#dc3545', backdrop: 'rgba(0,0,123,0.4)' });
          }
        });
      }
    });

    // ── Populate Edit Form ──
    $(document).on('click', '.edit-btn', function () {
      let id = $(this).data('id');
      $('#editContactForm')[0].reset();
      $('#editSaveBtn').prop('disabled', true);
      $('#editContactForm').data('initial-state', '');

      $.ajax({
        url: '/master/contacts/' + id + '/edit',
        type: 'GET',
        success: function (response) {
          if (response.success) {
            let d = response.data;
            $('#edit_contact_id').val(d.id);
            $('#edit_person_name').val(d.person_name);
            $('#edit_contact_no').val(d.contact_no);
            $('#edit_whatsapp_no').val(d.whatsapp_no);
            $('#edit_upi_no').val(d.upi_no);
            $('#edit_remarks').val(d.remarks);
            $('#edit_is_active').prop('checked', d.is_active ? true : false);
            $('#edit_account_holder_name').val(d.account_holder_name);
            $('#edit_account_no').val(d.account_no);
            $('#edit_ifsc_code').val(d.ifsc_code);
            $('#edit_bank_name').val(d.bank_name);
            $('#edit_branch').val(d.branch);
            $('#editContactForm').data('initial-state', $('#editContactForm').serialize());
          }
        },
        error: function () { Swal.fire('Error', 'Failed to fetch data', 'error'); }
      });
    });

    $('#editContactForm').on('change input', function () {
      let cur = $(this).serialize(), init = $(this).data('initial-state');
      $('#editSaveBtn').prop('disabled', cur === init || init === '');
    });

    // ── Edit Form ──
    $('#editContactForm').validate({
      rules: {
        person_name: { required: true, maxlength: 255 },
        contact_no:  { required: true, maxlength: 20 }
      },
      messages: {
        person_name: { required: 'Please enter a person name.' },
        contact_no:  { required: 'Please enter a contact number.' }
      },
      submitHandler: function (form, e) {
        e.preventDefault();
        let submitBtn = $('#editSaveBtn');
        let originalText = submitBtn.html();
        let id = $('#edit_contact_id').val();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

        $.ajax({
          url: '/master/contacts/' + id,
          type: 'POST',
          data: $(form).serialize(),
          success: function (response) {
            submitBtn.html(originalText).prop('disabled', true);
            if (response.success) {
              Toast.fire({ icon: 'success', title: response.message });
              $('#edit-module-popup').modal('hide');
              $(form).data('initial-state', $(form).serialize());
              $('#contacts-table').DataTable().ajax.reload();
            }
          },
          error: function (xhr) {
            submitBtn.html(originalText).prop('disabled', false);
            let msg = 'Something went wrong. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.errors)
              msg = Object.values(xhr.responseJSON.errors).map(e => e.join('<br>')).join('<br>');
            else if (xhr.responseJSON && xhr.responseJSON.message)
              msg = xhr.responseJSON.message;
            Swal.fire({ icon: 'error', title: 'Action Failed', html: '<div class="text-left text-danger">' + msg + '</div>', confirmButtonText: '<i class="fas fa-times-circle"></i> Close', confirmButtonColor: '#dc3545', backdrop: 'rgba(0,0,123,0.4)' });
          }
        });
      }
    });

    // ── Delete ──
    $(document).on('click', '.delete-btn', function () {
      let id = $(this).data('id');
      Swal.fire({
        title: 'Are you sure?', text: "You won't be able to revert this!", icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash"></i> Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: '/master/contacts/' + id, type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (response) {
              if (response.success) {
                Toast.fire({ icon: 'success', title: response.message });
                $('#contacts-table').DataTable().ajax.reload();
              }
            },
            error: function () { Swal.fire('Error!', 'Something went wrong.', 'error'); }
          });
        }
      });
    });
  </script>
@endpush
