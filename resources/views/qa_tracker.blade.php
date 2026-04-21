@extends('layouts.app')

@section('title', config('app.name') . ' | QA Tracker')

@push('styles')
<style>
  .qa-status-done    { background: #d4edda; color: #155724; font-weight: 600; }
  .qa-status-partial { background: #fff3cd; color: #856404; font-weight: 600; }
  .qa-status-pending { background: #f8d7da; color: #721c24; font-weight: 600; }
  .qa-table th       { background: #343a40; color: #fff; }
  .module-badge      { font-size: 11px; }
  .check-icon        { color: #28a745; }
  .cross-icon        { color: #dc3545; }
  .warn-icon         { color: #ffc107; }
</style>
@endpush

@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1 class="m-0">QA Tracker — New Modules</h1></div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
          <li class="breadcrumb-item active">QA Tracker</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    {{-- Summary Cards --}}
    <div class="row mb-3">
      <div class="col-md-3">
        <div class="small-box bg-success">
          <div class="inner"><h3>7</h3><p>Modules Done</p></div>
          <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="small-box bg-warning">
          <div class="inner"><h3>2</h3><p>Partially Done</p></div>
          <div class="icon"><i class="fas fa-exclamation-circle"></i></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="small-box bg-danger">
          <div class="inner"><h3>0</h3><p>Pending QA</p></div>
          <div class="icon"><i class="fas fa-times-circle"></i></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="small-box bg-info">
          <div class="inner"><h3>9</h3><p>Total Modules</p></div>
          <div class="icon"><i class="fas fa-layer-group"></i></div>
        </div>
      </div>
    </div>

    {{-- Main QA Table --}}
    <div class="card card-primary card-outline">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-clipboard-check mr-1"></i> Module QA Checklist</h3>
        <div class="card-tools">
          <small class="text-muted">Last updated: {{ now()->format('d M Y') }}</small>
        </div>
      </div>
      <div class="card-body p-0">
        <table class="table table-bordered table-hover mb-0 qa-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Module</th>
              <th>Feature</th>
              <th>List/View</th>
              <th>Add</th>
              <th>Edit</th>
              <th>Delete</th>
              <th>Permissions</th>
              <th>AJAX</th>
              <th>Overall Status</th>
              <th>Notes</th>
            </tr>
          </thead>
          <tbody>

            @php
              $tick = '<i class="fas fa-check-circle check-icon"></i>';
              $cross = '<i class="fas fa-times-circle cross-icon"></i>';
              $warn  = '<i class="fas fa-exclamation-triangle warn-icon"></i>';

              $modules = [
                [
                  'num'    => 1,
                  'module' => 'Roles & Permissions',
                  'feature'=> 'RBAC — Spatie',
                  'list'   => 'tick', 'add' => 'tick', 'edit' => 'tick', 'delete' => 'tick',
                  'perm'   => 'tick', 'ajax' => 'tick',
                  'status' => 'done',
                  'notes'  => 'Admin-only. \'admin\' role delete protected.',
                ],
                [
                  'num'    => 2,
                  'module' => 'User Accounts',
                  'feature'=> 'ERP User CRUD',
                  'list'   => 'tick', 'add' => 'tick', 'edit' => 'tick', 'delete' => 'tick',
                  'perm'   => 'tick', 'ajax' => 'tick',
                  'status' => 'done',
                  'notes'  => 'Self-delete blocked. Edit via data-* attrs (no extra AJAX).',
                ],
                [
                  'num'    => 3,
                  'module' => 'Companies',
                  'feature'=> 'Customer Companies',
                  'list'   => 'tick', 'add' => 'tick', 'edit' => 'tick', 'delete' => 'tick',
                  'perm'   => 'tick', 'ajax' => 'tick',
                  'status' => 'done',
                  'notes'  => 'DataTable with event delegation fixed.',
                ],
                [
                  'num'    => 4,
                  'module' => 'Machines',
                  'feature'=> 'Machine Master',
                  'list'   => 'tick', 'add' => 'tick', 'edit' => 'tick', 'delete' => 'tick',
                  'perm'   => 'tick', 'ajax' => 'tick',
                  'status' => 'done',
                  'notes'  => 'Machine type linked. Working status tracked.',
                ],
                [
                  'num'    => 5,
                  'module' => 'Lathe Production',
                  'feature'=> 'Production Register',
                  'list'   => 'tick', 'add' => 'tick', 'edit' => 'tick', 'delete' => 'tick',
                  'perm'   => 'tick', 'ajax' => 'tick',
                  'status' => 'done',
                  'notes'  => 'Per-employee rate support.',
                ],
                [
                  'num'    => 6,
                  'module' => 'CNC Production',
                  'feature'=> 'CNC Register',
                  'list'   => 'tick', 'add' => 'tick', 'edit' => 'tick', 'delete' => 'tick',
                  'perm'   => 'tick', 'ajax' => 'tick',
                  'status' => 'done',
                  'notes'  => 'Separate CNC entry form. Part/operation linked.',
                ],
                [
                  'num'    => 7,
                  'module' => 'Payroll',
                  'feature'=> 'Salary & Attendance',
                  'list'   => 'tick', 'add' => 'tick', 'edit' => 'tick', 'delete' => 'warn',
                  'perm'   => 'tick', 'ajax' => 'tick',
                  'status' => 'partial',
                  'notes'  => 'Delete on processed payrolls needs review.',
                ],
                [
                  'num'    => 8,
                  'module' => 'My Company',
                  'feature'=> 'Owner Company Profile',
                  'list'   => 'tick', 'add' => 'tick', 'edit' => 'tick', 'delete' => 'tick',
                  'perm'   => 'tick', 'ajax' => 'tick',
                  'status' => 'done',
                  'notes'  => 'Single owner company. Designation support added.',
                ],
                [
                  'num'    => 9,
                  'module' => 'Sidebar / Permissions',
                  'feature'=> 'Permission-gated nav',
                  'list'   => 'tick', 'add' => 'tick', 'edit' => 'tick', 'delete' => 'tick',
                  'perm'   => 'tick', 'ajax' => 'tick',
                  'status' => 'partial',
                  'notes'  => '@can / @role directives applied. Verify with each role.',
                ],
              ];
            @endphp

            @foreach($modules as $m)
              @php
                $statusClass = match($m['status']) {
                  'done'    => 'qa-status-done',
                  'partial' => 'qa-status-partial',
                  default   => 'qa-status-pending',
                };
                $statusLabel = match($m['status']) {
                  'done'    => '✔ Done',
                  'partial' => '⚠ Partial',
                  default   => '✘ Pending',
                };
                $icon = fn($v) => match($v) {
                  'tick'  => '<i class="fas fa-check-circle check-icon"></i>',
                  'cross' => '<i class="fas fa-times-circle cross-icon"></i>',
                  'warn'  => '<i class="fas fa-exclamation-triangle warn-icon"></i>',
                  default => '—',
                };
              @endphp
              <tr>
                <td>{{ $m['num'] }}</td>
                <td><strong>{{ $m['module'] }}</strong></td>
                <td><span class="badge badge-secondary module-badge">{{ $m['feature'] }}</span></td>
                <td class="text-center">{!! $icon($m['list']) !!}</td>
                <td class="text-center">{!! $icon($m['add']) !!}</td>
                <td class="text-center">{!! $icon($m['edit']) !!}</td>
                <td class="text-center">{!! $icon($m['delete']) !!}</td>
                <td class="text-center">{!! $icon($m['perm']) !!}</td>
                <td class="text-center">{!! $icon($m['ajax']) !!}</td>
                <td class="text-center {{ $statusClass }}">{{ $statusLabel }}</td>
                <td><small>{{ $m['notes'] }}</small></td>
              </tr>
            @endforeach

          </tbody>
        </table>
      </div>
    </div>

    {{-- Known Issues / Changelog --}}
    <div class="card card-warning card-outline mt-3">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-bug mr-1"></i> Known Issues & Fixes Log</h3>
      </div>
      <div class="card-body">
        <table class="table table-sm table-bordered">
          <thead class="thead-dark">
            <tr><th>#</th><th>Issue</th><th>Module</th><th>Fix Applied</th><th>Status</th></tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Edit/Delete not working on DataTable rows</td>
              <td>Users, Roles</td>
              <td>Converted to <code>$(document).on('click', ...)</code> event delegation</td>
              <td><span class="badge badge-success">Fixed</span></td>
            </tr>
            <tr>
              <td>2</td>
              <td>Icons not visible on login page</td>
              <td>All Blade Views</td>
              <td>Changed <code>asset('adminlte/...')</code> → <code>asset('adminlte/...')</code></td>
              <td><span class="badge badge-success">Fixed</span></td>
            </tr>
            <tr>
              <td>3</td>
              <td>Apache 404 on <code>/master/roles/{id}</code></td>
              <td>Roles</td>
              <td>Removed HTML form submit, switched to full AJAX (no browser navigation)</td>
              <td><span class="badge badge-success">Fixed</span></td>
            </tr>
            <tr>
              <td>4</td>
              <td><code>GET /master/users/{id}/edit</code> → 404</td>
              <td>Users</td>
              <td>Removed separate edit endpoint. User data embedded in button <code>data-*</code> attributes</td>
              <td><span class="badge badge-success">Fixed</span></td>
            </tr>
            <tr>
              <td>5</td>
              <td>Profile page showing broken <code>role->name</code></td>
              <td>Profile</td>
              <td>Changed to <code>getRoleNames()->implode(', ')</code> (Spatie method)</td>
              <td><span class="badge badge-success">Fixed</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>

@endsection
