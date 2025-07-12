@extends('layout')

@section('title', 'Quiz List')

@section('content')
<!-- CSRF token for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

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
                    <tr id="quiz-row-{{ $quiz->id }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $quiz->title }}</td>
                        <td>{{ $quiz->created_at->format('d M, Y H:i') }}</td>
                        <td class="text-center">
                            <a href="{{ route('quiz.details', $quiz->id) }}" class="btn btn-success btn-sm me-2">View</a>
                            <a href="{{ route('quiz.leaderboard', $quiz->id) }}" class="btn btn-secondary btn-sm me-2">Leaderboard</a>
                            
                            <!-- Delete Button -->
                            <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $quiz->id }}">Delete</button>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const quizId = this.getAttribute('data-id');

            if (!confirm('Are you sure you want to delete this quiz?')) return;

            fetch(`/quiz/${quizId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ _method: 'DELETE' })
            })
            .then(response => {
                if (response.ok) {
                    document.getElementById(`quiz-row-${quizId}`).remove();
                } else if (response.status === 403) {
                    alert('You are not authorized to delete this quiz.');
                } else {
                    alert('Failed to delete quiz.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong.');
            });
        });
    });
});
</script>
@endpush
