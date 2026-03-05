<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Admin Email - Whistle IT</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        .logo {
            font-size: 36px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .logo::before {
            content: '👑';
            margin-right: 10px;
        }
        .header h2 {
            color: #ffffff;
            font-size: 24px;
            font-weight: 300;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-message {
            text-align: center;
            margin-bottom: 30px;
        }
        .welcome-message h3 {
            color: #2c3e50;
            font-size: 20px;
            margin-bottom: 10px;
        }
        .admin-badge {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .otp-container {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 2px dashed #e74c3c;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-label {
            font-size: 14px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #e74c3c;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
            background: #ffffff;
            padding: 25px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.2);
            word-break: break-all;
            line-height: 1.4;
        }
        .footer {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 30px;
            text-align: center;
        }
        .footer .brand {
            font-weight: bold;
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Whistle IT Admin</div>
            <h2>Admin Email Verification Required</h2>
        </div>

        <div class="content">
            <div class="welcome-message">
                <div class="admin-badge">Admin Account</div>
                <h3>Hello {{ $name ?? 'Administrator' }}! 👑</h3>
                <p>Thank you for registering as an admin on Whistle IT!</p>
            </div>

            <p>To activate your admin account, please verify your email using the verification code below:</p>

            <div class="otp-container">
                <div class="otp-label">Your Admin Verification Code</div>
                <div class="otp-code">{{ $otp }}</div>
            </div>

            <p style="text-align: center; color: #6c757d; font-style: italic;">
                If you didn't request this admin registration, you can safely ignore this email.
            </p>
        </div>

        <div class="footer">
            <p class="brand">👑 Whistle IT Admin</p>
            <p>© {{ date('Y') }} Whistle IT. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
