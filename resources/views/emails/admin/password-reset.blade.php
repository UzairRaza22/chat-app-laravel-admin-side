<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 100%;
            margin: 0 auto;
            padding: 10px;
            background-color: #f4f4f4;
            box-sizing: border-box;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .content {
            margin-bottom: 25px;
        }
        .token-box {
            background-color: #f8f9fa;
            border: 2px solid #007bff;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            border-radius: 8px;
            box-sizing: border-box;
        }
        .token-box h3 {
            color: #007bff;
            margin: 0 0 15px 0;
            font-size: 18px;
        }
        .token-display {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: bold;
            color: #333;
            letter-spacing: 1px;
            background-color: #fff;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #ddd;
            word-break: break-all;
            box-sizing: border-box;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            box-sizing: border-box;
        }
        .instructions {
            color: #666;
            margin: 20px 0;
        }
        .instructions ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 8px 0;
        }
        .api-example {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            box-sizing: border-box;
        }
        .api-example h4 {
            margin: 0 0 10px 0;
            color: #495057;
            font-size: 16px;
        }
        .api-example pre {
            margin: 0;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            font-size: 12px;
            overflow-x: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
            box-sizing: border-box;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 25px;
            box-sizing: border-box;
        }
        .footer p {
            margin: 5px 0;
        }
        
        /* Responsive Design */
        @media screen and (max-width: 600px) {
            body {
                padding: 5px;
            }
            .container {
                padding: 15px;
                border-radius: 0;
            }
            .logo {
                font-size: 20px;
            }
            .token-box {
                padding: 15px;
            }
            .token-display {
                font-size: 14px;
                padding: 10px;
                letter-spacing: 0.5px;
            }
            .api-example pre {
                font-size: 11px;
                padding: 10px;
            }
            .instructions {
                font-size: 14px;
            }
        }
        
        @media screen and (max-width: 400px) {
            .container {
                padding: 10px;
            }
            .logo {
                font-size: 18px;
            }
            .token-box {
                padding: 10px;
            }
            .token-display {
                font-size: 12px;
                padding: 8px;
            }
            .api-example pre {
                font-size: 10px;
                padding: 8px;
            }
            .instructions {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Admin Password Reset</div>
        </div>
        
        <div class="content">
            <h2>Hello {{ $name }},</h2>
            
            <p>You have requested to reset your admin password. Use the verification token below to reset your password:</p>
            
            <div class="token-box">
                <h3>Password Reset Token</h3>
                <div class="token-display">
                    {{ $token }}
                </div>
            </div>
            
            <div class="warning">
                <strong>Security Notice:</strong> This password reset token will expire in 60 minutes. If you didn't request this password reset, please ignore this email.
            </div>
            
            <div class="instructions">
                <p>To use this token:</p>
                <ol>
                    <li>Copy the token shown above</li>
                    <li>Call the reset password API: <code>POST /api/admin/auth/reset-password</code></li>
                    <li>Use the token in your JSON request body</li>
                </ol>
            </div>
            
            <div class="api-example">
                <h4>API Request Example:</h4>
                <pre>POST /api/admin/auth/reset-password
Content-Type: application/json

{
    "token": "{{ $token }}",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}</pre>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Admin System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
