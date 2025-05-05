<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quiz->title }} - Quiz</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}" />
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
        const questions = @json($quiz->questions); 
        console.log(questions);
    </script>

    <!-- External Quiz Logic -->
    <script src="{{ asset('script.js') }}"></script>

    <script>
        // State to track if quiz is active
        let quizStarted = false;

        // Activate quiz tracking when quiz starts
        document.getElementById('start').addEventListener('click', function () {
            quizStarted = true;
        });

        // Listen for tab visibility change only when quiz is running
        document.addEventListener("visibilitychange", function () {
            if (!quizStarted) return;

            if (document.hidden) {
                sendTabEvent("hidden");
            } else {
                sendTabEvent("visible");
            }
        });

        function sendTabEvent(state) {
            fetch('/report-tab-switch', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    student_id: {{ session('student_id') }},
                    quiz_id: {{ $quiz->id }},
                    state: state,
                    time: new Date().toISOString()
                })
            });
        }

        // Deactivate tab tracking when user submits the score
        document.getElementById('submit-score').addEventListener('click', function () {
            quizStarted = false;
        });
    </script>
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
