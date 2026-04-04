{{-- ============================================================
resources/views/dashboard.blade.php
============================================================ --}}

@extends('layouts.app')

@section('title', config('app.name', 'Sandhya ERP') . ' | Dashboard')

@push('styles')
  <style>
    .stat-label {
      font-size: 12px;
      color: #6c757d;
      margin-bottom: 2px;
    }

    .stat-val {
      font-size: 22px;
      font-weight: 700;
      line-height: 1;
    }

    .quick-link {
      transition: transform .15s;
    }

    .quick-link:hover {
      transform: translateY(-3px);
    }
  </style>
@endpush

@section('content')

  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      {{-- ===== Row 1 : Main Stat Boxes ===== --}}
      <div class="row">

        {{-- Employees --}}
        <div class="col-lg-3 col-md-6 col-6">
          <div class="small-box bg-primary">
            <div class="inner">
              <h3>{{ $totalEmployees }}</h3>
              <p>Total Employees</p>
              <small>
                <span class="badge badge-light text-success">{{ $activeEmployees }} Active</span>
                <span class="badge badge-light text-danger ml-1">{{ $terminatedEmployees }} Terminated</span>
              </small>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="{{ url('/payroll/employees') }}" class="small-box-footer">
              View All <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        {{-- Monthly Payroll --}}
        <div class="col-lg-3 col-md-6 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>₹ {{ number_format($monthlyPayroll, 0) }}</h3>
              <p>Est. Monthly Payroll</p>
              <small>
                <span class="badge badge-light text-success">{{ $employeesWithSalary }} Assigned</span>
                @if($employeesWithoutSalary > 0)
                  <span class="badge badge-light text-warning ml-1">{{ $employeesWithoutSalary }} Pending</span>
                @endif
              </small>
            </div>
            <div class="icon"><i class="fas fa-rupee-sign"></i></div>
            <a href="{{ url('/payroll/salaries') }}" class="small-box-footer">
              View Salaries <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        {{-- Machines --}}
        <div class="col-lg-3 col-md-6 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>{{ $totalMachines }}</h3>
              <p>Total Machines</p>
              <small>
                <span class="badge badge-light text-success">{{ $activeMachines }} Active</span>
                <span class="badge badge-light text-secondary ml-1">{{ $totalMachines - $activeMachines }} Inactive</span>
              </small>
            </div>
            <div class="icon"><i class="fas fa-cog"></i></div>
            <a href="{{ url('/master/machines') }}" class="small-box-footer">
              View All <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

        {{-- Vendors --}}
        <div class="col-lg-3 col-md-6 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>{{ $totalCompanies }}</h3>
              <p>Total Vendors</p>
              <small>
                <span class="badge badge-light text-success">{{ $activeCompanies }} Active</span>
                <span class="badge badge-light text-secondary ml-1">{{ $totalCompanies - $activeCompanies }}
                  Inactive</span>
              </small>
            </div>
            <div class="icon"><i class="fas fa-building"></i></div>
            <a href="{{ url('/master/companies') }}" class="small-box-footer">
              View All <i class="fas fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>

      </div>
      {{-- /.Row 1 --}}

      {{-- ===== Row 2 : Employee Breakdown + Operations & Parts ===== --}}
      <!-- <div class="row">

          {{-- Employee Type Breakdown --}}
          <div class="col-lg-4 col-md-6">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Employee Type (Active)</h3>
              </div>
              <div class="card-body">
                <canvas id="empTypeChart" height="180"></canvas>
              </div>
              <div class="card-footer d-flex justify-content-around text-center p-2">
                <div>
                  <div class="stat-label">Lathe</div>
                  <div class="stat-val text-primary">{{ $latheEmployees }}</div>
                </div>
                <div>
                  <div class="stat-label">CNC</div>
                  <div class="stat-val text-success">{{ $cncEmployees }}</div>
                </div>
                <div>
                  <div class="stat-label">Both</div>
                  <div class="stat-val text-warning">{{ $bothEmployees }}</div>
                </div>
              </div>
            </div>
          </div>

          {{-- Employee Status --}}
          <div class="col-lg-4 col-md-6">
            <div class="card card-success card-outline">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-check mr-1"></i> Employee Status</h3>
              </div>
              <div class="card-body">
                <canvas id="empStatusChart" height="180"></canvas>
              </div>
              <div class="card-footer d-flex justify-content-around text-center p-2">
                <div>
                  <div class="stat-label">Active</div>
                  <div class="stat-val text-success">{{ $activeEmployees }}</div>
                </div>
                <div>
                  <div class="stat-label">Inactive</div>
                  <div class="stat-val text-secondary">{{ $inactiveEmployees }}</div>
                </div>
                <div>
                  <div class="stat-label">Terminated</div>
                  <div class="stat-val text-danger">{{ $terminatedEmployees }}</div>
                </div>
              </div>
            </div>
          </div>

          {{-- Operations & Parts --}}
          <div class="col-lg-4 col-md-12">
            <div class="card card-warning card-outline">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-tools mr-1"></i> Operations & Parts</h3>
              </div>
              <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-wrench text-warning mr-2"></i> Total Operations</span>
                    <span>
                      <span class="badge badge-warning badge-pill">{{ $totalOperations }}</span>
                      <small class="text-muted ml-1">{{ $activeOperations }} active</small>
                    </span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-puzzle-piece text-info mr-2"></i> Total Parts</span>
                    <span>
                      <span class="badge badge-info badge-pill">{{ $totalParts }}</span>
                      <small class="text-muted ml-1">{{ $activeParts }} active</small>
                    </span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-cog text-warning mr-2"></i> Total Machines</span>
                    <span>
                      <span class="badge badge-warning badge-pill">{{ $totalMachines }}</span>
                      <small class="text-muted ml-1">{{ $activeMachines }} active</small>
                    </span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-building text-danger mr-2"></i> Total Vendors</span>
                    <span>
                      <span class="badge badge-danger badge-pill">{{ $totalCompanies }}</span>
                      <small class="text-muted ml-1">{{ $activeCompanies }} active</small>
                    </span>
                  </li>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-rupee-sign text-success mr-2"></i> Salary Assigned</span>
                    <span>
                      <span class="badge badge-success badge-pill">{{ $employeesWithSalary }}</span>
                      @if($employeesWithoutSalary > 0)
                        <small class="text-warning ml-1"><i class="fas fa-exclamation-triangle"></i> {{ $employeesWithoutSalary }} pending</small>
                      @endif
                    </span>
                  </li>
                </ul>
              </div>
              <div class="card-footer text-center">
                <a href="{{ url('/master/operations') }}" class="btn btn-sm btn-outline-warning mr-1">Operations</a>
                <a href="{{ url('/master/parts') }}" class="btn btn-sm btn-outline-info">Parts</a>
              </div>
            </div>
          </div>

        </div> -->
      {{-- /.Row 2 --}}

      {{-- ===== Row 3 : Recent Employees ===== --}}
      <!-- <div class="row">
          <div class="col-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-clock mr-1"></i> Recently Added Employees</h3>
                <div class="card-tools">
                  <a href="{{ url('/payroll/employees') }}" class="btn btn-primary btn-sm">View All</a>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-sm table-hover mb-0">
                    <thead class="thead-light">
                      <tr>
                        <th>Emp Code</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Mobile</th>
                        <th>Joining Date</th>
                        <th>Per Month (₹)</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($recentEmployees as $emp)
                      @php
                        $sc = ['active'=>'success','inactive'=>'secondary','terminated'=>'danger'][$emp->status] ?? 'secondary';
                      @endphp
                      <tr>
                        <td><a href="{{ route('employees.show', $emp->id) }}">{{ $emp->emp_code }}</a></td>
                        <td><a href="{{ route('employees.show', $emp->id) }}">{{ $emp->name }}</a></td>
                        <td>{{ ucfirst($emp->employee_type) }}</td>
                        <td>{{ $emp->mobile_primary }}</td>
                        <td>{{ $emp->joining_date ? $emp->joining_date->format('d M Y') : '—' }}</td>
                        <td>
                          @if($emp->currentSalary)
                            ₹ {{ number_format($emp->currentSalary->per_month, 2) }}
                          @else
                            <span class="text-warning"><i class="fas fa-exclamation-circle"></i> Not set</span>
                          @endif
                        </td>
                        <td><span class="badge badge-{{ $sc }}">{{ ucfirst($emp->status) }}</span></td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="7" class="text-center text-muted py-3">No employees found.</td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div> -->
      {{-- /.Row 3 --}}

      {{-- ===== Row 4 : Quick Links ===== --}}
      <!-- <div class="row">
          <div class="col-12">
            <h6 class="text-muted font-weight-bold mb-2"><i class="fas fa-bolt mr-1"></i> Quick Links</h6>
          </div>
          @foreach([
            ['url' => '/payroll/employees',  'icon' => 'fas fa-users',        'color' => 'primary', 'label' => 'Employees'],
            ['url' => '/payroll/salaries',   'icon' => 'fas fa-rupee-sign',   'color' => 'success', 'label' => 'Salaries'],
            ['url' => '/master/companies',  'icon' => 'fas fa-building',     'color' => 'danger',  'label' => 'Our Vendors'],
            ['url' => '/master/machines',   'icon' => 'fas fa-cog',          'color' => 'warning', 'label' => 'Machines'],
            ['url' => '/master/operations', 'icon' => 'fas fa-wrench',       'color' => 'info',    'label' => 'Operations'],
            ['url' => '/master/parts',      'icon' => 'fas fa-puzzle-piece', 'color' => 'secondary','label' => 'Parts'],
          ] as $ql)
          <div class="col-lg-2 col-md-4 col-6 mb-3">
            <a href="{{ url($ql['url']) }}" class="text-decoration-none quick-link">
              <div class="card card-{{ $ql['color'] }} card-outline text-center py-3 mb-0 h-100">
                <div class="card-body p-2">
                  <i class="{{ $ql['icon'] }} fa-2x text-{{ $ql['color'] }} mb-2"></i>
                  <div class="font-weight-bold text-dark">{{ $ql['label'] }}</div>
                </div>
              </div>
            </a>
          </div>
          @endforeach
        </div> -->
      {{-- /.Row 4 --}}

    </div>
  </section>

