<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('styles.css') }}" />
</head>
<body>
    <header>
        <div class="timer">
            <p>Time: <span id="timer">0</span></p>
        </div>
    </header>

    <main class="quiz">
        <div id="quiz-start">
            <div class="landing" id="start-screen">
                <h1>{{ $quiz->title }}</h1>
                <p>Try to answer the following questions within the time limit.</p>
                <button id="start">Start Quiz</button>
            </div>
        </div>

        <div class="hide" id="questions">
            <h2 id="question-words"></h2>
            <div class="options" id="options"></div>
        </div>

        <div class="hide" id="quiz-end">
            <h2>All Done!</h2>
            <p>Your final score is: <span id="score-final"></span></p>
            <form action="{{ route('result.store') }}" method="POST">
                @csrf
                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                <input type="hidden" name="student_id" value="{{ session('student_id') }}">
                <input type="hidden" id="final-score" name="score" value="">
                <button type="submit" id="submit-score">Submit</button>
            </form>
        </div>

        <div id="feedback" class="feedback hide"></div>
    </main>

    <script>
        const questions = @json($quiz->questions); 
        console.log(questions);
    </script>
    <script src="{{ asset('script.js') }}"></script>

    <script>
        // Prevent browser back button during quiz
        history.pushState(null, null, location.href);
        window.addEventListener('popstate', function () {
            history.pushState(null, null, location.href);
            alert("You cannot go back during the quiz.");
        });
    </script>
</body>
</html>
