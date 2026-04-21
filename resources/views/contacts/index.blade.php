@extends('layouts.app')

@section('title', config('app.name') . ' | Contacts')

@push('styles')
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <style>
    .phone-row { background: #f8f9fa; border-radius: 6px; padding: 6px 8px; margin-bottom: 6px; }
    .phone-row:last-child { margin-bottom: 0; }
    .primary-badge { font-size: 10px; }
  </style>
@endpush

@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0"><i class="fas fa-address-book mr-2"></i>Contacts</h1></div>
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
          <h3 class="card-title"><i class="fas fa-list mr-1"></i> Contact List</h3>
          <div class="card-tools">
            @can('create contacts')
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addContactModal">
              <i class="fas fa-plus mr-1"></i> Add Contact
            </button>
            @endcan
          </div>
        </div>

        {{-- Filter Bar --}}
        <div class="card-body border-bottom py-2">
          <div class="row align-items-end">
            <div class="col-md-4">
              <div class="form-group mb-0">
                <label class="mb-1"><small><i class="fas fa-tags mr-1 text-muted"></i>Category</small></label>
                <select id="filter_category" class="form-control form-control-sm">
                  <option value="">All Categories</option>
                  @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group mb-0">
                <label class="mb-1"><small><i class="fas fa-map-marker-alt mr-1 text-muted"></i>Area / Location</small></label>
                <input type="text" id="filter_area" class="form-control form-control-sm" placeholder="Search area…">
              </div>
            </div>
            <div class="col-md-4 d-flex align-items-end">
              <button type="button" class="btn btn-sm btn-primary mr-2" id="applyFilterBtn">
                <i class="fas fa-search mr-1"></i> Filter
              </button>
              <button type="button" class="btn btn-sm btn-outline-secondary" id="clearFilterBtn">
                <i class="fas fa-times mr-1"></i> Clear
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <table id="contacts-table" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th style="width:45px">#</th>
                <th>Name</th>
                <th>Category</th>
                <th>Area / Location</th>
                <th>Phone Numbers</th>
                <th style="width:90px">Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>

    </div>

    {{-- ========== ADD MODAL ========== --}}
    <div class="modal fade" id="addContactModal" data-backdrop="static" data-keyboard="false" tabindex="-1">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header bg-primary text-white">
            <h5 class="modal-title"><i class="fas fa-user-plus mr-1"></i> Add Contact</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form id="addContactForm" novalidate>
              @csrf

              {{-- Basic Info --}}
              <h6 class="text-primary font-weight-bold border-bottom pb-1 mb-3">
                <i class="fas fa-info-circle mr-1"></i> Basic Information
              </h6>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Person Name <span class="text-danger">*</span></label>
                    <input type="text" name="person_name" class="form-control" placeholder="Full name" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="d-flex justify-content-between">
                      Category / Type
                      <a href="#" class="small" data-dismiss="modal" data-toggle="modal" data-target="#manageCategoriesModal">
                        <i class="fas fa-cog mr-1"></i>Manage
                      </a>
                    </label>
                    <select name="contact_category_id" class="form-control">
                      <option value="">-- Select Category --</option>
                      @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Area / Location</label>
                    <input type="text" name="area" class="form-control" placeholder="e.g. Pune, MIDC Bhosari">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>UPI No / ID</label>
                    <input type="text" name="upi_no" class="form-control" placeholder="e.g. name@upi">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Remarks</label>
                    <input type="text" name="remarks" class="form-control" placeholder="Notes…">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Status</label>
                    <div class="custom-control custom-switch mt-2">
                      <input type="checkbox" name="is_active" class="custom-control-input" id="add_is_active" value="1" checked>
                      <label class="custom-control-label" for="add_is_active">Active</label>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Phone Numbers --}}
              <h6 class="text-primary font-weight-bold border-bottom pb-1 mb-3 mt-2">
                <i class="fas fa-phone mr-1"></i> Phone Numbers
                <small class="text-muted font-weight-normal ml-2">(First number is primary)</small>
              </h6>
              <div id="addPhoneRows">
                {{-- JS inserts rows here --}}
              </div>
              <button type="button" class="btn btn-sm btn-outline-success mt-1 add-phone-btn" data-target="addPhoneRows">
                <i class="fas fa-plus mr-1"></i> Add Number
              </button>

              {{-- Bank Details --}}
              <h6 class="text-primary font-weight-bold border-bottom pb-1 mb-3 mt-3">
                <i class="fas fa-university mr-1"></i> Bank Details
              </h6>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Account Holder Name</label>
                    <input type="text" name="account_holder_name" class="form-control" placeholder="As per bank records">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Account No</label>
                    <input type="text" name="account_no" class="form-control" placeholder="Account number">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>IFSC Code</label>
                    <input type="text" name="ifsc_code" class="form-control text-uppercase" placeholder="e.g. SBIN0001234">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" class="form-control" placeholder="e.g. SBI">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Branch</label>
                    <input type="text" name="branch" class="form-control" placeholder="Branch name">
                  </div>
                </div>
              </div>

            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="addSaveBtn">
              <i class="fas fa-save mr-1"></i> Save Contact
            </button>
          </div>
        </div>
      </div>
    </div>

    {{-- ========== EDIT MODAL ========== --}}
    <div class="modal fade" id="edit-module-popup" data-backdrop="static" data-keyboard="false" tabindex="-1">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title"><i class="fas fa-edit mr-1"></i> Edit Contact</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form id="editContactForm" novalidate>
              @csrf
              <input type="hidden" name="_method" value="PUT">
              <input type="hidden" name="id" id="edit_id">

              {{-- Basic Info --}}
              <h6 class="text-warning font-weight-bold border-bottom pb-1 mb-3">
                <i class="fas fa-info-circle mr-1"></i> Basic Information
              </h6>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Person Name <span class="text-danger">*</span></label>
                    <input type="text" name="person_name" id="edit_person_name" class="form-control" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Category / Type</label>
                    <select name="contact_category_id" id="edit_contact_category_id" class="form-control">
                      <option value="">-- Select Category --</option>
                      @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Area / Location</label>
                    <input type="text" name="area" id="edit_area" class="form-control" placeholder="e.g. Pune">
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
                    <input type="text" name="remarks" id="edit_remarks" class="form-control">
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

              {{-- Phone Numbers --}}
              <h6 class="text-warning font-weight-bold border-bottom pb-1 mb-3 mt-2">
                <i class="fas fa-phone mr-1"></i> Phone Numbers
                <small class="text-muted font-weight-normal ml-2">(First number is primary)</small>
              </h6>
              <div id="editPhoneRows">
                {{-- populated by JS --}}
              </div>
              <button type="button" class="btn btn-sm btn-outline-success mt-1 add-phone-btn" data-target="editPhoneRows">
                <i class="fas fa-plus mr-1"></i> Add Number
              </button>

              {{-- Bank Details --}}
              <h6 class="text-warning font-weight-bold border-bottom pb-1 mb-3 mt-3">
                <i class="fas fa-university mr-1"></i> Bank Details
              </h6>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Account Holder Name</label>
                    <input type="text" name="account_holder_name" id="edit_account_holder_name" class="form-control">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Account No</label>
                    <input type="text" name="account_no" id="edit_account_no" class="form-control">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>IFSC Code</label>
                    <input type="text" name="ifsc_code" id="edit_ifsc_code" class="form-control text-uppercase">
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
                    <label>Branch</label>
                    <input type="text" name="branch" id="edit_branch" class="form-control">
                  </div>
                </div>
              </div>

            </form>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-warning" id="editSaveBtn">
              <i class="fas fa-save mr-1"></i> Update Contact
            </button>
          </div>
        </div>
      </div>
    </div>

    {{-- ========== MANAGE CATEGORIES MODAL ========== --}}
    <div class="modal fade" id="manageCategoriesModal" data-keyboard="false" tabindex="-1">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header bg-info text-white">
            <h5 class="modal-title"><i class="fas fa-tags mr-1"></i> Manage Contact Categories</h5>
            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="input-group mb-3">
              <input type="text" id="newCategoryName" class="form-control" placeholder="New category name…" maxlength="100">
              <div class="input-group-append">
                <button class="btn btn-success" id="addCategoryBtn" type="button">
                  <i class="fas fa-plus mr-1"></i>Add
                </button>
              </div>
            </div>
            <div id="categoriesList">
              <div class="text-center text-muted py-3"><i class="fas fa-spinner fa-spin"></i> Loading…</div>
            </div>
          </div>
          <div class="modal-footer justify-content-between">
            <small class="text-muted">Changes apply immediately to all dropdowns.</small>
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

  </section>
