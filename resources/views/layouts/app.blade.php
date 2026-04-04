<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', config('app.name') . ' | Dashboard')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.css') }}">

  <style>
    /* Sidebar scrollable so submenus don't get clipped */
    .main-sidebar .sidebar { overflow-y: auto !important; }
    .main-sidebar .nav-sidebar .nav-treeview { overflow: visible !important; }
  </style>

  {{-- Page specific styles --}}
  @stack('styles')
</head>
@php
    $themeClass = 'hold-transition sidebar-mini layout-fixed';
    if (auth()->check() && !empty(auth()->user()->theme_settings['body_class'])) {
        $themeClass = str_replace(
            ['control-sidebar-slide-open', 'control-sidebar-open'],
            '',
            auth()->user()->theme_settings['body_class']
        );
    }
@endphp
<body class="{{ $themeClass }}">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="Sandhya ERP Logo" height="60" width="60">
  </div>

  {{-- ========== NAVBAR ========== --}}
  @include('partials.navbar')

  {{-- ========== SIDEBAR ========== --}}
  @include('partials.sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    {{-- Page specific content --}}
    @yield('content')

  </div>
  <!-- /.content-wrapper -->

  {{-- ========== FOOTER ========== --}}
  @include('partials.footer')

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->

</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('adminlte/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('adminlte/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('adminlte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('adminlte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- Sandhya ERP App -->
<script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>
<!-- Sandhya ERP for demo purposes -->
<script src="{{ asset('adminlte/dist/js/demo.js') }}"></script>

{{-- Page specific scripts --}}
@stack('scripts')

<script>
$(document).ready(function() {
    // Save Theme Settings button click
    $(document).on('click', '#save-theme-btn', function() {
        let $btn = $(this);
        let originalText = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        $btn.prop('disabled', true);

        setTimeout(function() {
            let bodyClass = $('body').attr('class')
                .replace('control-sidebar-slide-open', '')
                .replace('control-sidebar-open', '')
                .replace(/\s+/g, ' ')
                .trim();

            $.ajax({
                url: '{{ route('theme.settings.update') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    body_class: bodyClass
                },
                success: function(response) {
                    $btn.html('<i class="fas fa-check"></i> Saved!');
                    setTimeout(() => {
                        $btn.html(originalText);
                        $btn.prop('disabled', false);
                    }, 2000);
                },
                error: function(err) {
                    $btn.html('<i class="fas fa-times"></i> Failed');
                    setTimeout(() => {
                        $btn.html(originalText);
                        $btn.prop('disabled', false);
                    }, 2000);
                }
            });
        }, 100);
    });
});
</script>

</body>
</html>