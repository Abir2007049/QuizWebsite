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
                            <a href="{{ route('quiz.take', $quiz->id) }}">Take Quiz</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- kjkjk -->
    @endif
</body>
</html>
