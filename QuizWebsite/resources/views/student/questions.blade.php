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

<!-- Custom Modal -->
<div id="block-modal" class="modal-overlay hide">
    <div class="modal-box">
        <h3>⚠️ Action Blocked</h3>
        <p>You cannot go back. Submit to end the quiz.</p>
        <button id="close-modal">OK</button>
    </div>
</div>

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
            <input type="hidden" name="quiz_id" value="{{ $quiz->id }}" />
            <input type="hidden" name="student_id" value="{{ session('student_id') }}" />
            <input type="hidden" id="final-score" name="score" value="" />
            <button type="submit" id="submit-score">Submit</button>
        </form>
    </div>

    <!-- Feedback Section -->
    <div id="feedback" class="feedback hide"></div>
</main>

<!-- Laravel-passed Questions -->
<script>
    const questions = @json($questions);
    console.log(questions);
</script>

<!-- Custom JS Logic -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    let quizStarted = false;
    let modalOpen = false;

    const showBlockMessage = () => {
        modalOpen = true;
        document.getElementById('block-modal').classList.remove('hide');
    };

    const hideBlockMessage = () => {
        modalOpen = false;
        document.getElementById('block-modal').classList.add('hide');
    };

    document.getElementById('close-modal').addEventListener('click', () => {
        hideBlockMessage();
    });

    document.getElementById('start').addEventListener('click', function () {
        quizStarted = true;
        history.pushState(null, null, location.href);
        // You can add quiz start logic here as needed
    });

    document.getElementById('submit-score').addEventListener('click', function () {
        quizStarted = false;
        // You can add submit logic here as needed
    });

    window.addEventListener('popstate', function () {
        if (quizStarted) {
            // Always show modal and re-push state to prevent back navigation escape
            showBlockMessage();
            history.pushState(null, null, location.href);
        }
    });

    // Block certain key shortcuts when quiz is running
    document.addEventListener('keydown', function (e) {
        if (!quizStarted) return;

        if (e.key === 'Backspace' && !['INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
            e.preventDefault();
            showBlockMessage();
        }

        if (
            e.key === 'F5' || e.key === 'F12' ||
            (e.ctrlKey && ['r', 'R', 'u', 'U', 's', 'S', 'i', 'I'].includes(e.key)) ||
            (e.key === 'ArrowLeft' && e.altKey)
        ) {
            e.preventDefault();
            showBlockMessage();
        }
    });

    // Block right-click when quiz is running
    document.addEventListener('contextmenu', function (e) {
        if (quizStarted) {
            e.preventDefault();
            showBlockMessage();
        }
    });

    // Tab visibility change tracking
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

<!-- Your Quiz Logic -->
<script src="{{ asset('script.js') }}"></script>

</body>
</html>
