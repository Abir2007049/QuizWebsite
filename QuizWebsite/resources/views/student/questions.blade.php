<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quiz->title }} - Quiz</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <header>
        <div class="timer">
            <p>Time: <span id="timer">0</span></p>
        </div>
    </header>

    <main class="quiz">
        <!-- Start Screen -->
        <div id="quiz-start">
            <div class="landing" id="start-screen">
                <h1>{{ $quiz->title }}</h1>
                <p>Try to answer the following questions within the time limit.</p>
                <button id="start">Start Quiz</button>
            </div>
        </div>

        <!-- Questions Container -->
        <div class="hide" id="questions">
            <h2 id="question-words"></h2>
            <div class="options" id="options"></div>
        </div>

        <!-- End Screen -->
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

        <!-- Feedback Section -->
        <div id="feedback" class="feedback hide"></div>
    </main>

    <!-- Laravel-passed Questions -->
    <script>
        const questions = @json($questions);  // ✅ Fixed: use $questions
        console.log(questions);
    </script>

    <!-- External Quiz Logic -->
    <script src="{{ secure_asset('script.js') }}"></script>

    <script>
        let quizStarted = false;

        document.getElementById('start').addEventListener('click', function () {
            quizStarted = true;
        });

        document.addEventListener("visibilitychange", function () {
            if (!quizStarted) return;

            fetch('/report-tab-switch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    student_id: {{ session('student_id') }},
                    quiz_id: {{ $quiz->id }},
                    state: document.hidden ? 'hidden' : 'visible',
                    time: new Date().toISOString()
                })
            });
        });

        document.getElementById('submit-score').addEventListener('click', function () {
            quizStarted = false;
        });

        // Prevent browser back button during quiz
        history.pushState(null, null, location.href);
        window.addEventListener('popstate', function () {
            history.pushState(null, null, location.href);
            alert("You cannot go back during the quiz.");
        });
    </script>

     <script src="{{ asset('script.js') }}"></script>
</body>
</html>
