<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz</title>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const timerElement = document.getElementById('timer');
        let timeLeft = {{ $quiz->duration * 60 }}; // Convert duration to seconds

        function startTimer() {
            const interval = setInterval(() => {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;

                timerElement.innerText = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                timeLeft--;

                if (timeLeft < 0) {
                    clearInterval(interval);
                    document.getElementById('quiz-form').submit(); // Auto-submit on timeout
                }
            }, 1000);
        }

        startTimer();
    });
</script>

</head>
<body>
    <h1>{{ $quiz->title }}</h1>
    <div>
        <strong>Time Left: </strong><span id="timer"></span>
    </div>

    <form id="quiz-form" action="{{ route('quiz.submit', ['quiz' => $quiz->id, 'student' => $student_id]) }}" method="POST">
        @csrf
    
        @foreach ($quiz->questions as $question)
            <div>
                <p>{{ $question->text }}</p>
                @for ($i = 1; $i <= 4; $i++)
                    <label>
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $i }}" required>
                        {{ $question->{'option' . $i} }}
                    </label><br>
                @endfor
            </div>
        @endforeach
        <button type="submit">Submit</button>
    </form>
    
 </body>  <!-- jchdshdhdu -->
</html>
