@extends('layout')

@section('title', 'Quiz Details')

@section('content')
<div class="container mt-4 p-4 bg-light shadow rounded" style="color: #333; background-color: #f8fff8; border: 1px solid #d4edda;">
    <!-- Quiz Title and Created At -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0" style="color: #155724;">{{ $quiz->title }}</h1>
        <p class="mb-0"><strong>Created At:</strong> {{ $quiz->created_at->format('d M, Y H:i') }}</p>
    </div>

    <!-- Questions Table -->
    <h3 style="color: #155724;">Questions</h3>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-striped">
            <thead style="background-color: #d4edda;">
                <tr>
                    <th>#</th>
                    <th>Question</th>
                    <th>Options</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quiz->questions as $question)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $question->text }}</td>
                        <td>
                            <ul class="list-unstyled mb-0">
                                @foreach([$question->option1, $question->option2, $question->option3, $question->option4] as $index => $option)
                                    <li style="@if ($index + 1 == $question->right_option) font-weight: bold; color: green; @endif">{{ $option }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('questions.delete', $question->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                            </form>
                            <form action="{{ route('questions.edit', ['id' => $question->id]) }}" method="GET" class="d-inline-block">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm">Update</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No questions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Add New Question Form -->
    <h3 style="color: #155724;">Add New Question</h3>
    <form action="{{ route('questions.add') }}" method="POST" class="mb-4">
        @csrf
        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

        <div class="row g-3">
            <div class="col-md-6">
                <label for="question_text" class="form-label">Question</label>
                <input type="text" id="question_text" name="question_text" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Options</label>
                @for ($i = 1; $i <= 4; $i++)
                    <input type="text" name="options[{{ $i }}]" class="form-control mb-2" placeholder="Option {{ $i }}" required>
                @endfor
            </div>
            <div class="col-md-4">
                <label for="correct_option" class="form-label">Correct Option</label>
                <select id="correct_option" name="correct_option" class="form-control" required>
                    <option value="" disabled selected>Select</option>
                    @for ($i = 1; $i <= 4; $i++)
                        <option value="{{ $i }}">Option {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label for="duration" class="form-label">Duration (seconds)</label>
                <input type="number" id="duration" name="duration" class="form-control" min="1" placeholder="Duration">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">Add Question</button>
            </div>
        </div>
    </form>

    <!-- Schedule Quiz Form -->
    <h3 style="color: #155724;">Schedule Quiz</h3>
    <form action="{{ route('quiz.schedule', $quiz->id) }}" method="POST" class="d-flex align-items-end gap-3 mb-3">
        @csrf
        <div>
            <label for="start_datetime" class="form-label">Start Date & Time</label>
            <input type="text" id="start_datetime" name="start_datetime" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Schedule</button>
    </form>

    <form action="{{ route('quiz.startnow', $quiz->id) }}" method="POST" class="text-end">
        @csrf
        <button type="submit" class="btn btn-success">Start Now</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#start_datetime", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    });
</script>
@endsection
