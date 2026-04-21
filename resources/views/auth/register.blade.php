<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }} | Register</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition register-page">

  <div class="register-box">
    <div class="card card-outline card-primary">

      <div class="card-header text-center">
        <a href="{{ url('/') }}" class="h1"><b>{{ config('app.name') }}</b></a>
      </div>

      <div class="card-body">
        <p class="login-box-msg">Register a new membership</p>

        <form action="{{ route('register') }}" method="POST">
          @csrf

          {{-- Name --}}
          <div class="input-group mb-3">
            <input type="text" name="name" value="{{ old('name') }}"
              class="form-control @error('name') is-invalid @enderror" placeholder="Full name" autofocus
              autocomplete="name">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
            @error('name')
              <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
          </div>

          {{-- Email --}}
          <div class="input-group mb-3">
            <input type="email" name="email" value="{{ old('email') }}"
              class="form-control @error('email') is-invalid @enderror" placeholder="Email" autocomplete="username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
            @error('email')
              <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
          </div>

          {{-- Password --}}
          <div class="input-group mb-3">
            <input type="password" id="password" name="password"
              class="form-control @error('password') is-invalid @enderror" placeholder="Password"
              autocomplete="new-password">
            <div class="input-group-append">
              <div class="input-group-text" style="cursor:pointer;" onclick="togglePassword('password', 'eyeIcon1')">
                <span class="fas fa-eye" id="eyeIcon1"></span>
              </div>
            </div>
            @error('password')
              <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
          </div>

          {{-- Confirm Password --}}
          <div class="input-group mb-3">
            <input type="password" id="password_confirmation" name="password_confirmation"
              class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Confirm password"
              autocomplete="new-password">
            <div class="input-group-append">
              <div class="input-group-text" style="cursor:pointer;"
                onclick="togglePassword('password_confirmation', 'eyeIcon2')">
                <span class="fas fa-eye" id="eyeIcon2"></span>
              </div>
            </div>
            @error('password_confirmation')
              <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
          </div>

          {{-- Role (admin only) --}}
          @if(Auth::check() && Auth::user()->hasRole('admin'))
            <div class="input-group mb-3">
              <select name="role" class="form-control @error('role') is-invalid @enderror">
                <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Assign Role --</option>
                @foreach($roles as $role)
                  <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                    {{ ucfirst($role) }}
                  </option>
                @endforeach
              </select>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user-shield"></span>
                </div>
              </div>
              @error('role')
                <span class="invalid-feedback d-block">{{ $message }}</span>
              @enderror
            </div>
          @endif

          {{-- Submit --}}
          <div class="row">
            <div class="col-12 my-2">
              <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>
          </div>

        </form>

        {{-- Already registered --}}
        <p class="mb-1 mt-3">
          <a href="{{ route('dashboard') }}">Back to Dashboard</a>
        </p>

      </div>
      {{-- /.card-body --}}
    </div>
    {{-- /.card --}}
  </div>
  {{-- /.register-box --}}

  <!-- jQuery -->
  <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- Sandhya ERP App -->
  <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>

  <script>
    function togglePassword(fieldId, iconId) {
      const field = document.getElementById(fieldId);
      const icon = document.getElementById(iconId);
      if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    }
  </script>
</body>

</html>