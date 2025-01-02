<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: #28a745;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav .nav-links {
            display: flex;
            align-items: center;
        }
        nav a, nav .logout-link {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
            cursor: pointer;
        }
        nav a:hover, nav .logout-link:hover {
            text-decoration: underline;
        }
        h1 {
            text-align: center;
            color: #333;
            margin: 20px 0;
        }
        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #218838;
        }
        .alert {
            max-width: 600px;
            margin: 20px auto;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        hr {
            margin: 30px 0;
            border: 0;
            border-top: 1px solid #ddd;
        }
        .section-header {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            color: #333;
            font-weight: bold;
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
            <!-- Logout link as an anchor tag -->
            <a href="{{ route('logout') }}" class="logout-link">Logout</a>
        </div>
    </nav>

    <!-- Page Title -->
    <h1>Create a New Quiz</h1>

    <!-- Form: Create Quiz -->
    <form action="{{ route('storeQuiz') }}" method="POST">
        @csrf

        <div class="section-header">
            Create Quiz
        </div>

        <!-- Quiz Title -->
        <label for="quiz_title">Quiz Title:</label>
        <input type="text" id="quiz_title" name="quiz_title" placeholder="Enter quiz title" required>

        <!-- Submit Button -->
        <button type="submit">Submit Quiz</button>
    </form>

    <!-- Success Message -->
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

</body>
</html>
