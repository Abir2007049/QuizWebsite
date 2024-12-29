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
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">No questions found</td>
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
        <input type="number" id="correct_option" name="correct_option" class="form-control" min="1" max="4" required>
        <small class="form-text text-muted">Enter a number from 1 to 4.</small>
    </div>

    <!-- Duration -->
    <div class="form-group">
        <label for="duration" class="form-label">Duration (minutes)</label>
        <input type="number" id="duration" name="duration" class="form-control" min="1" placeholder="Duration in minutes (optional)">
        <small class="form-text text-muted">Enter the time limit for the question in minutes. (Optional)</small>
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
        
        <!-- Duration -->
        <div class="form-group">
            <label for="duration" class="form-label">Duration (in minutes)</label>
            <input type="number" id="duration" name="duration" class="form-control" min="1" required>
        </div>
        
        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary mt-2">Schedule Quiz</button>
        
        <!-- Start Now Button -->
        <button type="button" class="btn btn-success mt-2" id="startNowButton">Start Now</button>
    </form>
    
    
    
</div>

<script>
    // Handle Start Now button click
    document.getElementById('startNowButton').addEventListener('click', function () {
        let duration = $quiz->duration;

        if(duration==-1) please enter a valid duration

        else
        {
            ok
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
           // minDate: "today"  // Prevents selecting past dates
        });
    });
</script>
@endsection
