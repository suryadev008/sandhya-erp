<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Locked</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .lock-container {
            text-align: center;
            padding: 50px 40px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        .lock-icon {
            font-size: 80px;
            color: #e74c3c;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50%       { transform: scale(1.05); opacity: 0.8; }
        }

        h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #e74c3c;
        }

        .subtitle {
            font-size: 16px;
            color: #bdc3c7;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .info-box {
            background: rgba(231, 76, 60, 0.15);
            border: 1px solid rgba(231, 76, 60, 0.3);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .info-box p {
            font-size: 14px;
            color: #ecf0f1;
            line-height: 1.8;
        }

        .info-box strong {
            color: #e74c3c;
        }

        .unlock-form {
            margin-top: 20px;
        }

        .unlock-form h3 {
            font-size: 14px;
            color: #95a5a6;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-group {
            display: flex;
            gap: 10px;
        }

        .unlock-form input[type="password"] {
            flex: 1;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s;
        }

        .unlock-form input[type="password"]::placeholder {
            color: #7f8c8d;
        }

        .unlock-form input[type="password"]:focus {
            border-color: #3498db;
        }

        .unlock-btn {
            padding: 12px 20px;
            background: #3498db;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
            white-space: nowrap;
        }

        .unlock-btn:hover { background: #2980b9; }

        .error-msg {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 10px;
        }

        .success-msg {
            color: #2ecc71;
            font-size: 13px;
            margin-top: 10px;
        }

        .contact-info {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 13px;
            color: #7f8c8d;
        }

        .contact-info a {
            color: #3498db;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="lock-container">
        <div class="lock-icon">
            <i class="fas fa-lock"></i>
        </div>

        <h1>System Locked</h1>
        <p class="subtitle">Sandhya ERP ka access band kar diya gaya hai.</p>

        <div class="info-box">
            <p>
                <strong>Karan:</strong> Payment ki due date <strong>30 April 2026</strong> tak payment nahi aayi.<br><br>
                System dobara activate karne ke liye please contact karein.
            </p>
        </div>

        @if(session('unlock_error'))
            <div class="error-msg"><i class="fas fa-times-circle"></i> {{ session('unlock_error') }}</div>
        @endif

        <div class="unlock-form">
            <h3><i class="fas fa-key"></i> &nbsp;Unlock Key Darj Karein</h3>
            <form method="POST" action="{{ route('system.unlock') }}">
                @csrf
                <div class="input-group">
                    <input type="password" name="unlock_key" placeholder="Secret unlock key..." required autofocus>
                    <button type="submit" class="unlock-btn">
                        <i class="fas fa-unlock"></i> Unlock
                    </button>
                </div>
            </form>
        </div>

        <div class="contact-info">
            <i class="fas fa-phone"></i> Support ke liye contact karein
        </div>
    </div>
</body>
</html>
