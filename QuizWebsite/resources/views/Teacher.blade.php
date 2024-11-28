<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz</title>
</head>
<body>
    <h1>Create a New Quiz</h1>

    <!-- Form for creating a quiz -->
    <form action="{{ route('storeQuiz') }}" method="POST">
        @csrf
        
        <!-- Quiz Title -->
        <label for="quiz_title">Quiz Title:</label>
        <input type="text" id="quiz_title" name="quiz_title" required>
        <br><br>

        <!-- Questions Section -->
        <div id="questions-section">
            <h2>Questions</h2>
            
            <!-- First Question -->
            <div class="question" id="question_1">
                <label for="question_1_text">Question:</label>
                <input type="text" id="question_1_text" name="questions[1][text]" required>
                <br>

                <!-- Options for the question -->
                <div class="options">
                    <h3>Options:</h3>
                    <label for="option_1_1">Option 1:</label>
                    <input type="text" id="option_1_1" name="questions[1][options][1]" required>
                    <br>
                    
                    <label for="option_1_2">Option 2:</label>
                    <input type="text" id="option_1_2" name="questions[1][options][2]" required>
                    <br>

                    <label for="option_1_3">Option 3:</label>
                    <input type="text" id="option_1_3" name="questions[1][options][3]" required>
                    <br>

                    <label for="option_1_4">Option 4:</label>
                    <input type="text" id="option_1_4" name="questions[1][options][4]" required>
                    <br>

                    <!-- Select the correct option -->
                    <label for="correct_option_1">Correct Option:</label>
                    <select id="correct_option_1" name="questions[1][correct_option]" required>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                        <option value="4">Option 4</option>
                    </select>
                </div>
                <br>
            </div>
        </div>

        <!-- Button to add more questions -->
        <button type="button" id="add-question">Add Another Question</button>
        <br><br>

        <!-- Submit Quiz -->
        <button type="submit">Submit Quiz</button>
    </form>

    <script>
        // JavaScript to dynamically add more questions
        document.getElementById('add-question').addEventListener('click', function () {
            const questionsSection = document.getElementById('questions-section');
            const questionCount = questionsSection.getElementsByClassName('question').length + 1;

            const questionDiv = document.createElement('div');
            questionDiv.classList.add('question');
            questionDiv.id = `question_${questionCount}`;

            questionDiv.innerHTML = `
                <label for="question_${questionCount}_text">Question:</label>
                <input type="text" id="question_${questionCount}_text" name="questions[${questionCount}][text]" required>
                <br>
                <div class="options">
                    <h3>Options:</h3>
                    <label for="option_${questionCount}_1">Option 1:</label>
                    <input type="text" id="option_${questionCount}_1" name="questions[${questionCount}][options][1]" required>
                    <br>
                    <label for="option_${questionCount}_2">Option 2:</label>
                    <input type="text" id="option_${questionCount}_2" name="questions[${questionCount}][options][2]" required>
                    <br>
                    <label for="option_${questionCount}_3">Option 3:</label>
                    <input type="text" id="option_${questionCount}_3" name="questions[${questionCount}][options][3]" required>
                    <br>
                    <label for="option_${questionCount}_4">Option 4:</label>
                    <input type="text" id="option_${questionCount}_4" name="questions[${questionCount}][options][4]" required>
                    <br>
                    <label for="correct_option_${questionCount}">Correct Option:</label>
                    <select id="correct_option_${questionCount}" name="questions[${questionCount}][correct_option]" required>
                        <option value="1">Option 1</option>
                        <option value="2">Option 2</option>
                        <option value="3">Option 3</option>
                        <option value="4">Option 4</option>
                    </select>
                </div>
                <br>
            `;

            questionsSection.appendChild(questionDiv);
        });
    </script>
</body>
</html>
