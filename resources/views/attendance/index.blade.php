@extends('layouts.app')

@section('title', config('app.name') . ' | Attendance Report')

@push('styles')
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet"
    href="{{ asset('public/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet"
    href="{{ asset('public/adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
  <style>
    .filter-card .form-group {
      margin-bottom: 0;
    }

    @media print {
      .no-print {
        display: none !important;
      }

      .card {
        border: 1px solid #ddd !important;
      }

      .content-header,
      .main-header,
      .main-sidebar,
      .main-footer {
        display: none !important;
      }

      .content-wrapper {
        margin: 0 !important;
      }
    }

    .badge-lathe {
      background: #007bff;
      color: #fff;
    }

    .badge-cnc {
      background: #17a2b8;
      color: #fff;
    }

    .badge-both {
      background: #6f42c1;
      color: #fff;
    }

    .half-day-row {
      background: #fff8e1 !important;
    }

    .downtime-row {
      background: #fff3f3 !important;
    }
  </style>
@endpush

@section('content')

  <div class="content-header no-print">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><i class="fas fa-calendar-alt mr-2"></i>Attendance Report</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Payroll</li>
            <li class="breadcrumb-item active">Attendance</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      {{-- ── Filter Card ─────────────────────────────────────────────── --}}
      <div class="card card-outline card-secondary filter-card mb-3 no-print">
        <div class="card-body py-2">
          <form method="GET" action="{{ route('attendance.index') }}" id="filterForm">
            <div class="row align-items-end">

              {{-- Date Range --}}
              <div class="col-md-3">
                <div class="form-group">
                  <label class="mb-1"><small><i class="fas fa-calendar mr-1"></i>Date Range</small></label>
                  <div class="input-group input-group-sm">
                    <input type="date" name="from_date" id="from_date" class="form-control"
                      value="{{ $fromDate->toDateString() }}" max="{{ now()->toDateString() }}">
                    <div class="input-group-prepend input-group-append">
                      <span class="input-group-text">to</span>
                    </div>
                    <input type="date" name="to_date" id="to_date" class="form-control"
                      value="{{ $toDate->toDateString() }}" max="{{ now()->toDateString() }}">
                  </div>
                </div>
              </div>

              {{-- Employee --}}
              <div class="col-md-3">
                <div class="form-group">
                  <label class="mb-1"><small><i class="fas fa-user mr-1"></i>Employee</small></label>
                  <select name="employee_id" id="filter_employee" class="form-control form-control-sm select2-filter">
                    <option value="">-- All Employees --</option>
                    @foreach($employees as $emp)
                      <option value="{{ $emp->id }}" {{ $employeeId == $emp->id ? 'selected' : '' }}>
                        {{ $emp->emp_code }} – {{ $emp->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              {{-- Shift --}}
              <div class="col-md-2">
                <div class="form-group">
                  <label class="mb-1"><small><i class="fas fa-clock mr-1"></i>Shift</small></label>
                  <select name="shift" class="form-control form-control-sm">
                    <option value="">All Shifts</option>
                    <option value="day" {{ $shift == 'day' ? 'selected' : '' }}>Day</option>
                    <option value="night" {{ $shift == 'night' ? 'selected' : '' }}>Night</option>
                    <option value="A" {{ $shift == 'A' ? 'selected' : '' }}>A</option>
                    <option value="B" {{ $shift == 'B' ? 'selected' : '' }}>B</option>
                    <option value="general" {{ $shift == 'general' ? 'selected' : '' }}>General</option>
                  </select>
                </div>
              </div>

              {{-- Machine Type --}}
              <div class="col-md-2">
                <div class="form-group">
                  <label class="mb-1"><small><i class="fas fa-cogs mr-1"></i>Machine Type</small></label>
                  <select name="machine_type" class="form-control form-control-sm">
                    <option value="all" {{ $machineType == 'all' ? 'selected' : '' }}>All (Lathe + CNC)</option>
                    <option value="lathe" {{ $machineType == 'lathe' ? 'selected' : '' }}>Lathe Only</option>
                    <option value="cnc" {{ $machineType == 'cnc' ? 'selected' : '' }}>CNC Only</option>
                  </select>
                </div>
              </div>

              {{-- View Mode + Buttons --}}
              <div class="col-md-2 d-flex flex-column">
                <label class="mb-1"><small><i class="fas fa-table mr-1"></i>View</small></label>
                <div class="btn-group btn-group-sm mb-1" role="group">
                  <button type="submit" name="view_mode" value="summary"
                    class="btn {{ $viewMode === 'summary' ? 'btn-primary' : 'btn-outline-primary' }}">
                    Summary
                  </button>
                  <button type="submit" name="view_mode" value="detail"
                    class="btn {{ $viewMode === 'detail' ? 'btn-info' : 'btn-outline-info' }}">
                    Daily
                  </button>
                </div>
              </div>

            </div>

            {{-- Quick date range buttons --}}
            <div class="row mt-1">
              <div class="col">
                <small class="text-muted mr-2">Quick:</small>
                @php
                  $today = now()->toDateString();
                  $thisMonthFrom = now()->startOfMonth()->toDateString();
                  $thisMonthTo = now()->toDateString();
                  $lastMonthFrom = now()->subMonth()->startOfMonth()->toDateString();
                  $lastMonthTo = now()->subMonth()->endOfMonth()->toDateString();
                  $thisWeekFrom = now()->startOfWeek(\Carbon\Carbon::MONDAY)->toDateString();
                @endphp
                <a href="{{ route('attendance.index', array_merge(request()->except(['from_date', 'to_date']), ['from_date' => $thisMonthFrom, 'to_date' => $thisMonthTo, 'view_mode' => $viewMode])) }}"
                  class="btn btn-xs btn-outline-secondary mr-1">This Month</a>
                <a href="{{ route('attendance.index', array_merge(request()->except(['from_date', 'to_date']), ['from_date' => $lastMonthFrom, 'to_date' => $lastMonthTo, 'view_mode' => $viewMode])) }}"
                  class="btn btn-xs btn-outline-secondary mr-1">Last Month</a>
                <a href="{{ route('attendance.index', array_merge(request()->except(['from_date', 'to_date']), ['from_date' => $thisWeekFrom, 'to_date' => $today, 'view_mode' => $viewMode])) }}"
                  class="btn btn-xs btn-outline-secondary mr-1">This Week</a>
                <a href="{{ route('attendance.index', array_merge(request()->except(['from_date', 'to_date']), ['from_date' => $today, 'to_date' => $today, 'view_mode' => $viewMode])) }}"
                  class="btn btn-xs btn-outline-secondary mr-1">Today</a>
                <a href="{{ route('attendance.index') }}" class="btn btn-xs btn-outline-danger mr-1">
                  <i class="fas fa-times mr-1"></i>Clear
                </a>
                <button type="button" onclick="window.print()" class="btn btn-xs btn-outline-dark float-right">
                  <i class="fas fa-print mr-1"></i>Print
                </button>
              </div>
            </div>

          </form>
        </div>
      </div>

      {{-- ── Summary Info Boxes ──────────────────────────────────────── --}}
      <div class="row mb-3">
        <div class="col-md-2 col-sm-4">
          <div class="info-box bg-info">
            <span class="info-box-icon"><i class="fas fa-calendar-check"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Present</span>
              <span class="info-box-number">{{ $totalPresent }}</span>
              <span class="info-box-text" style="font-size:11px;">attendance entries</span>
            </div>
          </div>
        </div>
        <div class="col-md-2 col-sm-4">
          <div class="info-box bg-success">
            <span class="info-box-icon"><i class="fas fa-sun"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Full Days</span>
              <span class="info-box-number">{{ $totalFull }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-2 col-sm-4">
          <div class="info-box bg-warning">
            <span class="info-box-icon"><i class="fas fa-adjust"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Half Days</span>
              <span class="info-box-number">{{ $totalHalf }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-2 col-sm-4">
          <div class="info-box bg-danger">
            <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Downtime Days</span>
              <span class="info-box-number">{{ $totalDowntime }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-2 col-sm-4">
          <div class="info-box bg-primary">
            <span class="info-box-icon"><i class="fas fa-boxes"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Pieces</span>
              <span class="info-box-number">{{ number_format($totalQty) }}</span>
            </div>
          </div>
        </div>
        <div class="col-md-2 col-sm-4">
          <div class="info-box" style="background:#6f42c1;color:#fff">
            <span class="info-box-icon"><i class="fas fa-rupee-sign"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Amount</span>
              <span class="info-box-number" style="font-size:1.1rem">₹ {{ number_format($totalAmount, 2) }}</span>
            </div>
          </div>
        </div>
      </div>

      {{-- Print header (only visible on print) --}}
      <div class="d-none d-print-block mb-3">
        <h4 class="text-center">Attendance Report — {{ $fromDate->format('d M Y') }} to {{ $toDate->format('d M Y') }}
        </h4>
        @if($employeeId)
          <p class="text-center text-muted">Employee: {{ $employees->firstWhere('id', $employeeId)?->name }}</p>
        @endif
      </div>

      {{-- ── Report Table ─────────────────────────────────────────────── --}}
      @if($viewMode === 'summary')
        {{-- SUMMARY VIEW --}}
        <div class="card card-primary card-outline">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title mb-0">
              <i class="fas fa-users mr-1"></i>
              Employee Summary
              <small class="text-muted ml-1">{{ $fromDate->format('d M Y') }} – {{ $toDate->format('d M Y') }}</small>
            </h3>
            <span class="badge badge-primary ml-2 p-2">{{ count($summaryRecords) }} employee(s)</span>
          </div>
          <div class="card-body p-0">
            @if(empty($summaryRecords))
              <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>No attendance data for selected filters.
              </div>
            @else
              <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover mb-0" id="summaryTable">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>Emp Code</th>
                      <th>Name</th>
                      <th class="text-center">Total Days</th>
                      <th class="text-center">Full Days</th>
                      <th class="text-center">Half Days</th>
                      <th class="text-center">Downtime Days</th>
                      <th class="text-center">Lathe Days</th>
                      <th class="text-center">CNC Days</th>
                      <th class="text-right">Total Pieces</th>
                      <th class="text-right">Total Amount (₹)</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($summaryRecords as $i => $s)
                      <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                          <a href="{{ route('attendance.index', array_merge(request()->query(), ['employee_id' => $s['employee_id'], 'view_mode' => 'detail'])) }}"
                            class="font-weight-bold">{{ $s['emp_code'] }}</a>
                        </td>
                        <td>
                          <a
                            href="{{ route('attendance.index', array_merge(request()->query(), ['employee_id' => $s['employee_id'], 'view_mode' => 'detail'])) }}">
                            {{ $s['employee_name'] }}
                          </a>
                        </td>
                        <td class="text-center font-weight-bold">{{ $s['total_days'] }}</td>
                        <td class="text-center text-success">{{ $s['full_days'] }}</td>
                        <td class="text-center text-warning font-weight-bold">
                          {{ $s['half_days'] > 0 ? $s['half_days'] : '—' }}
                        </td>
                        <td class="text-center text-danger">
                          {{ $s['downtime_days'] > 0 ? $s['downtime_days'] : '—' }}
                        </td>
                        <td class="text-center">
                          @if($s['lathe_days'] > 0)
                            <span class="badge badge-lathe">{{ $s['lathe_days'] }}</span>
                          @else —
                          @endif
                        </td>
                        <td class="text-center">
                          @if($s['cnc_days'] > 0)
                            <span class="badge badge-cnc">{{ $s['cnc_days'] }}</span>
                          @else —
                          @endif
                        </td>
                        <td class="text-right">{{ number_format($s['total_qty']) }}</td>
                        <td class="text-right font-weight-bold text-primary">₹ {{ number_format($s['total_amount'], 2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr class="table-secondary font-weight-bold">
                      <td colspan="3" class="text-right pr-3">Totals:</td>
                      <td class="text-center">{{ $totalPresent }}</td>
                      <td class="text-center text-success">{{ $totalFull }}</td>
                      <td class="text-center text-warning">{{ $totalHalf > 0 ? $totalHalf : '—' }}</td>
                      <td class="text-center text-danger">{{ $totalDowntime > 0 ? $totalDowntime : '—' }}</td>
                      <td colspan="2"></td>
                      <td class="text-right">{{ number_format($totalQty) }}</td>
                      <td class="text-right text-primary">₹ {{ number_format($totalAmount, 2) }}</td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            @endif
          </div>
        </div>

      @else
        {{-- DAILY DETAIL VIEW --}}
        <div class="card card-info card-outline">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title mb-0">
              <i class="fas fa-calendar-day mr-1"></i>
              Daily Attendance Detail
              <small class="text-muted ml-1">{{ $fromDate->format('d M Y') }} – {{ $toDate->format('d M Y') }}</small>
            </h3>
            <span class="badge badge-info ml-2 p-2">{{ count($dailyRecords) }} record(s)</span>
            <div class="ml-auto no-print">
              <span class="badge badge-warning p-1 mr-1"><i class="fas fa-square mr-1"></i>Half Day</span>
              <span class="badge badge-danger p-1"><i class="fas fa-square mr-1"></i>Downtime</span>
            </div>
          </div>
          <div class="card-body p-0">
            @if(empty($dailyRecords))
              <div class="text-center py-5 text-muted">
                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>No attendance data for selected filters.
              </div>
            @else
              <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover mb-0" id="detailTable">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>Date</th>
                      <th>Day</th>
                      <th>Emp Code</th>
                      <th>Name</th>
                      <th class="text-center">Shift(s)</th>
                      <th class="text-center">Machine</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">Downtime</th>
                      <th class="text-right">Total Pieces</th>
                      <th class="text-right">Amount (₹)</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($dailyRecords as $i => $rec)
                      @php
                        $rowClass = $rec['is_half_day'] ? 'half-day-row' : ($rec['has_downtime'] ? 'downtime-row' : '');
                        $dayOfWeek = \Carbon\Carbon::parse($rec['date'])->format('D');
                        $isSunday = \Carbon\Carbon::parse($rec['date'])->isSunday();
                      @endphp
                      <tr class="{{ $rowClass }}">
                        <td>{{ $i + 1 }}</td>
                        <td class="text-nowrap font-weight-bold {{ $isSunday ? 'text-danger' : '' }}">
                          {{ \Carbon\Carbon::parse($rec['date'])->format('d M Y') }}
                        </td>
                        <td class="{{ $isSunday ? 'text-danger font-weight-bold' : 'text-muted' }}">{{ $dayOfWeek }}</td>
                        <td class="font-weight-bold">{{ $rec['emp_code'] }}</td>
                        <td>{{ $rec['employee_name'] }}</td>
                        <td class="text-center">
                          @foreach($rec['shifts'] as $sh)
                            <span class="badge badge-secondary">{{ ucfirst($sh) }}</span>
                          @endforeach
                        </td>
                        <td class="text-center">
                          @foreach($rec['machine_types'] as $mt)
                            <span class="badge badge-{{ strtolower($mt) }}">{{ $mt }}</span>
                          @endforeach
                        </td>
                        <td class="text-center">
                          @if($rec['is_half_day'])
                            <span class="badge badge-warning">Half Day</span>
                          @else
                            <span class="badge badge-success">Full Day</span>
                          @endif
                        </td>
                        <td class="text-center">
                          @if($rec['has_downtime'])
                            <span class="badge badge-danger">
                              <i class="fas fa-exclamation-triangle mr-1"></i>
                              {{ $rec['downtime_minutes'] }}m
                            </span>
                          @else
                            <span class="text-muted">—</span>
                          @endif
                        </td>
                        <td class="text-right">{{ number_format($rec['total_qty']) }}</td>
                        <td class="text-right font-weight-bold text-primary">₹ {{ number_format($rec['total_amount'], 2) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr class="table-secondary font-weight-bold">
                      <td colspan="9" class="text-right pr-3">Totals:</td>
                      <td class="text-right">{{ number_format($totalQty) }}</td>
                      <td class="text-right text-primary">₹ {{ number_format($totalAmount, 2) }}</td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            @endif
          </div>
        </div>
      @endif

    </div>
  </section>

@endsection

@push('scripts')
  <script src="{{ asset('public/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('public/adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
  <script>
    $(function () {

      $('.select2-filter').select2({ theme: 'bootstrap4', width: '100%' });

      @if($viewMode === 'summary' && count($summaryRecords) > 0)
        $('#summaryTable').DataTable({
          responsive: true,
          order: [[1, 'asc']],
          pageLength: 50,
          columnDefs: [{ orderable: false, targets: [] }],
        });
      @endif

      @if($viewMode === 'detail' && count($dailyRecords) > 0)
        $('#detailTable').DataTable({
          responsive: true,
          order: [[1, 'asc'], [3, 'asc']],
          pageLength: 50,
        });
      @endif

      // Validate date range
      $('#filterForm').on('submit', function () {
        var from = new Date($('#from_date').val());
        var to = new Date($('#to_date').val());
        if (from > to) {
          alert('From date cannot be after To date.');
          return false;
        }
      });

    });
  </script>
@endpush