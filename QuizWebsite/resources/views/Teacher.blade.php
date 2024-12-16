<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav .nav-links {
            display: flex;
            align-items: center;
        }
        nav a, nav .logout-btn {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
        }
        nav a:hover, nav .logout-btn:hover {
            text-decoration: underline;
        }
        h1 {
            text-align: center;
            color: #333;
            margin: 20px 0;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            background-color: #ffecb3;
            color: #d9534f;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }
        hr {
            margin: 30px 0;
        }
        .section-header {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav>
        <div>
            <a href="#">Home</a>
        </div>
        <div class="nav-links">
        <a href="{{ route('quiz.list') }}">View Quizzes</a>


            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>

    <h1>Create a New Quiz</h1>
    <!-- jskdj -->

    <!-- Form 1: Create Quiz -->
    <form action="{{ route('storeQuiz') }}" method="POST">
        @csrf

        <div class="section-header">
            <h2>Create Quiz</h2>
        </div>

        <!-- Quiz Title -->
        <label for="quiz_title">Quiz Title:</label>
        <input type="text" id="quiz_title" name="quiz_title" required>
        <br>

        <!-- Submit Quiz -->
        <button type="submit">Submit Quiz</button>
    </form>

    <hr>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


  
    
</body>
</html>
