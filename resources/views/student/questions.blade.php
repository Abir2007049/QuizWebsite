<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $quiz->title }} - Quiz</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        body { background: linear-gradient(to bottom right, #0f0f1a, #1a1a2e); color: #e0e0e0; font-family: 'Segoe UI', sans-serif; }
        .btn { background: linear-gradient(to right, #6e57e0, #8f57ea); color: white; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 0.5rem; transition: all 0.3s ease; }
        .btn:hover { background: linear-gradient(to right, #7c64f1, #9d67f4); transform: scale(1.05); }
        .hide { display: none; }
        .quiz-container { max-width: 700px; margin: auto; }
        .options button { display: block; width: 100%; margin-top: 0.75rem; padding: 0.75rem; border-radius: 0.5rem; background-color: #27293d; color: #d1d5db; transition: all 0.3s ease; }
        .options button:hover { background-color: #3b3f5c; color: #fff; }
    </style>
</head>
<body class="px-4 py-10">

    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-4xl font-extrabold text-white leading-tight mb-6">{{ $quiz->title }}</h1>
        <p class="text-gray-400 mt-2">Try to answer all the questions within the time limit</p>
    </div>

    <main class="quiz-container space-y-10">
        <!-- Start Screen -->
        <div id="quiz-start">
            <div id="start-screen" class="text-center space-y-4">
                <h2 class="text-3xl font-bold text-white">Ready to begin?</h2>
                <button id="start" class="btn">Start Quiz</button>
            </div>
        </div>

        <!-- Questions Container -->
        <div id="questions" class="hide space-y-6">
            <h2 id="question-words" class="text-2xl font-semibold text-indigo-300 mb-4"></h2>
            <div class="options" id="options"></div>
        </div>

        <!-- End Screen -->
        <div id="quiz-end" class="hide text-center space-y-4">
            <h2 class="text-3xl font-bold text-green-400">All Done!</h2>
            <form id="quiz-form" action="{{ route('result.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                <input type="hidden" id="student_id" name="student_id" value="{{ session('student_id') }}">
                <div id="answers-container" class="space-y-2"></div>
                <button type="submit" class="btn">Submit</button>
            </form>
        </div>
    </main>

    <!-- Timer -->
    <div class="fixed top-4 right-4 bg-indigo-600 text-white px-4 py-2 rounded-lg shadow-lg text-sm font-semibold z-50">
        Time Left: <span id="timer">0</span>
    </div>

    <!-- Pass PHP data to JS -->
    <script>
        window.questions = @json($questions);
        window.quizId = {{ $quiz->id }};
        window.studentId = {{ session('student_id') }};
    </script>

    <!-- External Quiz Logic -->
    <script src="{{ asset('script.js') }}"></script>
</body>
</html>
