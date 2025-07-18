<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Quiz Violation Alert</title>
    <style>
        /* Basic email styles for better compatibility */
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f9fc;
            margin: 0; padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            background-color: #ffffff;
            margin: 0 auto;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .header img {
            max-width: 150px;
        }
        h1 {
            color: #004085;
        }
        .content {
            margin-top: 20px;
            font-size: 16px;
            line-height: 1.5;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: white !important;
            padding: 10px 20px;
            margin-top: 20px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/your-logo.png') }}" alt="Quiz App Logo" />
        </div>

        <h1>Quiz Violation Alert</h1>

        <div class="content">
            <p>Dear Student,</p>
            <p>We have detected a quiz violation associated with ID: <strong>{{ $studentId }}</strong>.</p>
          
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Quiz App. All rights reserved.<br/>
            1234 Quiz St, Knowledge City, Country
        </div>
    </div>
</body>
</html>