@endsection

@push('scripts')
  <script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
  <script>
    // Employee Type Chart
    new Chart(document.getElementById('empTypeChart'), {
      type: 'doughnut',
      data: {
        labels: ['Lathe', 'CNC', 'Both'],
        datasets: [{
          data: [{{ $latheEmployees }}, {{ $cncEmployees }}, {{ $bothEmployees }}],
          backgroundColor: ['#007bff', '#28a745', '#ffc107'],
          borderWidth: 2
        }]
      },
      options: {
        cutoutPercentage: 65,
        legend: { position: 'bottom', labels: { boxWidth: 12 } },
        maintainAspectRatio: true,
      }
    });

    // Employee Status Chart
    new Chart(document.getElementById('empStatusChart'), {
      type: 'doughnut',
      data: {
        labels: ['Active', 'Inactive', 'Terminated'],
        datasets: [{
          data: [{{ $activeEmployees }}, {{ $inactiveEmployees }}, {{ $terminatedEmployees }}],
          backgroundColor: ['#28a745', '#6c757d', '#dc3545'],
          borderWidth: 2
        }]
      },
      options: {
        cutoutPercentage: 65,
        legend: { position: 'bottom', labels: { boxWidth: 12 } },
        maintainAspectRatio: true,
      }
    });
  </script>
@endpush