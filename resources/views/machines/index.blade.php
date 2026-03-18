@extends('layouts.app')

@section('title', config('app.name', 'Sandhya ERP') . ' | Machines')

@section('content')

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard v1</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

      <!-- ===== Stat Boxes Row ===== -->
      <div class="row">

        <!-- New Orders -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3>150</h3>
              <p>New Orders</p>
            </div>
            <div class="icon"><i class="ion ion-bag"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- Bounce Rate -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>53<sup style="font-size: 20px">%</sup></h3>
              <p>Bounce Rate</p>
            </div>
            <div class="icon"><i class="ion ion-stats-bars"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- User Registrations -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3>44</h3>
              <p>User Registrations</p>
            </div>
            <div class="icon"><i class="ion ion-person-add"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <!-- Unique Visitors -->
        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3>65</h3>
              <p>Unique Visitors</p>
            </div>
            <div class="icon"><i class="ion ion-pie-graph"></i></div>
            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

      </div>
      <!-- /.stat boxes row -->

      <!-- ===== Main Row ===== -->
      <div class="row">

        <!-- ===== Left Column ===== -->
        <section class="col-lg-7 connectedSortable">

          <!-- Sales Chart Card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-chart-pie mr-1"></i> Sales
              </h3>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="card-body">
              <div class="tab-content p-0">
                <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
                  <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>
                </div>
                <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                  <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
                </div>
              </div>
            </div>
          </div>
          <!-- /.Sales Chart Card -->

          <!-- Direct Chat Card -->
          <div class="card direct-chat direct-chat-primary">
            <div class="card-header">
              <h3 class="card-title">Direct Chat</h3>
              <div class="card-tools">
                <span title="3 New Messages" class="badge badge-primary">3</span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" title="Contacts" data-widget="chat-pane-toggle">
                  <i class="fas fa-comments"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="direct-chat-messages">

                <div class="direct-chat-msg">
                  <div class="direct-chat-infos clearfix">
                    <span class="direct-chat-name float-left">Alexander Pierce</span>
                    <span class="direct-chat-timestamp float-right">23 Jan 2:00 pm</span>
                  </div>
                  <img class="direct-chat-img" src="{{ asset('adminlte/dist/img/user1-128x128.jpg') }}" alt="message user image">
                  <div class="direct-chat-text">Is this template really for free? That's unbelievable!</div>
                </div>

                <div class="direct-chat-msg right">
                  <div class="direct-chat-infos clearfix">
                    <span class="direct-chat-name float-right">Sarah Bullock</span>
                    <span class="direct-chat-timestamp float-left">23 Jan 2:05 pm</span>
                  </div>
                  <img class="direct-chat-img" src="{{ asset('adminlte/dist/img/user3-128x128.jpg') }}" alt="message user image">
                  <div class="direct-chat-text">You better believe it!</div>
                </div>

                <div class="direct-chat-msg">
                  <div class="direct-chat-infos clearfix">
                    <span class="direct-chat-name float-left">Alexander Pierce</span>
                    <span class="direct-chat-timestamp float-right">23 Jan 5:37 pm</span>
                  </div>
                  <img class="direct-chat-img" src="{{ asset('adminlte/dist/img/user1-128x128.jpg') }}" alt="message user image">
                  <div class="direct-chat-text">Working with AdminLTE on a great new app! Wanna join?</div>
                </div>

                <div class="direct-chat-msg right">
                  <div class="direct-chat-infos clearfix">
                    <span class="direct-chat-name float-right">Sarah Bullock</span>
                    <span class="direct-chat-timestamp float-left">23 Jan 6:10 pm</span>
                  </div>
                  <img class="direct-chat-img" src="{{ asset('adminlte/dist/img/user3-128x128.jpg') }}" alt="message user image">
                  <div class="direct-chat-text">I would love to.</div>
                </div>

              </div>
              <!-- Contacts list -->
              <div class="direct-chat-contacts">
                <ul class="contacts-list">
                  @foreach([
                    ['img' => 'user1-128x128.jpg', 'name' => 'Count Dracula',  'date' => '2/28/2015', 'msg' => 'How have you been? I was...'],
                    ['img' => 'user7-128x128.jpg', 'name' => 'Sarah Doe',       'date' => '2/23/2015', 'msg' => 'I will be waiting for...'],
                    ['img' => 'user3-128x128.jpg', 'name' => 'Nadia Jolie',     'date' => '2/20/2015', 'msg' => "I'll call you back at..."],
                    ['img' => 'user5-128x128.jpg', 'name' => 'Nora S. Vans',   'date' => '2/10/2015', 'msg' => 'Where is your new...'],
                    ['img' => 'user6-128x128.jpg', 'name' => 'John K.',         'date' => '1/27/2015', 'msg' => 'Can I take a look at...'],
                    ['img' => 'user8-128x128.jpg', 'name' => 'Kenneth M.',      'date' => '1/4/2015',  'msg' => 'Never mind I found...'],
                  ] as $contact)
                  <li>
                    <a href="#">
                      <img class="contacts-list-img" src="{{ asset('adminlte/dist/img/' . $contact['img']) }}" alt="User Avatar">
                      <div class="contacts-list-info">
                        <span class="contacts-list-name">
                          {{ $contact['name'] }}
                          <small class="contacts-list-date float-right">{{ $contact['date'] }}</small>
                        </span>
                        <span class="contacts-list-msg">{{ $contact['msg'] }}</span>
                      </div>
                    </a>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="card-footer">
              <form action="#" method="post">
                @csrf
                <div class="input-group">
                  <input type="text" name="message" placeholder="Type Message ..." class="form-control">
                  <span class="input-group-append">
                    <button type="button" class="btn btn-primary">Send</button>
                  </span>
                </div>
              </form>
            </div>
          </div>
          <!-- /.Direct Chat -->

          <!-- TO DO List Card -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="ion ion-clipboard mr-1"></i> To Do List
              </h3>
              <div class="card-tools">
                <ul class="pagination pagination-sm">
                  <li class="page-item"><a href="#" class="page-link">&laquo;</a></li>
                  <li class="page-item"><a href="#" class="page-link">1</a></li>
                  <li class="page-item"><a href="#" class="page-link">2</a></li>
                  <li class="page-item"><a href="#" class="page-link">3</a></li>
                  <li class="page-item"><a href="#" class="page-link">&raquo;</a></li>
                </ul>
              </div>
            </div>
            <div class="card-body">
              <ul class="todo-list" data-widget="todo-list">
                @foreach([
                  ['id' => 1, 'text' => 'Design a nice theme',                    'badge' => 'danger',    'time' => '2 mins',  'done' => false],
                  ['id' => 2, 'text' => 'Make the theme responsive',              'badge' => 'info',      'time' => '4 hours', 'done' => true],
                  ['id' => 3, 'text' => 'Let theme shine like a star',            'badge' => 'warning',   'time' => '1 day',   'done' => false],
                  ['id' => 4, 'text' => 'Let theme shine like a star',            'badge' => 'success',   'time' => '3 days',  'done' => false],
                  ['id' => 5, 'text' => 'Check your messages and notifications',  'badge' => 'primary',   'time' => '1 week',  'done' => false],
                  ['id' => 6, 'text' => 'Let theme shine like a star',            'badge' => 'secondary', 'time' => '1 month', 'done' => false],
                ] as $todo)
                <li>
                  <span class="handle">
                    <i class="fas fa-ellipsis-v"></i>
                    <i class="fas fa-ellipsis-v"></i>
                  </span>
                  <div class="icheck-primary d-inline ml-2">
                    <input type="checkbox" value="" name="todo{{ $todo['id'] }}" id="todoCheck{{ $todo['id'] }}" {{ $todo['done'] ? 'checked' : '' }}>
                    <label for="todoCheck{{ $todo['id'] }}"></label>
                  </div>
                  <span class="text">{{ $todo['text'] }}</span>
                  <small class="badge badge-{{ $todo['badge'] }}">
                    <i class="far fa-clock"></i> {{ $todo['time'] }}
                  </small>
                  <div class="tools">
                    <i class="fas fa-edit"></i>
                    <i class="fas fa-trash-o"></i>
                  </div>
                </li>
                @endforeach
              </ul>
            </div>
            <div class="card-footer clearfix">
              <button type="button" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> Add item
              </button>
            </div>
          </div>
          <!-- /.TO DO List -->

        </section>
        <!-- /.Left col -->

        <!-- ===== Right Column ===== -->
        <section class="col-lg-5 connectedSortable">

          <!-- Map Card -->
          <div class="card bg-gradient-primary">
            <div class="card-header border-0">
              <h3 class="card-title">
                <i class="fas fa-map-marker-alt mr-1"></i> Visitors
              </h3>
              <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm daterange" title="Date range">
                  <i class="far fa-calendar-alt"></i>
                </button>
                <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div id="world-map" style="height: 250px; width: 100%;"></div>
            </div>
            <div class="card-footer bg-transparent">
              <div class="row">
                <div class="col-4 text-center">
                  <div id="sparkline-1"></div>
                  <div class="text-white">Visitors</div>
                </div>
                <div class="col-4 text-center">
                  <div id="sparkline-2"></div>
                  <div class="text-white">Online</div>
                </div>
                <div class="col-4 text-center">
                  <div id="sparkline-3"></div>
                  <div class="text-white">Sales</div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.Map Card -->

          <!-- Sales Graph Card -->
          <div class="card bg-gradient-info">
            <div class="card-header border-0">
              <h3 class="card-title">
                <i class="fas fa-th mr-1"></i> Sales Graph
              </h3>
              <div class="card-tools">
                <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <canvas class="chart" id="line-chart"
                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
            <div class="card-footer bg-transparent">
              <div class="row">
                <div class="col-4 text-center">
                  <input type="text" class="knob" data-readonly="true" value="20"
                    data-width="60" data-height="60" data-fgColor="#39CCCC">
                  <div class="text-white">Mail-Orders</div>
                </div>
                <div class="col-4 text-center">
                  <input type="text" class="knob" data-readonly="true" value="50"
                    data-width="60" data-height="60" data-fgColor="#39CCCC">
                  <div class="text-white">Online</div>
                </div>
                <div class="col-4 text-center">
                  <input type="text" class="knob" data-readonly="true" value="30"
                    data-width="60" data-height="60" data-fgColor="#39CCCC">
                  <div class="text-white">In-Store</div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.Sales Graph Card -->

          <!-- Calendar Card -->
          <div class="card bg-gradient-success">
            <div class="card-header border-0">
              <h3 class="card-title">
                <i class="far fa-calendar-alt"></i> Calendar
              </h3>
              <div class="card-tools">
                <div class="btn-group">
                  <button type="button" class="btn btn-success btn-sm dropdown-toggle"
                    data-toggle="dropdown" data-offset="-52">
                    <i class="fas fa-bars"></i>
                  </button>
                  <div class="dropdown-menu" role="menu">
                    <a href="#" class="dropdown-item">Add new event</a>
                    <a href="#" class="dropdown-item">Clear events</a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">View calendar</a>
                  </div>
                </div>
                <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
            <div class="card-body pt-0">
              <div id="calendar" style="width: 100%"></div>
            </div>
          </div>
          <!-- /.Calendar Card -->

        </section>
        <!-- /.Right col -->

      </div>
      <!-- /.Main Row -->

    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->

@endsection

@push('scripts')
  <!-- AdminLTE dashboard demo -->
  <script src="{{ asset('adminlte/dist/js/pages/dashboard.js') }}"></script>
@endpush