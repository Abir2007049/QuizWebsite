
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Quizzes for {{ $teacher->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #28a745;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #28a745;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        a {
            color: #28a745;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .status {
            font-weight: bold;
        }
        .status.upcoming {
            color: #ffc107;
        }
        .status.finished {
            color: #dc3545;
        }
        .status.running {
            color: #28a745;
        }
        #broadcast-data {
            margin-top: 30px;
            padding: 20px;
            background-color: #fff;
            border: 2px solid #28a745;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<h1>Quizzes for Room: {{ $teacher->room_name }}</h1>

@php
    $dhakaTime = \Carbon\Carbon::now('Asia/Dhaka');
@endphp

@if ($quizzes->isEmpty())
    <p>No quizzes available in this room.</p>
@else
    <table>
        <thead>
            <tr>
                <th>Quiz Title</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quizzes as $quiz)
                @php
                    $startDatetime = \Carbon\Carbon::parse($quiz->start_datetime)->setTimezone('Asia/Dhaka');
                    $endDatetime = $startDatetime->copy()->addMinutes($quiz->duration);
                @endphp
                <tr>
                    <td>{{ $quiz->title }}</td>
                    <td>
                        <span class="quiz-status status"
                              id="quiz-status-{{ $quiz->id }}"
                              data-id="{{ $quiz->id }}"
                              data-start="{{ $startDatetime->toIso8601String() }}"
                              data-end="{{ $endDatetime->toIso8601String() }}">
                            @if ($dhakaTime->lt($startDatetime))
                                <span class="status upcoming">The quiz will be available on {{ $startDatetime->format('F j, Y, g:i A') }}.</span>
                            @elseif ($dhakaTime->gt($endDatetime))
                                <span class="status finished">Finished</span>
                            @else
                                <a href="{{ route('quiz.take', ['id' => $quiz->id, 'student_id' => $student_id]) }}" class="status running">Running</a>
                            @endif
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif



<script>
    function updateQuizStatus(quizId, startTime, endTime) {
        const now = new Date();
        const start = new Date(startTime);
        const end = new Date(endTime);
        const statusEl = document.getElementById(`quiz-status-${quizId}`);

        if (!statusEl) return;

        const quizLink = `/quiz/${quizId}/take`;  

        if (now < start) {
            statusEl.innerHTML = `<span class="status upcoming">The quiz will be available on ${start.toLocaleString()}</span>`;

           
            setTimeout(() => {
                statusEl.innerHTML = `<a href="${quizLink}" class="status running">Running</a>`;
            }, start - now);

            
            setTimeout(() => {
                statusEl.innerHTML = `<span class="status finished">Finished</span>`;
            }, end - now);

        } else if (now >= start && now < end) {
            statusEl.innerHTML = `<a href="${quizLink}" class="status running">Running</a>`;

            setTimeout(() => {
                statusEl.innerHTML = `<span class="status finished">Finished</span>`;
            }, end - now);

        } else {
            statusEl.innerHTML = `<span class="status finished">Finished</span>`;
        }
    }

    function initQuizTimers() {
        document.querySelectorAll('.quiz-status').forEach(el => {
            const id = el.dataset.id;
            const start = el.dataset.start;
            const end = el.dataset.end;
            updateQuizStatus(id, start, end);
        });
    }

   
    document.addEventListener("DOMContentLoaded", function () {
        initQuizTimers();
const room = "{{ session('room_name') }}";
       
        window.Echo.channel(`room.${room}`)
            .listen("QuizStatusUpdated", (e) => {
                const dataDiv = document.getElementById('broadcast-data');
                const quizId = e.quizId ?? 'Unknown';

                fetch(`/api/quiz/${quizId}/timing`)
                    .then(res => res.json())
                    .then(data => {
                        updateQuizStatus(quizId, data.start_datetime, data.end_datetime);
                    });

                
            });
    });
</script>

</body>
</html>