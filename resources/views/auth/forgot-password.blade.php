<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }} | Forgot Password</title>

  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">

  <style>
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

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

    .login-left::before {
      content: '';
      position: absolute;
      width: 400px;
      height: 400px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(59, 130, 246, 0.25) 0%, transparent 70%);
      top: -100px;
      left: -100px;
      animation: pulse 6s ease-in-out infinite;
    }

    .login-left::after {
      content: '';
      position: absolute;
      width: 300px;
      height: 300px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
      bottom: -80px;
      right: -80px;
      animation: pulse 8s ease-in-out infinite reverse;
    }

    @keyframes pulse {

      0%,
      100% {
        transform: scale(1);
        opacity: 0.7;
      }

      50% {
        transform: scale(1.15);
        opacity: 1;
      }
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

    .brand-logo span {
      color: #60a5fa;
    }

    .brand-tagline {
      color: rgba(255, 255, 255, 0.55);
      font-size: 0.95rem;
      font-weight: 400;
      letter-spacing: 0.5px;
      position: relative;
      z-index: 2;
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
      margin-bottom: 28px;
      line-height: 1.6;
    }

    .form-label {
      font-size: 0.82rem;
      font-weight: 600;
      color: #374151;
      margin-bottom: 6px;
      display: block;
    }

    .input-wrap {
      position: relative;
      margin-bottom: 22px;
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
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .invalid-feedback {
      color: #ef4444;
      font-size: 0.78rem;
      margin-top: 4px;
      display: block;
    }

    .btn-submit {
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

    .btn-submit:hover {
      opacity: 0.92;
      transform: translateY(-1px);
    }

    .btn-submit:active {
      transform: translateY(0);
    }

    .alert-success {
      background: #ecfdf5;
      border: 1px solid #86efac;
      color: #166534;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 0.85rem;
      margin-bottom: 18px;
      line-height: 1.5;
    }

    .back-link {
      text-align: center;
      margin-top: 22px;
      font-size: 0.84rem;
      color: #64748b;
    }

    .back-link a {
      color: #3b82f6;
      font-weight: 500;
      text-decoration: none;
    }

    .back-link a:hover {
      text-decoration: underline;
    }

    .login-footer {
      position: absolute;
      bottom: 20px;
      left: 0;
      right: 0;
      text-align: center;
      font-size: 0.75rem;
      color: #94a3b8;
    }

    @media (max-width: 768px) {
      .login-left {
        display: none;
      }

      .login-right {
        width: 100%;
        padding: 40px 28px;
      }
    }
  </style>
</head>

<body>

  <!-- Left Panel -->
  <div class="login-left">
    <!-- Logo -->
    <img src="{{ asset('adminlte/dist/img/AdminLTELogo.png') }}" alt="{{ config('app.name') }} Logo"
      style="width:100px; height:100px; object-fit:contain; border-radius:50%; background:rgba(255,255,255,0.1); padding:14px; margin-bottom:18px; box-shadow:0 0 40px rgba(96,165,250,0.4); position:relative; z-index:2;">
    <div class="brand-logo">
      Sandhya <span>ERP</span>
    </div>
    <p class="brand-tagline">Enterprise Resource Planning System</p>
  </div>

  <!-- Right Panel -->
  <div class="login-right">
    <h2>Forgot Password? 🔐</h2>
    <p class="subtitle">Enter your registered email address and we'll send you a password reset link.</p>

    @if (session('status'))
      <div class="alert-success">
        <i class="fas fa-check-circle mr-1"></i> {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf

      <label class="form-label" for="email">Email Address</label>
      <div class="input-wrap">
        <i class="fas fa-envelope input-icon"></i>
        <input type="email" name="email" id="email" value="{{ old('email') }}"
          class="{{ $errors->has('email') ? 'is-invalid' : '' }}" placeholder="you@company.com" autofocus>
      </div>
      @error('email')
        <span class="invalid-feedback" style="margin-top:-16px; margin-bottom:14px;">{{ $message }}</span>
      @enderror

      <button type="submit" class="btn-submit">
        <i class="fas fa-paper-plane mr-1"></i> Send Reset Link
      </button>
    </form>

    <div class="back-link">
      <a href="{{ route('login') }}"><i class="fas fa-arrow-left mr-1"></i> Back to Sign In</a>
    </div>

    <div class="login-footer">
      &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
  </div>

  <!-- jQuery -->
  <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>