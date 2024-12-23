@extends('layout')

@section('title', 'Quiz List')

@section('content')
<div class="container mt-4">
    <h1>Quiz List</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($quizzes as $quiz)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $quiz->title }}</td>
                    <td>{{ $quiz->created_at->format('d M, Y H:i') }}</td>
                    <td>
                        <a href="{{ route('quiz.details', $quiz->id) }}" class="btn btn-primary btn-sm">View</a>
                        <a href="{{ route('quiz.leaderboard', $quiz->id) }}" class="btn btn-primary btn-sm">leaderboard</a>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No quizzes found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
