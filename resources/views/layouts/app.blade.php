<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', config('app.name') . ' | Dashboard')</title>

  <!-- Preconnect for faster Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('public/adminlte/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('public/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

  <style>
    /* Sidebar scrollable so submenus don't get clipped */
    .main-sidebar .sidebar {
      overflow-y: auto !important;
    }

    .main-sidebar .nav-sidebar .nav-treeview {
      overflow: visible !important;
    }

    /* ── Page Transition Loader ── */
    #page-loader-bar {
      position: fixed;
      top: 0;
      left: 0;
      height: 3px;
      width: 0%;
      background: linear-gradient(90deg, #007bff, #00c6ff);
      z-index: 100000;
      border-radius: 0 3px 3px 0;
      box-shadow: 0 0 10px rgba(0, 123, 255, .7);
      opacity: 0;
      /* hidden by opacity — NOT display:none */
      pointer-events: none;
      transition: none;
    }

    #page-loader-overlay {
      display: none;
      position: fixed;
      inset: 0;
      z-index: 99998;
      cursor: wait;
      pointer-events: none;
    }

    #page-loader-overlay.active {
      display: block;
      pointer-events: all;
    }
  </style>

  {{-- Page specific styles --}}
  @stack('styles')
  <script>window.APP_URL = '{{ rtrim(url('/'), '/') }}';</script>
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

  <!-- Page transition bar -->
  <div id="page-loader-bar"></div>
  <div id="page-loader-overlay"></div>

  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__shake" src="{{ asset('public/adminlte/dist/img/AdminLTELogo.png') }}"
        alt="Sandhya ERP Logo" height="60" width="60">
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
  <script src="{{ asset('public/adminlte/plugins/jquery/jquery.min.js') }}"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="{{ asset('public/adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('public/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- overlayScrollbars -->
  <script src="{{ asset('public/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
  <!-- Sandhya ERP App -->
  <script src="{{ asset('public/adminlte/dist/js/adminlte.js') }}"></script>

  {{-- Page specific scripts --}}
  @stack('scripts')

  <script>
      // ── Page Transition Loader ────────────────────────────────────────────────
      (function () {
        var bar = document.getElementById('page-loader-bar');
        var overlay = document.getElementById('page-loader-overlay');
        var fillTimer;

        function startLoader() {
          clearTimeout(fillTimer);

          // Reset without transition
          bar.style.transition = 'none';
          bar.style.width = '0%';
          bar.style.opacity = '1';

          // *** Force browser reflow — this is the critical fix ***
          // Without this, the browser batches the style changes and
          // the transition never fires.
          bar.offsetHeight; // eslint-disable-line no-unused-expressions

          // Now animate
          bar.style.transition = 'width 0.5s ease';
          bar.style.width = '50%';

          fillTimer = setTimeout(function () {
            bar.style.transition = 'width 10s cubic-bezier(0.05, 0.1, 0, 1)';
            bar.style.width = '90%';
          }, 500);

          overlay.classList.add('active');
          document.body.style.cursor = 'wait';
        }

        // Show loader on navigation link clicks
        document.addEventListener('click', function (e) {
          var el = e.target.closest('a');
          if (!el) return;

          var href = el.getAttribute('href');
          if (!href ||
            href === '#' ||
            href.startsWith('javascript') ||
            href.startsWith('mailto') ||
            href.startsWith('tel') ||
            el.getAttribute('target') === '_blank' ||
            el.getAttribute('data-toggle') ||
            el.getAttribute('data-dismiss') ||
            el.getAttribute('data-widget') ||
            e.ctrlKey || e.metaKey || e.shiftKey) {
            return;
          }
          startLoader();
        }, true);

        // Reset if user navigates back (bfcache) — remove loader from restored page
        window.addEventListener('pageshow', function (e) {
          if (e.persisted) {
            clearTimeout(fillTimer);
            bar.style.transition = 'none';
            bar.style.opacity = '0';
            bar.style.width = '0%';
            overlay.classList.remove('active');
            document.body.style.cursor = '';
          }
        });
      })();

    $(document).ready(function () {
      // Save Theme Settings button click
      $(document).on('click', '#save-theme-btn', function () {
        let $btn = $(this);
        let originalText = $btn.html();
        $btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        $btn.prop('disabled', true);

        setTimeout(function () {
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
            success: function (response) {
              $btn.html('<i class="fas fa-check"></i> Saved!');
              setTimeout(() => {
                $btn.html(originalText);
                $btn.prop('disabled', false);
              }, 2000);
            },
            error: function (err) {
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