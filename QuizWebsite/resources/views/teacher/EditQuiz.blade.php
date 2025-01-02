@extends('layout')

@section('title', 'Quiz Details')

@section('content')
<div class="container mt-4">
    <!-- Quiz Title and Created At -->
    <h1>{{ $quiz->title }}</h1>
    <p><strong>Created At:</strong> {{ $quiz->created_at->format('d M, Y H:i') }}</p>
    
    <!-- Questions Table -->
    <h3>Questions</h3>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
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
                            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                @foreach([$question->option1, $question->option2, $question->option3, $question->option4] as $index => $option)
                                    <span 
                                        @if ($index + 1 == $question->right_option) 
                                            style="font-weight: bold; color: green;" 
                                        @endif>
                                        {{ $option }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="text-center">
                            <!-- Delete Button -->
                            <form action="{{ route('questions.delete', $question->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm px-4 py-2 shadow-sm hover:scale-105 transition-transform duration-300 ease-in-out">Delete</button>
                            </form>
                        
                            <!-- Update Button -->
                            <form action="{{ route('questions.edit', ['id' => $question->id]) }}" method="GET" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm px-4 py-2 shadow-sm hover:scale-105 transition-transform duration-300 ease-in-out">Update</button>
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

    <!-- Form Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form to Add a New Question -->
    <h3>Add New Question</h3>
    <form action="{{ route('questions.add') }}" method="POST">
        @csrf
        <!-- Pass the Quiz ID -->
        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

        <!-- Question Text -->
        <div class="form-group">
            <label for="question_text" class="form-label">Question Text</label>
            <input type="text" id="question_text" name="question_text" class="form-control" required>
        </div>

        <!-- Options -->
        <div class="form-group">
            <label class="form-label">Options</label>
            <div class="form-group">
                <input type="text" name="options[1]" class="form-control mb-2" placeholder="Option 1" required>
                <input type="text" name="options[2]" class="form-control mb-2" placeholder="Option 2" required>
                <input type="text" name="options[3]" class="form-control mb-2" placeholder="Option 3" required>
                <input type="text" name="options[4]" class="form-control mb-2" placeholder="Option 4" required>
            </div>
        </div>

        <!-- Correct Option -->
        <div class="form-group">
            <label for="correct_option" class="form-label">Correct Option</label>
            <select id="correct_option" name="correct_option" class="form-control" required>
                <option value="" disabled selected>Select an option</option>
                <option value="1">Option 1</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
                <option value="4">Option 4</option>
            </select>
            <small class="form-text text-muted">Select a number between 1 to 4 as the correct option.</small>
        </div>
        

        <!-- Duration -->
        <div class="form-group">
            <label for="duration" class="form-label">Duration (seconds)</label>
            <input type="number" id="duration" name="duration" class="form-control" min="1" placeholder="Duration ">
            <small class="form-text text-muted">Enter the time limit for the question </small>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary mt-2">Add Question</button>
    </form>

    <!-- Schedule Quiz Form -->
    <h3>Schedule Quiz</h3>
    <form action="{{ route('quiz.schedule', $quiz->id) }}" method="POST">
        @csrf
        
        <!-- Starting Date and Time (Flatpickr Calendar) -->
        <div class="form-group">
            <label for="start_datetime" class="form-label">Starting Date and Time</label>
            <input type="text" id="start_datetime" name="start_datetime" class="form-control" required>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary mt-2">Schedule Quiz</button>
    </form>

    <form action="{{ route('quiz.startnow', $quiz->id) }}" method="POST">
        @csrf
        <!-- Start Now Button -->
        <button type="submit" class="btn btn-success mt-2" id="startNowButton">Start Now</button>
    </form>
</div>

<script>
    // Handle Start Now button click
    document.getElementById('startNowButton').addEventListener('click', function () {
        let duration = $quiz->duration;

        if(duration == -1) {
            alert("Please enter a valid duration");
        } else {
            alert("Starting the quiz now!");
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    // Ensure that Flatpickr initializes after the DOM is fully loaded
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#start_datetime", {
            enableTime: true,  // Enables time selection
            dateFormat: "Y-m-d H:i",  // Formats the date and time
        });
    });
</script>

@endsection
