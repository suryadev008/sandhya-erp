@extends('layouts.app')

@section('title', config('app.name') . ' | Our Vendors')

@push('styles')
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
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
        <div class="col-sm-6"><h1 class="m-0">Our Vendors</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Our Vendors</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Vendor List</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#add-module-popup">
              <i class="fas fa-plus"></i> Add Vendor
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="companies-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Vendor Name</th>
                <th>Plant Name</th>
                <th>Contact Person</th>
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
            <h4 class="modal-title">Add Vendor</h4>
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
                    <label>Vendor Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" id="company_name" class="form-control" placeholder="Enter Vendor Name">
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
            <h4 class="modal-title">Edit Vendor</h4>
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
                    <label>Vendor Name <span class="text-danger">*</span></label>
                    <input type="text" name="company_name" id="edit_company_name" class="form-control" placeholder="Enter Vendor Name">
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

    // ── DataTable ──────────────────────────────────────────────────────
    $('#companies-table').DataTable({
      processing: true, serverSide: true, responsive: true, order: [],
      ajax: {
        url: '{{ route('companies.index') }}',
        data: function (d) { d.status = $('#status-filter').val(); }
      },
      columns: [
        { data: 'DT_RowIndex',    name: 'DT_RowIndex',    orderable: false, searchable: false },
        { data: 'company_name',   name: 'company_name' },
        { data: 'plant_name',     name: 'plant_name' },
        { data: 'contact_person', name: 'contact_person' },
        { data: 'contact_phone',  name: 'contact_phone' },
        { data: 'is_active',      name: 'is_active' },
        { data: 'action',         name: 'action', orderable: false, searchable: false },
      ],
      initComplete: function () {
        var filterHtml = '<span class="d-inline-block ml-3"><label>Status: <select id="status-filter" class="custom-select custom-select-sm form-control form-control-sm"><option value="">All</option><option value="1">Active</option><option value="0">Inactive</option></select></label></span>';
        $('#companies-table_length').css('display', 'inline-block').after(filterHtml);
        $('#status-filter').on('change', function () { $('#companies-table').DataTable().ajax.reload(); });
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
      messages: { company_name: { required: 'Please enter a vendor name.' } },
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
        url: '/master/companies/' + id + '/edit', type: 'GET',
        success: function (res) {
          if (!res.success) return;
          var d = res.data;
          $('#edit_company_id').val(d.id);
          $('#edit_company_name').val(d.company_name);
          $('#edit_plant_name').val(d.plant_name);
          $('#edit_contact_person').val(d.contact_person);
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
      messages: { company_name: { required: 'Please enter a vendor name.' } },
      submitHandler: function (form, e) {
        e.preventDefault();
        var $btn = $('#editSaveBtn');
        var orig = $btn.html();
        var id   = $('#edit_company_id').val();
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
        $.ajax({
          url: '/master/companies/' + id, type: 'POST', data: $(form).serialize(),
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
          url: '/master/companies/' + id, type: 'DELETE',
          data: { _token: '{{ csrf_token() }}' },
          success: function (res) {
            if (res.success) { Toast.fire({ icon: 'success', title: res.message }); $('#companies-table').DataTable().ajax.reload(); }
          },
          error: function () { Swal.fire('Error!', 'Something went wrong.', 'error'); }
        });
      });
    });

  });
  </script>
@endpush
