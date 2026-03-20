<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }} | Log in</title>

  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Inter', sans-serif;
      height: 100vh;
      display: flex;
      background: #0f172a;
      overflow: hidden;
    }

    /* ── Left Panel ── */
    .login-left {
      flex: 1;
      background: linear-gradient(135deg, #1e3a5f 0%, #0f2744 50%, #091a30 100%);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 60px 40px;
      position: relative;
      overflow: hidden;
    }

    /* animated glow circles */
    .login-left::before {
      content: '';
      position: absolute;
      width: 400px; height: 400px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(59,130,246,0.25) 0%, transparent 70%);
      top: -100px; left: -100px;
      animation: pulse 6s ease-in-out infinite;
    }
    .login-left::after {
      content: '';
      position: absolute;
      width: 300px; height: 300px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 70%);
      bottom: -80px; right: -80px;
      animation: pulse 8s ease-in-out infinite reverse;
    }
    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 0.7; }
      50%       { transform: scale(1.15); opacity: 1; }
    }

    .brand-logo {
      font-size: 2.8rem;
      font-weight: 700;
      color: #fff;
      letter-spacing: -1px;
      margin-bottom: 8px;
      position: relative;
      z-index: 2;
    }
    .brand-logo span { color: #60a5fa; }

    .brand-tagline {
      color: rgba(255,255,255,0.55);
      font-size: 0.95rem;
      font-weight: 400;
      letter-spacing: 0.5px;
      margin-bottom: 50px;
      position: relative;
      z-index: 2;
    }

    .feature-list {
      list-style: none;
      position: relative;
      z-index: 2;
    }
    .feature-list li {
      display: flex;
      align-items: center;
      gap: 14px;
      color: rgba(255,255,255,0.75);
      font-size: 0.9rem;
      margin-bottom: 20px;
    }
    .feature-list li .icon-wrap {
      width: 38px; height: 38px;
      border-radius: 10px;
      background: rgba(96,165,250,0.15);
      border: 1px solid rgba(96,165,250,0.3);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      color: #60a5fa;
      font-size: 0.9rem;
    }

    /* ── Right Panel ── */
    .login-right {
      width: 420px;
      background: #ffffff;
      display: flex;
      flex-direction: column;
      justify-content: center;
      padding: 50px 48px;
      position: relative;
    }

    .login-right h2 {
      font-size: 1.7rem;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 6px;
    }
    .login-right p.subtitle {
      color: #64748b;
      font-size: 0.88rem;
      margin-bottom: 32px;
    }

    /* Form */
    .form-label {
      font-size: 0.82rem;
      font-weight: 600;
      color: #374151;
      margin-bottom: 6px;
      display: block;
    }

    .input-wrap {
      position: relative;
      margin-bottom: 18px;
    }
    .input-wrap .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #9ca3af;
      font-size: 0.85rem;
      pointer-events: none;
    }
    .input-wrap input {
      width: 100%;
      padding: 11px 14px 11px 38px;
      border: 1.5px solid #e2e8f0;
      border-radius: 10px;
      font-size: 0.9rem;
      color: #1e293b;
      background: #f8fafc;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      font-family: 'Inter', sans-serif;
    }
    .input-wrap input:focus {
      border-color: #3b82f6;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }
    .input-wrap input.is-invalid {
      border-color: #ef4444;
    }
    .invalid-feedback { color: #ef4444; font-size: 0.78rem; margin-top: 4px; display: block; }

    /* Toggle password */
    .toggle-pass {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #9ca3af;
      cursor: pointer;
      font-size: 0.85rem;
      background: none;
      border: none;
      padding: 0;
    }
    .toggle-pass:hover { color: #3b82f6; }

    /* Remember row */
    .row-options {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 24px;
    }
    .remember-label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.83rem;
      color: #374151;
      cursor: pointer;
    }
    .remember-label input[type="checkbox"] {
      width: 16px; height: 16px;
      accent-color: #3b82f6;
      cursor: pointer;
    }
    .forgot-link {
      font-size: 0.83rem;
      color: #3b82f6;
      text-decoration: none;
      font-weight: 500;
    }
    .forgot-link:hover { text-decoration: underline; }

    /* Submit Button */
    .btn-login {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #3b82f6, #2563eb);
      color: #fff;
      font-weight: 600;
      font-size: 0.95rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: opacity 0.2s, transform 0.1s;
      font-family: 'Inter', sans-serif;
      letter-spacing: 0.3px;
    }
    .btn-login:hover { opacity: 0.92; transform: translateY(-1px); }
    .btn-login:active { transform: translateY(0); }

    /* Alert */
    .alert-success {
      background: #ecfdf5; border: 1px solid #86efac; color: #166534;
      padding: 10px 14px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 18px;
    }

    /* Footer note */
    .login-footer {
      position: absolute;
      bottom: 20px;
      left: 0; right: 0;
      text-align: center;
      font-size: 0.75rem;
      color: #94a3b8;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .login-left { display: none; }
      .login-right { width: 100%; padding: 40px 28px; }
    }
  </style>
</head>

<body>

  <!-- Left Panel -->
  <div class="login-left">
    <!-- Logo -->
    <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}"
         alt="{{ config('app.name') }} Logo"
         style="width:100px; height:100px; object-fit:contain; border-radius:50%; background:rgba(255,255,255,0.1); padding:14px; margin-bottom:18px; box-shadow:0 0 40px rgba(96,165,250,0.4); position:relative; z-index:2;">
    <div class="brand-logo">
      Sandhya <span>ERP</span>
    </div>
    <p class="brand-tagline">Enterprise Resource Planning System</p>

    <!-- <ul class="feature-list">
      <li>
        <div class="icon-wrap"><i class="fas fa-cogs"></i></div>
        <span>Complete Machine & Operations Management</span>
      </li>
      <li>
        <div class="icon-wrap"><i class="fas fa-users"></i></div>
        <span>Employee Attendance & Payroll Tracking</span>
      </li>
      <li>
        <div class="icon-wrap"><i class="fas fa-industry"></i></div>
        <span>Production & Parts Inventory Control</span>
      </li>
      <li>
        <div class="icon-wrap"><i class="fas fa-chart-bar"></i></div>
        <span>Real-Time Reports & Analytics</span>
      </li>
    </ul> -->
  </div>

  <!-- Right Panel -->
  <div class="login-right">
    <h2>Welcome back 👋</h2>
    <p class="subtitle">Sign in to your account to continue</p>

    @if(session('status'))
      <div class="alert-success">{{ session('status') }}</div>
    @endif

    <form action="{{ route('login') }}" method="POST">
      @csrf

      <!-- Email -->
      <label class="form-label" for="email">Email Address</label>
      <div class="input-wrap">
        <i class="fas fa-envelope input-icon"></i>
        <input type="email"
               name="email"
               id="email"
               value="{{ old('email') }}"
               class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
               placeholder="you@company.com"
               autofocus>
      </div>
      @error('email')
        <span class="invalid-feedback" style="margin-top:-12px; margin-bottom:14px;">{{ $message }}</span>
      @enderror

      <!-- Password -->
      <label class="form-label" for="password">Password</label>
      <div class="input-wrap">
        <i class="fas fa-lock input-icon"></i>
        <input type="password"
               name="password"
               id="password"
               class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
               placeholder="Enter your password">
        <button type="button" class="toggle-pass" onclick="togglePass()">
          <i class="fas fa-eye" id="toggleIcon"></i>
        </button>
      </div>
      @error('password')
        <span class="invalid-feedback" style="margin-top:-12px; margin-bottom:14px;">{{ $message }}</span>
      @enderror

      <!-- Remember Me + Forgot -->
      <div class="row-options">
        <label class="remember-label">
          <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
          Remember me
        </label>
        @if(Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
        @endif
      </div>

      <button type="submit" class="btn-login">
        <i class="fas fa-sign-in-alt mr-1"></i> Sign In
      </button>
    </form>

    <div class="login-footer">
      &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
  </div>

  <script>
    function togglePass() {
      const input = document.getElementById('password');
      const icon  = document.getElementById('toggleIcon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    }
  </script>

</body>
</html>