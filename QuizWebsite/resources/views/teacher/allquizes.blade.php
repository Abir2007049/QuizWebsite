@extends('layout')

@section('title', 'Quiz List')

@section('content')
<div class="container mt-4">
    <h1 class="text-center">Quiz List</h1>
    <div class="table-responsive">
        <table class="table table-striped table-hover border">
            <thead class="table-success">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Created At</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quizzes as $quiz)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $quiz->title }}</td>
                        <td>{{ $quiz->created_at->format('d M, Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('quiz.details', $quiz->id) }}" class="btn btn-success btn-sm me-2">View</a>
                            <a href="{{ route('quiz.leaderboard', $quiz->id) }}" class="btn btn-secondary btn-sm me-2">Leaderboard</a>
                            <!-- Delete Button -->
                            <form action="{{ route('quiz.destroy', $quiz->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this quiz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No quizzes found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
