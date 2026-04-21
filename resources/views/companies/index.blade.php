@extends('layouts.app')

@section('title', config('app.name') . ' | Customer Companies')

@push('styles')
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <style>
    .gst-verified-panel { font-size: 13px; }
    .gst-verified-panel .gst-row { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 4px; }
    .gst-badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
  </style>
@endpush

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Customer Companies</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Customer Companies</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Customer Company List</h3>
          <div class="card-tools">
            @can('create companies')
            <button type="button" class="btn btn-secondary btn-sm mr-1" data-toggle="modal" data-target="#manage-designations-popup">
              <i class="fas fa-tags"></i> Manage Designations
            </button>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-module-popup">
              <i class="fas fa-plus"></i> Add Customer Company
            </button>
            @endcan
          </div>
        </div>
        <div class="card-body">
          <table id="companies-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Customer/Company Name</th>
                <th>Plant Name</th>
                <th>Contact Person</th>
                <th>Designation</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- ===================== ADD MODAL ===================== --}}
    <div class="modal fade" id="add-module-popup" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Customer Company</h4>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <form id="addCompanyForm">
              @csrf
              {{-- Hidden GST verified fields --}}
              <input type="hidden" name="gst_trade_name"       id="add_gst_trade_name">
              <input type="hidden" name="gst_legal_name"       id="add_gst_legal_name">
              <input type="hidden" name="gst_status"           id="add_gst_status">
              <input type="hidden" name="gst_state"            id="add_gst_state">
              <input type="hidden" name="gst_pan"              id="add_gst_pan">
              <input type="hidden" name="gst_registration_date" id="add_gst_registration_date">
              <input type="hidden" name="gst_business_type"    id="add_gst_business_type">
              <input type="hidden" name="gst_verified_at"      id="add_gst_verified_at">

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Customer/Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" id="company_name" class="form-control" placeholder="Enter Customer/Company Name">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Plant Name</label>
                    <input type="text" name="plant_name" id="plant_name" class="form-control" placeholder="Enter Plant Name">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" id="contact_person" class="form-control" placeholder="Enter Contact Person">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Designation</label>
                    <select name="designation_id" id="designation_id" class="form-control">
                      <option value="">— Select Designation —</option>
                      @foreach($designations as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Contact Phone</label>
                    <input type="text" name="contact_phone" id="contact_phone" class="form-control" placeholder="Enter Phone Number">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" id="address" class="form-control" placeholder="Enter Address"></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Remark</label>
                    <textarea name="remark" id="remark" class="form-control" placeholder="Enter Remark"></textarea>
                  </div>
                </div>

                {{-- GST Section --}}
                <div class="col-12"><hr class="mt-1 mb-2"><h6 class="text-muted font-weight-bold mb-2">GST Details</h6></div>
                <div class="col-md-6">
                  <div class="form-group mb-1">
                    <label>GST Number (GSTIN) <small class="text-muted">15 characters</small></label>
                    <div class="input-group">
                      <input type="text" name="gst_no" id="gst_no" class="form-control text-uppercase"
                        placeholder="e.g. 27AAAPL1234C1Z5" maxlength="15" style="letter-spacing:1px;">
                      <div class="input-group-append">
                        <button type="button" class="btn btn-info gst-verify-btn"
                          data-prefix="add" data-input="#gst_no">
                          <i class="fas fa-search-plus mr-1"></i> Verify
                        </button>
                      </div>
                    </div>
                  </div>
                  <div id="add_gst_result"></div>
                </div>
                <div class="col-md-6 d-flex align-items-start pt-4">
                  <div id="add_gst_panel" class="gst-verified-panel d-none w-100">
                    <div class="alert alert-success py-2 px-3 mb-0">
                      <strong><i class="fas fa-check-circle mr-1"></i> GST Verified</strong>
                      <div class="gst-row mt-1">
                        <span><strong>State:</strong> <span id="add_panel_state">—</span></span>
                        <span>|</span>
                        <span><strong>PAN:</strong> <span id="add_panel_pan">—</span></span>
                        <span>|</span>
                        <span><strong>Status:</strong> <span id="add_panel_status">—</span></span>
                      </div>
                      <div class="mt-1" id="add_panel_names"></div>
                      <div class="mt-1" id="add_panel_extra"></div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>Status</label>
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
            <button type="submit" form="addCompanyForm" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
    {{-- End Add Modal --}}

    {{-- ===================== EDIT MODAL ===================== --}}
    <div class="modal fade" id="edit-module-popup" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Customer Company</h4>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <form id="editCompanyForm">
              @csrf
              @method('PUT')
              <input type="hidden" name="id" id="edit_company_id">
              {{-- Hidden GST verified fields --}}
              <input type="hidden" name="gst_trade_name"        id="edit_gst_trade_name">
              <input type="hidden" name="gst_legal_name"        id="edit_gst_legal_name">
              <input type="hidden" name="gst_status"            id="edit_gst_status_val">
              <input type="hidden" name="gst_state"             id="edit_gst_state_val">
              <input type="hidden" name="gst_pan"               id="edit_gst_pan_val">
              <input type="hidden" name="gst_registration_date" id="edit_gst_registration_date">
              <input type="hidden" name="gst_business_type"     id="edit_gst_business_type">
              <input type="hidden" name="gst_verified_at"       id="edit_gst_verified_at">

              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Customer/Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" id="edit_company_name" class="form-control" placeholder="Enter Customer/Company Name">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Plant Name</label>
                    <input type="text" name="plant_name" id="edit_plant_name" class="form-control" placeholder="Enter Plant Name">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" id="edit_contact_person" class="form-control" placeholder="Enter Contact Person">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Designation</label>
                    <select name="designation_id" id="edit_designation_id" class="form-control">
                      <option value="">— Select Designation —</option>
                      @foreach($designations as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Contact Phone</label>
                    <input type="text" name="contact_phone" id="edit_contact_phone" class="form-control" placeholder="Enter Phone Number">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" id="edit_address" class="form-control" placeholder="Enter Address"></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Remark</label>
                    <textarea name="remark" id="edit_remark" class="form-control" placeholder="Enter Remark"></textarea>
                  </div>
                </div>

                {{-- GST Section --}}
                <div class="col-12"><hr class="mt-1 mb-2"><h6 class="text-muted font-weight-bold mb-2">GST Details</h6></div>
                <div class="col-md-6">
                  <div class="form-group mb-1">
                    <label>GST Number (GSTIN) <small class="text-muted">15 characters</small></label>
                    <div class="input-group">
                      <input type="text" name="gst_no" id="edit_gst_no" class="form-control text-uppercase"
                        placeholder="e.g. 27AAAPL1234C1Z5" maxlength="15" style="letter-spacing:1px;">
                      <div class="input-group-append">
                        <button type="button" class="btn btn-info gst-verify-btn"
                          data-prefix="edit" data-input="#edit_gst_no">
                          <i class="fas fa-search-plus mr-1"></i> Verify
                        </button>
                      </div>
                    </div>
                  </div>
                  <div id="edit_gst_result"></div>
                </div>
                <div class="col-md-6 d-flex align-items-start pt-4">
                  <div id="edit_gst_panel" class="gst-verified-panel d-none w-100">
                    <div class="alert alert-success py-2 px-3 mb-0">
                      <strong><i class="fas fa-check-circle mr-1"></i> GST Verified</strong>
                      <div class="gst-row mt-1">
                        <span><strong>State:</strong> <span id="edit_panel_state">—</span></span>
                        <span>|</span>
                        <span><strong>PAN:</strong> <span id="edit_panel_pan">—</span></span>
                        <span>|</span>
                        <span><strong>Status:</strong> <span id="edit_panel_status">—</span></span>
                      </div>
                      <div class="mt-1" id="edit_panel_names"></div>
                      <div class="mt-1" id="edit_panel_extra"></div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>Status</label>
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
            <button type="submit" form="editCompanyForm" id="editSaveBtn" class="btn btn-primary" disabled>Save changes</button>
          </div>
        </div>
      </div>
    </div>
    {{-- End Edit Modal --}}

    {{-- ===================== MANAGE DESIGNATIONS MODAL ===================== --}}
    <div class="modal fade" id="manage-designations-popup" tabindex="-1" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header bg-secondary text-white">
            <h5 class="modal-title"><i class="fas fa-tags mr-2"></i>Manage Designations</h5>
            <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            {{-- Add new --}}
            <div class="input-group mb-3">
              <input type="text" id="new_designation_name" class="form-control"
                placeholder="Enter new designation (e.g. Manager)" maxlength="100">
              <div class="input-group-append">
                <button type="button" class="btn btn-primary" id="add-designation-btn">
                  <i class="fas fa-plus"></i> Add
                </button>
              </div>
            </div>
            <div id="designation-form-error" class="text-danger small mb-2 d-none"></div>

            {{-- List --}}
            <div class="table-responsive" style="max-height:320px; overflow-y:auto;">
              <table class="table table-sm table-bordered table-hover mb-0" id="designations-list-table">
                <thead class="thead-light">
                  <tr><th>#</th><th>Designation Name</th><th class="text-center" style="width:80px">Action</th></tr>
                </thead>
                <tbody id="designations-list-body">
                  <tr><td colspan="3" class="text-center text-muted py-3">Loading...</td></tr>
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
    {{-- End Manage Designations Modal --}}

  </section>
@endsection

@push('scripts')
  <script src="{{ asset('public/adminlte/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/jquery-validation/additional-methods.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

  <script>
  $(function () {

    // ── DataTable ──────────────────────────────────────────────────────
    $('#companies-table').DataTable({
      processing: true, serverSide: true, responsive: true, order: [],
      ajax: {
        url: '{{ route('companies.index') }}',
        data: function (d) {
          d.status         = $('#status-filter').val();
          d.designation_id = $('#designation-filter').val();
        }
      },
      columns: [
        { data: 'DT_RowIndex',    name: 'DT_RowIndex',    orderable: false, searchable: false },
        { data: 'company_name',      name: 'company_name' },
        { data: 'plant_name',        name: 'plant_name' },
        { data: 'contact_person',    name: 'contact_person' },
        { data: 'designation_name',  name: 'designation_name', orderable: false, searchable: false },
        { data: 'contact_phone',     name: 'contact_phone' },
        { data: 'is_active',         name: 'is_active' },
        { data: 'action',            name: 'action', orderable: false, searchable: false },
      ],
      initComplete: function () {
        var desigOptions = '<option value="">All Designations</option>';
        @foreach($designations as $d)
          desigOptions += '<option value="{{ $d->id }}">{{ addslashes($d->name) }}</option>';
        @endforeach

        var filterHtml =
          '<span class="d-inline-block ml-3"><label>Status: ' +
            '<select id="status-filter" class="custom-select custom-select-sm form-control form-control-sm">' +
              '<option value="">All</option><option value="1">Active</option><option value="0">Inactive</option>' +
            '</select>' +
          '</label></span>' +
          '<span class="d-inline-block ml-3"><label>Designation: ' +
            '<select id="designation-filter" class="custom-select custom-select-sm form-control form-control-sm">' +
              desigOptions +
            '</select>' +
          '</label></span>';

        $('#companies-table_length').css('display', 'inline-block').after(filterHtml);
        $('#status-filter, #designation-filter').on('change', function () {
          $('#companies-table').DataTable().ajax.reload();
        });
      }
    });

    // ── Validation defaults ────────────────────────────────────────────
    $.validator.setDefaults({
      errorElement: 'span',
      errorPlacement: function (error, element) {
        error.addClass('invalid-feedback');
        element.closest('.form-group').append(error);
      },
      highlight:   function (el) { $(el).addClass('is-invalid'); },
      unhighlight: function (el) { $(el).removeClass('is-invalid'); }
    });

    // ── Toast ─────────────────────────────────────────────────────────
    var Toast = Swal.mixin({
      toast: true, position: 'top-end', showConfirmButton: false,
      timer: 4000, timerProgressBar: true, background: '#f4f6f9', iconColor: '#28a745',
      customClass: { title: 'text-success font-weight-bold ml-2' },
      didOpen: (t) => { t.addEventListener('mouseenter', Swal.stopTimer); t.addEventListener('mouseleave', Swal.resumeTimer); }
    });

    function showError(msg) {
      Swal.fire({ icon: 'error', title: 'Action Failed',
        html: '<div class="text-left text-danger">' + msg + '</div>',
        confirmButtonText: '<i class="fas fa-times-circle"></i> Close',
        confirmButtonColor: '#dc3545', backdrop: 'rgba(0,0,123,0.4)' });
    }
    function parseErrors(xhr) {
      if (xhr.responseJSON && xhr.responseJSON.errors)
        return Object.values(xhr.responseJSON.errors).map(e => e.join('<br>')).join('<br>');
      return xhr.responseJSON?.message || 'Something went wrong.';
    }

    // ── GST auto-uppercase ────────────────────────────────────────────
    $(document).on('input', '#gst_no, #edit_gst_no', function () {
      var pos = this.selectionStart;
      $(this).val($(this).val().toUpperCase());
      this.setSelectionRange(pos, pos);
    });

    // ── GST Verify ────────────────────────────────────────────────────
    var gstVerifyUrl = '{{ route("companies.verify-gst") }}';

    function populateGstPanel(prefix, res) {
      var $panel = $('#' + prefix + '_gst_panel');
      if (!res || !res.valid) { $panel.addClass('d-none'); return; }

      $('#' + prefix + '_panel_state').text(res.gst_state || '—');
      $('#' + prefix + '_panel_pan').text(res.gst_pan || '—');

      var statusText = res.gst_status || 'Format Valid';
      var statusColor = (statusText.toLowerCase().includes('active')) ? 'success' : 'warning';
      $('#' + prefix + '_panel_status').html('<span class="badge badge-' + statusColor + '">' + statusText + '</span>');

      var namesHtml = '';
      if (res.gst_legal_name)  namesHtml += '<span><strong>Legal Name:</strong> ' + res.gst_legal_name + '</span> ';
      if (res.gst_trade_name)  namesHtml += '<span><strong>Trade Name:</strong> ' + res.gst_trade_name + '</span>';
      $('#' + prefix + '_panel_names').html(namesHtml);

      var extraHtml = '';
      if (res.gst_registration_date) extraHtml += '<span><strong>Reg. Date:</strong> ' + res.gst_registration_date + '</span> ';
      if (res.gst_business_type)     extraHtml += '<span><strong>Type:</strong> ' + res.gst_business_type + '</span>';
      $('#' + prefix + '_panel_extra').html(extraHtml);

      $panel.removeClass('d-none');

      // Fill hidden inputs
      $('#' + prefix + '_gst_trade_name').val(res.gst_trade_name || '');
      $('#' + prefix + '_gst_legal_name').val(res.gst_legal_name || '');
      if (prefix === 'add') {
        $('#add_gst_status').val(res.gst_status || '');
        $('#add_gst_state').val(res.gst_state || '');
        $('#add_gst_pan').val(res.gst_pan || '');
        $('#add_gst_registration_date').val(res.gst_registration_date || '');
        $('#add_gst_business_type').val(res.gst_business_type || '');
        $('#add_gst_verified_at').val(res.gst_verified_at || '');
      } else {
        $('#edit_gst_status_val').val(res.gst_status || '');
        $('#edit_gst_state_val').val(res.gst_state || '');
        $('#edit_gst_pan_val').val(res.gst_pan || '');
        $('#edit_gst_registration_date').val(res.gst_registration_date || '');
        $('#edit_gst_business_type').val(res.gst_business_type || '');
        $('#edit_gst_verified_at').val(res.gst_verified_at || '');
      }
    }

    $(document).on('click', '.gst-verify-btn', function () {
      var $btn   = $(this);
      var prefix = $btn.data('prefix');
      var gstin  = $($btn.data('input')).val().trim().toUpperCase();
      var $result = $('#' + prefix + '_gst_result');

      if (!gstin) { $result.html('<small class="text-muted">Enter a GSTIN first.</small>'); return; }
      if (gstin.length !== 15) {
        $result.html('<small class="text-danger"><i class="fas fa-times-circle mr-1"></i>GSTIN must be 15 characters.</small>');
        return;
      }

      $btn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Verifying...').prop('disabled', true);
      $result.html('');

      var companyId = prefix === 'edit' ? $('#edit_company_id').val() : '';

      $.getJSON(gstVerifyUrl, { gstin: gstin, company_id: companyId }, function (res) {
        $btn.html('<i class="fas fa-search-plus mr-1"></i> Verify').prop('disabled', false);

        if (res.valid) {
          var src = res.source === 'api' ? ' <span class="text-info">(via API)</span>' : ' <span class="text-muted">(local validation)</span>';
          $result.html('<small class="text-success"><i class="fas fa-check-circle mr-1"></i>' + res.message + src + '</small>');
          populateGstPanel(prefix, res);
          if (prefix === 'edit') $('#editSaveBtn').prop('disabled', false);
        } else {
          $result.html('<small class="text-danger"><i class="fas fa-times-circle mr-1"></i>' + res.message + '</small>');
          $('#' + prefix + '_gst_panel').addClass('d-none');
        }
      }).fail(function () {
        $btn.html('<i class="fas fa-search-plus mr-1"></i> Verify').prop('disabled', false);
        $result.html('<small class="text-danger">Verification request failed. Try again.</small>');
      });
    });

    // Clear GST panel when GSTIN input is cleared
    $(document).on('input', '#gst_no', function () {
      if (!$(this).val()) { $('#add_gst_panel').addClass('d-none'); $('#add_gst_result').html(''); }
    });
    $(document).on('input', '#edit_gst_no', function () {
      if (!$(this).val()) { $('#edit_gst_panel').addClass('d-none'); $('#edit_gst_result').html(''); }
    });

    // ── Add Company ───────────────────────────────────────────────────
    $('#addCompanyForm').validate({
      rules: { company_name: { required: true, maxlength: 255 } },
      messages: { company_name: { required: 'Please enter a company name.' } },
      submitHandler: function (form, e) {
        e.preventDefault();
        var $btn = $(form).closest('.modal-content').find('button[type="submit"]');
        var orig = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
        $.ajax({
          url: '{{ route('companies.store') }}', type: 'POST', data: $(form).serialize(),
          success: function (res) {
            $btn.html(orig).prop('disabled', false);
            if (res.success) {
              Toast.fire({ icon: 'success', title: res.message });
              $('#add-module-popup').modal('hide');
              form.reset();
              $('#add_gst_panel').addClass('d-none');
              $('#add_gst_result').html('');
              $('#companies-table').DataTable().ajax.reload();
            }
          },
          error: function (xhr) { $btn.html(orig).prop('disabled', false); showError(parseErrors(xhr)); }
        });
      }
    });

    // ── Populate Edit Form ────────────────────────────────────────────
    $(document).on('click', '.edit-btn', function () {
      var id = $(this).data('id');
      $('#editCompanyForm')[0].reset();
      $('#editSaveBtn').prop('disabled', true);
      $('#editCompanyForm').data('initial-state', '');
      $('#edit_gst_panel').addClass('d-none');
      $('#edit_gst_result').html('');

      $.ajax({
        url: window.APP_URL + '/master/companies/' + id + '/edit', type: 'GET',
        success: function (res) {
          if (!res.success) return;
          var d = res.data;
          $('#edit_company_id').val(d.id);
          $('#edit_company_name').val(d.company_name);
          $('#edit_plant_name').val(d.plant_name);
          $('#edit_contact_person').val(d.contact_person);
          $('#edit_designation_id').val(d.designation_id || '');
          $('#edit_contact_phone').val(d.contact_phone);
          $('#edit_address').val(d.address);
          $('#edit_remark').val(d.remark);
          $('#edit_is_active').prop('checked', !!d.is_active);
          $('#edit_gst_no').val(d.gst_no || '');

          // Restore hidden GST fields
          $('#edit_gst_trade_name').val(d.gst_trade_name || '');
          $('#edit_gst_legal_name').val(d.gst_legal_name || '');
          $('#edit_gst_status_val').val(d.gst_status || '');
          $('#edit_gst_state_val').val(d.gst_state || '');
          $('#edit_gst_pan_val').val(d.gst_pan || '');
          $('#edit_gst_registration_date').val(d.gst_registration_date || '');
          $('#edit_gst_business_type').val(d.gst_business_type || '');
          $('#edit_gst_verified_at').val(d.gst_verified_at || '');

          // Show verified panel if GST already saved
          if (d.gst_no && d.gst_verified_at) {
            populateGstPanel('edit', {
              valid: true,
              gst_state: d.gst_state, gst_pan: d.gst_pan,
              gst_status: d.gst_status, gst_legal_name: d.gst_legal_name,
              gst_trade_name: d.gst_trade_name, gst_registration_date: d.gst_registration_date,
              gst_business_type: d.gst_business_type, gst_verified_at: d.gst_verified_at,
            });
          }

          $('#editCompanyForm').data('initial-state', $('#editCompanyForm').serialize());
        },
        error: function () { Swal.fire('Error', 'Failed to fetch data', 'error'); }
      });
    });

    // ── Enable Save on change ─────────────────────────────────────────
    $('#editCompanyForm').on('change input', function () {
      var cur = $(this).serialize(), init = $(this).data('initial-state');
      $('#editSaveBtn').prop('disabled', (cur === init || init === ''));
    });

    // ── Edit Company Submit ───────────────────────────────────────────
    $('#editCompanyForm').validate({
      rules: { company_name: { required: true, maxlength: 255 } },
      messages: { company_name: { required: 'Please enter a company name.' } },
      submitHandler: function (form, e) {
        e.preventDefault();
        var $btn = $('#editSaveBtn');
        var orig = $btn.html();
        var id   = $('#edit_company_id').val();
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
        $.ajax({
          url: window.APP_URL + '/master/companies/' + id, type: 'POST', data: $(form).serialize(),
          success: function (res) {
            $btn.html(orig).prop('disabled', true);
            if (res.success) {
              Toast.fire({ icon: 'success', title: res.message });
              $('#edit-module-popup').modal('hide');
              $(form).data('initial-state', $(form).serialize());
              $('#companies-table').DataTable().ajax.reload();
            }
          },
          error: function (xhr) { $btn.html(orig).prop('disabled', false); showError(parseErrors(xhr)); }
        });
      }
    });

    // ── Delete ────────────────────────────────────────────────────────
    $(document).on('click', '.delete-btn', function () {
      var id = $(this).data('id');
      Swal.fire({
        title: 'Are you sure?', text: "You won't be able to revert this!",
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash"></i> Yes, delete it!'
      }).then(function (result) {
        if (!result.isConfirmed) return;
        $.ajax({
          url: window.APP_URL + '/master/companies/' + id, type: 'DELETE',
          data: { _token: '{{ csrf_token() }}' },
          success: function (res) {
            if (res.success) { Toast.fire({ icon: 'success', title: res.message }); $('#companies-table').DataTable().ajax.reload(); }
          },
          error: function () { Swal.fire('Error!', 'Something went wrong.', 'error'); }
        });
      });
    });

    // ── Manage Designations ───────────────────────────────────────────
    var desigUrl   = '{{ route("designations.index") }}';
    var desigStore = '{{ route("designations.store") }}';
    var csrfToken  = '{{ csrf_token() }}';

    function refreshDesignationDropdowns(list) {
      // Rebuild both Add and Edit dropdowns
      var opts = '<option value="">— Select Designation —</option>';
      $.each(list, function (i, d) {
        opts += '<option value="' + d.id + '">' + $('<div>').text(d.name).html() + '</option>';
      });
      $('#designation_id').html(opts);
      $('#edit_designation_id').html(opts);
    }

    function loadDesignations() {
      $.getJSON(desigUrl, function (res) {
        if (!res.success) return;
        var list = res.data;

        // Rebuild table
        var rows = '';
        if (list.length === 0) {
          rows = '<tr><td colspan="3" class="text-center text-muted py-3">No designations found. Add one above.</td></tr>';
        } else {
          $.each(list, function (i, d) {
            rows += '<tr>'
              + '<td>' + (i + 1) + '</td>'
              + '<td>' + $('<div>').text(d.name).html() + '</td>'
              + '<td class="text-center">'
              +   '<button class="btn btn-danger btn-xs delete-desig-btn" data-id="' + d.id + '" title="Delete">'
              +     '<i class="fas fa-trash"></i>'
              +   '</button>'
              + '</td>'
              + '</tr>';
          });
        }
        $('#designations-list-body').html(rows);

        // Sync dropdowns
        refreshDesignationDropdowns(list);
      });
    }

    // Load list when modal opens
    $('#manage-designations-popup').on('show.bs.modal', function () {
      loadDesignations();
      $('#new_designation_name').val('');
      $('#designation-form-error').addClass('d-none').text('');
    });

    // Add designation
    $('#add-designation-btn').on('click', function () {
      var name = $.trim($('#new_designation_name').val());
      if (!name) {
        $('#designation-form-error').removeClass('d-none').text('Please enter a designation name.');
        return;
      }
      var $btn = $(this);
      $btn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
      $.ajax({
        url: desigStore, type: 'POST',
        data: { name: name, _token: csrfToken },
        success: function (res) {
          $btn.html('<i class="fas fa-plus"></i> Add').prop('disabled', false);
          if (res.success) {
            $('#new_designation_name').val('');
            $('#designation-form-error').addClass('d-none').text('');
            Toast.fire({ icon: 'success', title: res.message });
            loadDesignations();
          }
        },
        error: function (xhr) {
          $btn.html('<i class="fas fa-plus"></i> Add').prop('disabled', false);
          var msg = xhr.responseJSON?.errors?.name?.[0] || xhr.responseJSON?.message || 'Something went wrong.';
          $('#designation-form-error').removeClass('d-none').text(msg);
        }
      });
    });

    // Allow Enter key on input
    $('#new_designation_name').on('keypress', function (e) {
      if (e.which === 13) $('#add-designation-btn').trigger('click');
    });

    // Delete designation
    $(document).on('click', '.delete-desig-btn', function () {
      var id   = $(this).data('id');
      var $row = $(this).closest('tr');
      Swal.fire({
        title: 'Delete Designation?',
        text: 'Companies using this designation will be unlinked.',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash"></i> Yes, delete!'
      }).then(function (result) {
        if (!result.isConfirmed) return;
        $.ajax({
          url: window.APP_URL + '/master/designations/' + id, type: 'DELETE',
          data: { _token: csrfToken },
          success: function (res) {
            if (res.success) {
              Toast.fire({ icon: 'success', title: res.message });
              loadDesignations();
            }
          },
          error: function () { Swal.fire('Error!', 'Something went wrong.', 'error'); }
        });
      });
    });

  });
  </script>
@endpush
