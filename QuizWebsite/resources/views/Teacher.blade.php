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
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-weight: bold;
        }
        nav a:hover {
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
        <div>
            <a href="{{ route('quiz.list') }}">View Quizzes</a>
            <a href="#">Logout</a>
        </div>
    </nav>

    <h1>Create a New Quiz</h1>

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

    <!-- Check if quiz_id is set in session -->
    @if (session('quiz_id'))
    <!-- Form to add questions (only visible if quiz_id is set in session) -->
    <div class="section-header">
        <h2>Add Questions to Your Quiz</h2>
    </div>

    <form action="{{ route('storeQuestion') }}" method="POST">
        @csrf
        <!-- Pass the Quiz ID -->
        <input type="hidden" name="quiz_id" value="{{ session('quiz_id') }}">
    
        <!-- Question Text -->
        <div class="form-group">
            <label for="question_text">Question Text</label>
            <input type="text" id="question_text" name="question_text" class="form-control" required>
        </div>
    
        <!-- Options -->
        <div class="form-group">
            <label>Options</label>
            <div class="form-group">
                <input type="text" name="options[1]" class="form-control mb-2" placeholder="Option 1" required>
                <input type="text" name="options[2]" class="form-control mb-2" placeholder="Option 2" required>
                <input type="text" name="options[3]" class="form-control mb-2" placeholder="Option 3" required>
                <input type="text" name="options[4]" class="form-control mb-2" placeholder="Option 4" required>
            </div>
        </div>
    
        <!-- Correct Option -->
        <div class="form-group">
            <label for="correct_option">Correct Option (e.g., 1 for the first option)</label>
            <input type="number" id="correct_option" name="correct_option" class="form-control" min="1" max="4" required>
        </div>
    
        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary mt-2">Add Question</button>
    </form>
    @else
    <div class="message">
        <strong>Please create a quiz first to add questions.</strong>
    </div>
    @endif

</body>
</html>