@endsection

@push('scripts')
  <script src="{{ asset('public/adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

  <script>
  $(function () {

    // ── DataTable ────────────────────────────────────────────────────
    var table = $('#contacts-table').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      order: [[0, 'desc']],
      ajax: {
        url: '{{ route("contacts.index") }}',
        data: function (d) {
          d.category_id = $('#filter_category').val();
          d.area_filter = $('#filter_area').val();
        }
      },
      columns: [
        { data: 'DT_RowIndex',  name: 'DT_RowIndex',  orderable: false, searchable: false },
        { data: 'person_name',  name: 'person_name' },
        { data: 'category',     name: 'contact_category_id', orderable: false, searchable: false },
        { data: 'area',         name: 'area', orderable: false, searchable: false },
        { data: 'phones',       name: 'phones', orderable: false, searchable: false },
        { data: 'action',       name: 'action', orderable: false, searchable: false },
      ],
    });

    // ── Filter Handlers ──────────────────────────────────────────────
    $('#applyFilterBtn').on('click', function () {
      table.ajax.reload(null, false);
    });

    $('#clearFilterBtn').on('click', function () {
      $('#filter_category').val('');
      $('#filter_area').val('');
      table.ajax.reload(null, false);
    });

    // Also filter on Enter key in area input
    $('#filter_area').on('keypress', function (e) {
      if (e.which === 13) table.ajax.reload(null, false);
    });

    var csrfToken   = '{{ csrf_token() }}';
    var catIndexUrl = '{{ route("contact-categories.index") }}';
    var catStoreUrl = '{{ route("contact-categories.store") }}';

    var Toast = Swal.mixin({
      toast: true, position: 'top-end', showConfirmButton: false,
      timer: 4000, timerProgressBar: true,
    });

    // ── Phone Row Builder ────────────────────────────────────────────
    var phoneLabels = ['Mobile', 'WhatsApp', 'Office', 'Other'];

    function buildPhoneRow(number, label, index, containerId) {
      var isPrimary = index === 0;
      var labelOpts = phoneLabels.map(function (l) {
        return '<option value="' + l + '"' + (l === label ? ' selected' : '') + '>' + l + '</option>';
      }).join('');

      return '<div class="phone-row d-flex align-items-center gap-2">'
        + (isPrimary ? '<span class="badge badge-success primary-badge mr-2">Primary</span>' : '<span class="badge badge-light primary-badge mr-2">Alt</span>')
        + '<select name="phones[' + index + '][label]" class="form-control form-control-sm mr-2" style="width:110px">' + labelOpts + '</select>'
        + '<input type="text" name="phones[' + index + '][number]" class="form-control form-control-sm mr-2" placeholder="Phone number" value="' + (number || '') + '" maxlength="30" required>'
        + (isPrimary
          ? '<button type="button" class="btn btn-sm btn-outline-secondary disabled" disabled title="Primary number cannot be removed"><i class="fas fa-lock"></i></button>'
          : '<button type="button" class="btn btn-sm btn-outline-danger remove-phone-btn" title="Remove"><i class="fas fa-times"></i></button>')
        + '</div>';
    }

    function initPhoneRows(containerId, phones) {
      var $container = $('#' + containerId);
      $container.empty();
      if (!phones || !phones.length) {
        $container.html(buildPhoneRow('', 'Mobile', 0, containerId));
      } else {
        phones.forEach(function (p, i) {
          $container.append(buildPhoneRow(p.phone_number || p.number || '', p.label || 'Mobile', i, containerId));
        });
      }
    }

    function reindexPhoneRows(containerId) {
      $('#' + containerId + ' .phone-row').each(function (i) {
        var $row = $(this);
        // Update names
        $row.find('select').attr('name', 'phones[' + i + '][label]');
        $row.find('input').attr('name', 'phones[' + i + '][number]');
        // Update primary badge
        var $badge = $row.find('.primary-badge');
        if (i === 0) {
          $badge.removeClass('badge-light').addClass('badge-success').text('Primary');
          $row.find('.remove-phone-btn')
            .removeClass('remove-phone-btn btn-outline-danger')
            .addClass('disabled btn-outline-secondary')
            .attr('disabled', true)
            .html('<i class="fas fa-lock"></i>');
        }
      });
    }

    // Add phone row
    $(document).on('click', '.add-phone-btn', function () {
      var cid = $(this).data('target');
      var count = $('#' + cid + ' .phone-row').length;
      $('#' + cid).append(buildPhoneRow('', 'Mobile', count, cid));
    });

    // Remove phone row
    $(document).on('click', '.remove-phone-btn', function () {
      var cid = $(this).closest('.phone-row').parent().attr('id');
      $(this).closest('.phone-row').remove();
      reindexPhoneRows(cid);
    });

    // Initialize add form with one phone row
    initPhoneRows('addPhoneRows', []);

    $('#addContactModal').on('hidden.bs.modal', function () {
      $('#addContactForm')[0].reset();
      initPhoneRows('addPhoneRows', []);
    });

    // ── ADD Contact ──────────────────────────────────────────────────
    $('#addSaveBtn').on('click', function () {
      var form  = document.getElementById('addContactForm');
      var $form = $(form);

      // Validate required fields
      var name = $form.find('[name="person_name"]').val().trim();
      if (!name) {
        $form.find('[name="person_name"]').addClass('is-invalid').focus();
        return;
      }
      $form.find('[name="person_name"]').removeClass('is-invalid');

      var hasPhone = false;
      var phoneOk  = true;
      $form.find('[name^="phones["][name$="[number]"]').each(function () {
        var v = $(this).val().trim();
        if (v) { hasPhone = true; $(this).removeClass('is-invalid'); }
        else   { phoneOk = false; $(this).addClass('is-invalid'); }
      });

      if (!hasPhone || !phoneOk) {
        Toast.fire({ icon: 'warning', title: 'Please enter at least one phone number.' });
        return;
      }

      var btn = $(this);
      btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Saving...').prop('disabled', true);

      $.ajax({
        url: '{{ route("contacts.store") }}',
        method: 'POST',
        data: $form.serialize(),
        success: function (res) {
          btn.html('<i class="fas fa-save mr-1"></i> Save Contact').prop('disabled', false);
          if (res.success) {
            Toast.fire({ icon: 'success', title: res.message });
            $('#addContactModal').modal('hide');
            table.ajax.reload(null, false);
          }
        },
        error: function (xhr) {
          btn.html('<i class="fas fa-save mr-1"></i> Save Contact').prop('disabled', false);
          var errors = xhr.responseJSON?.errors;
          var msg = errors
            ? Object.values(errors).flat().join('<br>')
            : (xhr.responseJSON?.message || 'Something went wrong.');
          Swal.fire({ icon: 'error', title: 'Validation Error', html: msg });
        }
      });
    });

    // ── EDIT – populate form ──────────────────────────────────────────
    $(document).on('click', '.edit-btn', function () {
      var id = $(this).data('id');
      $('#editContactForm')[0].reset();
      $('#edit-module-popup').modal('show');
      $('#editSaveBtn').prop('disabled', true);

      $.ajax({
        url: window.APP_URL + '/master/contacts/' + id + '/edit',
        success: function (res) {
          if (!res.success) return;
          var d = res.data;
          $('#edit_id').val(d.id);
          $('#edit_person_name').val(d.person_name);
          $('#edit_contact_category_id').val(d.contact_category_id);
          $('#edit_area').val(d.area);
          $('#edit_upi_no').val(d.upi_no);
          $('#edit_remarks').val(d.remarks);
          $('#edit_is_active').prop('checked', !!d.is_active);
          $('#edit_account_holder_name').val(d.account_holder_name);
          $('#edit_account_no').val(d.account_no);
          $('#edit_ifsc_code').val(d.ifsc_code);
          $('#edit_bank_name').val(d.bank_name);
          $('#edit_branch').val(d.branch);

          // Populate phones
          var phones = d.phones && d.phones.length ? d.phones : [];
          initPhoneRows('editPhoneRows', phones);

          $('#editSaveBtn').prop('disabled', false);
        },
        error: function () { Swal.fire('Error', 'Failed to load contact data.', 'error'); }
      });
    });

    // ── UPDATE Contact ───────────────────────────────────────────────
    $('#editSaveBtn').on('click', function () {
      var form  = document.getElementById('editContactForm');
      var $form = $(form);
      var id    = $('#edit_id').val();

      var name = $form.find('[name="person_name"]').val().trim();
      if (!name) {
        $form.find('[name="person_name"]').addClass('is-invalid').focus();
        return;
      }
      $form.find('[name="person_name"]').removeClass('is-invalid');

      var hasPhone = false;
      var phoneOk  = true;
      $form.find('[name^="phones["][name$="[number]"]').each(function () {
        var v = $(this).val().trim();
        if (v) { hasPhone = true; $(this).removeClass('is-invalid'); }
        else   { phoneOk = false; $(this).addClass('is-invalid'); }
      });

      if (!hasPhone || !phoneOk) {
        Toast.fire({ icon: 'warning', title: 'Please enter at least one phone number.' });
        return;
      }

      var btn = $(this);
      btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Updating...').prop('disabled', true);

      $.ajax({
        url: window.APP_URL + '/master/contacts/' + id,
        method: 'POST',
        data: $form.serialize(),
        success: function (res) {
          btn.html('<i class="fas fa-save mr-1"></i> Update Contact').prop('disabled', false);
          if (res.success) {
            Toast.fire({ icon: 'success', title: res.message });
            $('#edit-module-popup').modal('hide');
            table.ajax.reload(null, false);
          }
        },
        error: function (xhr) {
          btn.html('<i class="fas fa-save mr-1"></i> Update Contact').prop('disabled', false);
          var errors = xhr.responseJSON?.errors;
          var msg = errors
            ? Object.values(errors).flat().join('<br>')
            : (xhr.responseJSON?.message || 'Something went wrong.');
          Swal.fire({ icon: 'error', title: 'Error', html: msg });
        }
      });
    });

    // ── DELETE ───────────────────────────────────────────────────────
    $(document).on('click', '.delete-btn', function () {
      var id = $(this).data('id');
      Swal.fire({
        title: 'Delete this contact?', text: 'All phone numbers will also be deleted.',
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc3545', confirmButtonText: 'Yes, delete'
      }).then(function (result) {
        if (!result.isConfirmed) return;
        $.ajax({
          url: window.APP_URL + '/master/contacts/' + id, method: 'DELETE',
          data: { _token: csrfToken },
          success: function (res) {
            if (res.success) {
              Toast.fire({ icon: 'success', title: res.message });
              table.ajax.reload(null, false);
            }
          },
          error: function () { Swal.fire('Error!', 'Something went wrong.', 'error'); }
        });
      });
    });

    // ── CATEGORY MANAGEMENT ──────────────────────────────────────────
    function renderCategories(cats) {
      if (!cats.length) {
        $('#categoriesList').html('<p class="text-muted text-center py-2">No categories yet. Add one above.</p>');
        return;
      }
      var html = '<ul class="list-group list-group-flush">';
      cats.forEach(function (c) {
        html += '<li class="list-group-item d-flex justify-content-between align-items-center py-2">'
          + '<span><i class="fas fa-tag text-info mr-2"></i><span class="cat-name-text">' + $('<div>').text(c.name).html() + '</span></span>'
          + '<span>'
          + '<button class="btn btn-xs btn-warning mr-1 cat-edit-btn" data-id="' + c.id + '" data-name="' + $('<div>').text(c.name).html() + '"><i class="fas fa-edit"></i></button>'
          + '<button class="btn btn-xs btn-danger cat-delete-btn" data-id="' + c.id + '"><i class="fas fa-trash"></i></button>'
          + '</span></li>';
      });
      html += '</ul>';
      $('#categoriesList').html(html);
    }

    function loadCategories() {
      $.getJSON(catIndexUrl, function (res) { renderCategories(res.data); });
    }

    function refreshCategoryDropdowns(cats) {
      var opts = '<option value="">-- Select Category --</option>';
      cats.filter(function (c) { return c.is_active; }).forEach(function (c) {
        opts += '<option value="' + c.id + '">' + $('<div>').text(c.name).html() + '</option>';
      });
      $('select[name="contact_category_id"]').html(opts);
    }

    $('#manageCategoriesModal').on('show.bs.modal', loadCategories);

    $('#addCategoryBtn').on('click', function () {
      var name = $.trim($('#newCategoryName').val());
      if (!name) { $('#newCategoryName').focus(); return; }
      var btn = $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
      $.ajax({
        url: catStoreUrl, method: 'POST',
        data: { _token: csrfToken, name: name },
        success: function (res) {
          btn.prop('disabled', false).html('<i class="fas fa-plus mr-1"></i>Add');
          if (res.success) {
            $('#newCategoryName').val('');
            loadCategories();
            $.getJSON(catIndexUrl, function (r) { refreshCategoryDropdowns(r.data); });
            Toast.fire({ icon: 'success', title: res.message });
          }
        },
        error: function (xhr) {
          btn.prop('disabled', false).html('<i class="fas fa-plus mr-1"></i>Add');
          Toast.fire({ icon: 'error', title: xhr.responseJSON?.errors?.name?.[0] ?? 'Error.' });
        }
      });
    });

    $('#newCategoryName').on('keypress', function (e) {
      if (e.which === 13) $('#addCategoryBtn').trigger('click');
    });

    $(document).on('click', '.cat-edit-btn', function () {
      var id = $(this).data('id'), name = $(this).data('name'), $li = $(this).closest('li');
      $li.find('.cat-name-text').html(
        '<input type="text" class="form-control form-control-sm d-inline-block cat-rename-input" style="width:170px" value="' + name + '" maxlength="100">'
        + ' <button class="btn btn-xs btn-success cat-save-btn" data-id="' + id + '"><i class="fas fa-check"></i></button>'
        + ' <button class="btn btn-xs btn-secondary cat-cancel-btn"><i class="fas fa-times"></i></button>'
      );
      $li.find('.cat-rename-input').focus().select();
    });

    $(document).on('click', '.cat-cancel-btn', loadCategories);

    $(document).on('click', '.cat-save-btn', function () {
      var id = $(this).data('id');
      var name = $.trim($(this).closest('li').find('.cat-rename-input').val());
      if (!name) return;
      $.ajax({
        url: window.APP_URL + '/master/contact-categories/' + id, method: 'POST',
        data: { _token: csrfToken, _method: 'PUT', name: name, is_active: 1 },
        success: function (res) {
          if (res.success) {
            loadCategories();
            $.getJSON(catIndexUrl, function (r) { refreshCategoryDropdowns(r.data); });
            Toast.fire({ icon: 'success', title: res.message });
          }
        },
        error: function (xhr) {
          Toast.fire({ icon: 'error', title: xhr.responseJSON?.errors?.name?.[0] ?? 'Update failed.' });
        }
      });
    });

    $(document).on('click', '.cat-delete-btn', function () {
      var id = $(this).data('id');
      Swal.fire({ title: 'Delete category?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545', confirmButtonText: 'Yes, delete' })
        .then(function (r) {
          if (!r.isConfirmed) return;
          $.ajax({
            url: window.APP_URL + '/master/contact-categories/' + id, method: 'POST',
            data: { _token: csrfToken, _method: 'DELETE' },
            success: function (res) {
              if (res.success) {
                loadCategories();
                $.getJSON(catIndexUrl, function (r) { refreshCategoryDropdowns(r.data); });
                Toast.fire({ icon: 'success', title: res.message });
              } else {
                Swal.fire({ icon: 'warning', title: 'Cannot Delete', text: res.message });
              }
            },
            error: function (xhr) {
              Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message ?? 'Delete failed.' });
            }
          });
        });
    });

  });
  </script>
@endpush
