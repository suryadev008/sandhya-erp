{{-- ============================================================
     resources/views/partials/footer.blade.php
     ============================================================ --}}

<footer class="main-footer">
  <strong>
    Copyright &copy; {{ date('Y')==2025 ? date('Y') : '2025-' . date('Y') }}
    <a href="{{ url('/') }}">{{ config('app.name') }}</a>.
  </strong>
  All rights reserved.
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 3.2.0
  </div>
</footer>