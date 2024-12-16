<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quizzes for {{ $teacher->name }}</title>
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
                        $startDatetime = \Carbon\Carbon::parse($quiz->start_datetime)->setTimezone('Asia/Dhaka'); // Use actual datetime

    // Get the current time in Dhaka
                         $dhakaTime = \Carbon\Carbon::now('Asia/Dhaka');
                    
                        // Convert Dhaka time to UTC
                      //  $utcTime = $dhakaTime->setTimezone('UTC');
                    
                        // Debugging the values using dd() to stop and inspect the data
                      //  dd('Start Datetime:', $startDatetime, 'Dhaka Time:', $dhakaTime);
                    @endphp
                    
                    
                
                        {{-- Debugging the start date and current date --}}
                    
                        {{-- Debugging the comparison result --}}
                    
                
                        @if ($dhakaTime->lt($startDatetime))
                      
                            <p>The quiz will be available on {{ $startDatetime->format('F j, Y, g:i A') }}.</p>
                        @else
                            <a href="{{ route('quiz.take', ['id' => $quiz->id, 'student_id' => $student_id]) }}">Take Quiz</a>
                        @endif
                    </td>
                </tr>
                
                
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>

