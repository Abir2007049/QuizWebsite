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
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
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

        <!-- Hidden input for quiz_id -->
        <input type="hidden" name="quiz_id" value="{{ session('quiz_id') }}">

        <!-- Question Text -->
        <label for="question_text">Question:</label>
        <input type="text" id="question_text" name="question_text" required>
        <br><br>

        <!-- Options -->
        <label for="option_1">Option 1:</label>
        <input type="text" id="option_1" name="options[1]" required>
        <br>
        <label for="option_2">Option 2:</label>
        <input type="text" id="option_2" name="options[2]" required>
        <br>
        <label for="option_3">Option 3:</label>
        <input type="text" id="option_3" name="options[3]" required>
        <br>
        <label for="option_4">Option 4:</label>
        <input type="text" id="option_4" name="options[4]" required>
        <br><br>

        <!-- Correct Option -->
        <label for="correct_option">Correct Option:</label>
        <select id="correct_option" name="correct_option" required>
            <option value="1">Option 1</option>
            <option value="2">Option 2</option>
            <option value="3">Option 3</option>
            <option value="4">Option 4</option>
        </select>
        <br><br>

        <!-- Add Question Button -->
        <button type="submit">Add Question</button>
    </form>
@else
    <div class="message">
        <strong>Please create a quiz first to add questions.</strong>
    </div>
@endif

</body>
</html>
