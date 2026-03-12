<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Password Reset</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #222;
            margin: 0;
            padding: 24px;
        }

        .card {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        }

        .button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 20px;
            background: #1877f2;
            color: #fff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }

        .muted {
            color: #666;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Reset Your Password</h2>
        <p>We received a request to reset the password for <strong>{{ $email }}</strong>.</p>
        <p>Click the button below to choose a new password:</p>

        <a class="button" href="{{ $resetUrl }}">Reset Password</a>

        <p class="muted">If you did not request a password reset, you can ignore this email.</p>
        <p class="muted">If the button does not work, copy and paste this link into your browser:</p>
        <p class="muted">{{ $resetUrl }}</p>
    </div>
</body>
</html>