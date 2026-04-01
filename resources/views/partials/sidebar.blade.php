{{-- ============================================================
resources/views/partials/sidebar.blade.php
============================================================ --}}

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <!-- Brand Logo -->
  <a href="{{ url('/') }}" class="brand-link">
    <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="Sandhya ERP Logo"
      class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">{{ config('app.name', 'Sandhya ERP') }}</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">

    <!-- Sidebar user panel (optional) -->
    @auth
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="{{ route('profile.edit') }}" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>
    @endauth

    <!-- SidebarSearch Form -->
    <!-- <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div> -->

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item">
          <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Production Register -->
        @php
          $inLathe = request()->routeIs('lathe-productions.*');
          $inCnc   = request()->routeIs('cnc-productions.*');
          $inProd  = $inLathe || $inCnc;
        @endphp
        <li class="nav-item {{ $inProd ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ $inProd ? 'active' : '' }}">
            <i class="nav-icon fas fa-clipboard-list"></i>
            <p>Production Register <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('lathe-productions.index') }}"
                class="nav-link {{ $inLathe ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Lathe Register</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('lathe-productions.create') }}"
                class="nav-link {{ request()->routeIs('lathe-productions.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Lathe Entry</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('cnc-productions.index') }}"
                class="nav-link {{ request()->routeIs('cnc-productions.index') || request()->routeIs('cnc-productions.show') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>CNC Register</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('cnc-productions.create') }}"
                class="nav-link {{ request()->routeIs('cnc-productions.create') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>CNC Entry</p>
              </a>
            </li>
          </ul>
        </li>


        <!-- Master -->
        <li class="nav-item {{ request()->is('master/*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->is('master/*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-database"></i>
            <p>Master <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/my-company') }}" class="nav-link {{ request()->is('my-company*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>My Company</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/master/companies') }}"
                class="nav-link {{ request()->is('master/companies*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Vendor Companies</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/master/contacts') }}"
                class="nav-link {{ request()->is('master/contacts*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Contacts</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/master/machine-types') }}"
                class="nav-link {{ request()->is('master/machine-types*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Machine Types</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/master/machines') }}"
                class="nav-link {{ request()->is('master/machines*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Machines</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/master/operations') }}"
                class="nav-link {{ request()->is('master/operations*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Operations</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/master/parts') }}"
                class="nav-link {{ request()->is('master/parts*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Parts</p>
              </a>
            </li>

          </ul>
        </li>

        <!-- Payroll -->
        <li class="nav-item {{ request()->is('payroll/*') ? 'menu-open' : '' }}">
          <a href="#" class="nav-link {{ request()->is('payroll/*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-money-check-alt"></i>
            <p>Payroll <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ url('/payroll/employees') }}"
                class="nav-link {{ request()->is('payroll/employees*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Employees</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/payroll/salaries') }}"
                class="nav-link {{ request()->is('payroll/salaries*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Salary</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ url('/payroll/payrolls') }}"
                class="nav-link {{ request()->is('payroll/payrolls*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Payroll</p>
              </a>
            </li>
          </ul>
        </li>


        @auth
          <li class="nav-item mt-2">
            <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
            <a href="#" class="nav-link"
              onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        @endauth
      </ul>
    </nav>
    <!-- /.sidebar-menu -->

  </div>
  <!-- /.sidebar -->
</aside>