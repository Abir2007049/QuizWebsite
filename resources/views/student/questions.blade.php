<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $quiz->title }} - Quiz</title>
    <link rel="stylesheet" href="{{ asset('styles.css') }}" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        .modal-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .modal-box {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }
        .modal-box h3 {
            margin-top: 0;
            font-size: 1.2rem;
        }
        .modal-box button {
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            background-color: #f44336;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .hide {
            display: none;
        }
    </style>
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
        <form id="quiz-form" action="{{ route('result.store') }}" method="POST">
            @csrf
            <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
            <input type="hidden" name="student_id" value="{{ session('student_id') }}">
            <div id="answers-container"></div>
            <button type="submit">Submit</button>
        </form>
    </div>

    <!-- Feedback Section -->
    <div id="feedback" class="feedback hide"></div>
</main>

<script>
    const questions = @json($questions);
</script>

<script>
    let quizStarted = false;

    document.addEventListener('DOMContentLoaded', () => {
        const startBtn = document.getElementById('start');
        const form = document.getElementById('quiz-form');

        // Start button clicked
        startBtn.addEventListener('click', () => {
            quizStarted = true;

            // Show leave confirmation on back/refresh
            window.onbeforeunload = function () {
                return "Are you sure you want to leave? Your progress will be lost.";
            };
        });

        // Disable leave confirmation if quiz is submitted
        form.addEventListener('submit', () => {
            window.onbeforeunload = null;
        });

        // Optional: Report tab switch
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
    });
</script>

<script src="{{ asset('script.js') }}"></script>

</body>
</html>
