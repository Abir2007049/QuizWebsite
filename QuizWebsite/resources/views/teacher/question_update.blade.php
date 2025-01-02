@extends('layout')

@section('content')
<div class="container mt-5">
    <h2 class="text-center text-success mb-4">Edit Question</h2>
    <form action="{{ route('questions.update', $question->id) }}" method="POST" class="p-4 border border-success rounded shadow-sm">
        @csrf
        @method('PUT')

        <!-- Question Text -->
        <div class="mb-3">
            <label for="text" class="form-label text-success">Question Text</label>
            <textarea id="text" name="text" class="form-control border-success" rows="4" required>{{ $question->text }}</textarea>
        </div>

        <!-- Option 1 -->
        <div class="mb-3">
            <label for="option1" class="form-label text-success">Option 1</label>
            <input type="text" id="option1" name="option1" class="form-control border-success" value="{{ $question->option1 }}" required>
        </div>

        <!-- Option 2 -->
        <div class="mb-3">
            <label for="option2" class="form-label text-success">Option 2</label>
            <input type="text" id="option2" name="option2" class="form-control border-success" value="{{ $question->option2 }}" required>
        </div>

        <!-- Option 3 -->
        <div class="mb-3">
            <label for="option3" class="form-label text-success">Option 3</label>
            <input type="text" id="option3" name="option3" class="form-control border-success" value="{{ $question->option3 }}" required>
        </div>

        <!-- Option 4 -->
        <div class="mb-3">
            <label for="option4" class="form-label text-success">Option 4</label>
            <input type="text" id="option4" name="option4" class="form-control border-success" value="{{ $question->option4 }}" required>
        </div>

        <!-- Correct Option -->
        <div class="mb-3">
            <label for="right_option" class="form-label text-success">Correct Option</label>
            <select id="right_option" name="right_option" class="form-control border-success" required>
                <option value="1" {{ $question->right_option == 1 ? 'selected' : '' }}>Option 1</option>
                <option value="2" {{ $question->right_option == 2 ? 'selected' : '' }}>Option 2</option>
                <option value="3" {{ $question->right_option == 3 ? 'selected' : '' }}>Option 3</option>
                <option value="4" {{ $question->right_option == 4 ? 'selected' : '' }}>Option 4</option>
            </select>
        </div>

        <!-- Duration -->
        <div class="mb-3">
            <label for="duration" class="form-label text-success">Duration (in seconds)</label>
            <input type="number" id="duration" name="duration" class="form-control border-success" value="{{ $question->duration }}" required>
        </div>

        <!-- Save Button -->
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success px-4 py-2">Update Question</button>
          
        </div>
    </form>
</div>
@endsection
