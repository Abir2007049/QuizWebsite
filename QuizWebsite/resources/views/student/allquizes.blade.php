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
    </style>

    <!-- Echo + Reverb -->
    
</head>
<body>

    <h1>Quizzes for Room: {{ $teacher->room_name }}</h1>

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
                    $dhakaTime = \Carbon\Carbon::now('Asia/Dhaka');
                    $endDatetime = $startDatetime->copy()->addMinutes($quiz->duration);
                    $quizId = $quiz->id;
                @endphp
                <tr>
                    <td>{{ $quiz->title }}</td>
                    <td>
                        <span id="quiz-status-{{ $quizId }}">
                            @if ($dhakaTime->lt($startDatetime))
                                <p class="status upcoming">The quiz will be available on {{ $startDatetime->format('F j, Y, g:i A') }}.</p>
                            @elseif ($dhakaTime->gt($endDatetime))
                                <p class="status finished">Finished</p>
                            @else
                                <a href="{{ route('quiz.take', ['id' => $quizId, 'student_id' => $student_id]) }}" class="status running">Running</a>
                            @endif
                        </span>

                        <script>
                            const statusElem{{ $quizId }} = document.getElementById("quiz-status-{{ $quizId }}");
                            const startTime{{ $quizId }} = new Date("{{ $startDatetime->format('Y-m-d\TH:i:s') }}");
                            const endTime{{ $quizId }} = new Date("{{ $endDatetime->format('Y-m-d\TH:i:s') }}");

                            const runningLink{{ $quizId }} = `<a href='{{ route('quiz.take', ['id' => $quizId, 'student_id' => $student_id]) }}' class='status running'>Running</a>`;
                            const upcomingText{{ $quizId }} = `<p class='status upcoming'>The quiz will be available on {{ $startDatetime->format('F j, Y, g:i A') }}.</p>`;
                            const finishedText{{ $quizId }} = `<p class='status finished'>Finished</p>`;

                            function updateStatus{{ $quizId }}() {
                                const now = new Date();
                                if (now < startTime{{ $quizId }}) {
                                    statusElem{{ $quizId }}.innerHTML = upcomingText{{ $quizId }};
                                } else if (now > endTime{{ $quizId }}) {
                                    statusElem{{ $quizId }}.innerHTML = finishedText{{ $quizId }};
                                } else {
                                    statusElem{{ $quizId }}.innerHTML = runningLink{{ $quizId }};
                                }
                            }

                            updateStatus{{ $quizId }}();
                            setInterval(updateStatus{{ $quizId }}, 1000);
                        </script>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>