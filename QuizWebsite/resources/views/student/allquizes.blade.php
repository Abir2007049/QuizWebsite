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
            color: #28a745; /* Green color */
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
            color: #ffc107; /* Yellow for upcoming */
        }
        .status.finished {
            color: #dc3545; /* Red for finished */
        }
        .status.running {
            color: #28a745; /* Green for running */
        }
    </style>
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($quizzes as $quiz)
                <tr>
                    <td>{{ $quiz->title }}</td>
                    <td>
                        @php
                        // Convert $quiz->start_datetime to a Carbon instance
                        $startDatetime = \Carbon\Carbon::parse($quiz->start_datetime)->setTimezone('Asia/Dhaka');
                        $dhakaTime = \Carbon\Carbon::now('Asia/Dhaka');

                        // Calculate the end time by adding the duration to the start time
                        $endDatetime = $startDatetime->copy()->addMinutes($quiz->duration);
                        @endphp

                        @if ($dhakaTime->lt($startDatetime))
                            <p class="status upcoming">The quiz will be available on {{ $startDatetime->format('F j, Y, g:i A') }}.</p>
                        @elseif ($dhakaTime->gt($endDatetime))
                             <p class="status finished">Finished</p>
                        @else
                            <a href="{{ route('quiz.take', ['id' => $quiz->id, 'student_id' => $student_id]) }}" class="status running">Running</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
