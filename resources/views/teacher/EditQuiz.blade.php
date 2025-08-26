@extends('layout')

@section('title', 'Quiz Details')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Flatpickr CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

<div class="max-w-7xl mx-auto py-10 px-4 text-white">
    {{-- Quiz Info --}}
    <div class="bg-[#1E293B] shadow-xl rounded-xl p-6 mb-10">
        <div class="flex justify-between items-center">
            <h3 class="text-3xl font-semibold text-white">{{ $quiz->title }}</h3>
            <span class="text-sm text-gray-400">Created: {{ $quiz->created_at->format('d M, Y H:i') }}</span>
        </div>
    </div>

    {{-- Questions List --}}
    <div class="bg-[#0F172A] shadow-xl rounded-xl mb-10">
        <div class="border-b border-blue-700 px-6 py-4">
            <h5 class="text-xl font-semibold text-white">📋 Questions</h5>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-blue-800 text-white">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Question</th>
                        <th class="px-6 py-3">Options</th>
                        <th class="px-6 py-3 text-center" colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody id="questions-body" class="bg-[#1E293B] text-gray-300 divide-y divide-blue-800">
                    @forelse ($quiz->questions as $question)
                        <tr class="hover:bg-[#334155] transition">
                            <td class="px-6 py-4">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                @if ($question->text) 
                                    {{ $question->text }}
                                @elseif ($question->image)
                                    <img src="{{ asset('storage/' . $question->image) }}" class="w-48 h-auto rounded shadow">
                                @else
                                    <em class="text-gray-400">No content</em>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach($question->options as $index => $opt)
                                        @if($opt)
                                            <li class="@if(($index+1)==$question->right_option) text-blue-400 font-semibold @endif">
                                                {{ $opt }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="text-red-400 hover:text-red-300 transition delete-btn" data-id="{{ $question->id }}">Delete</button>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('questions.edit', ['id' => $question->id]) }}" method="GET">
                                    @csrf
                                    <button type="submit" class="text-blue-400 hover:underline">Update</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr id="no-questions">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-400">No questions found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add Question --}}
    <div class="bg-[#0F172A] shadow-xl rounded-xl mb-10">
        <div class="border-b border-blue-700 px-6 py-4">
            <h5 class="text-xl font-semibold text-white">➕ Add Question</h5>
        </div>
        <div class="p-6">
            <form id="ajx" action="{{ secure_url('questions/add') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-300 font-medium mb-1">Question Type</label>
                        <select id="question_type" name="question_type" class="w-full rounded-md bg-blue-900 text-white border border-blue-600" required>
                            <option value="text">Text</option>
                            <option value="image">Image</option>
                        </select>
                    </div>

                    <div id="text-input">
                        <label class="block text-gray-300 font-medium mb-1">Question Text</label>
                        <input type="text" name="question_text" class="w-full rounded-md bg-blue-900 text-white border border-blue-600" placeholder="Enter question text">
                    </div>

                    <div id="image-input" class="hidden">
                        <label class="block text-gray-300 font-medium mb-1">Question Image</label>
                        <input type="file" name="question_image" class="w-full rounded-md bg-blue-900 text-white border border-blue-600" accept="image/*">
                    </div>

                    {{-- Options container --}}
                    <div class="md:col-span-2">
                        <label class="block text-gray-300 font-medium mb-1">Options (minimum 2, maximum 4)</label>
                        <div id="options-container">
                            <input type="text" name="options[]" class="w-full rounded-md bg-blue-900 text-white border border-blue-600 mb-2 placeholder-gray-400" placeholder="Option 1" required>
                            <input type="text" name="options[]" class="w-full rounded-md bg-blue-900 text-white border border-blue-600 mb-2 placeholder-gray-400" placeholder="Option 2" required>
                        </div>
                        <button type="button" id="add-option" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-1 rounded-md mt-2">Add Option</button>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-medium mb-1">Correct Option</label>
                        <select id="correct_option" name="correct_option" class="w-full rounded-md bg-blue-900 text-white border border-blue-600" required>
                            <option disabled selected>Select</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-300 font-medium mb-1">Duration (sec)</label>
                        <input type="number" name="duration" class="w-full rounded-md bg-blue-900 text-white border border-blue-600" min="1" placeholder="e.g., 30">
                    </div>

                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-md font-semibold shadow transition-all">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Schedule Quiz --}}
    <div class="bg-[#0F172A] shadow-xl rounded-xl">
        <div class="border-b border-blue-700 px-6 py-4">
            <h5 class="text-xl font-semibold text-white">⏱ Schedule Quiz</h5>
        </div>
        <div class="p-6">
            <form action="{{ route('quiz.schedule', $quiz->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                @csrf
                <div>
                    <label for="start_datetime" class="block text-gray-300 font-medium mb-1">Start Date & Time</label>
                    <input type="text" id="start_datetime" name="start_datetime" class="w-full rounded-md bg-blue-900 text-white border border-blue-600" required>
                </div>
                <div class="md:col-span-2 flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-2 rounded-md shadow font-semibold">Schedule</button>
                </div>
            </form>

            <form action="{{ route('quiz.startnow', $quiz->id) }}" method="POST" class="text-right">
                @csrf
                <button type="submit" class="bg-green-600 hover:bg-green-500 text-white px-6 py-2 rounded-md shadow font-semibold">Start Now</button>
            </form>
        </div>
    </div>
</div>

{{-- Flatpickr JS --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // Toggle question input type
    const questionType = document.getElementById('question_type');
    const textInput = document.getElementById('text-input');
    const imageInput = document.getElementById('image-input');

    function toggleQuestionInput() {
        const type = questionType.value;
        textInput.classList.toggle('hidden', type === 'image');
        imageInput.classList.toggle('hidden', type === 'text');
    }

    questionType.addEventListener('change', toggleQuestionInput);
    toggleQuestionInput();

    // Flatpickr init
    flatpickr("#start_datetime", { enableTime: true, dateFormat: "Y-m-d H:i" });

    // Options logic
    const optionsContainer = document.getElementById('options-container');
    const correctSelect = document.getElementById('correct_option');

    function updateCorrectOptions() {
        correctSelect.innerHTML = '<option disabled selected>Select</option>';
        const optionInputs = optionsContainer.querySelectorAll('input[name="options[]"]');
        optionInputs.forEach((input, index) => {
            if (input.value.trim() !== "") {
                correctSelect.innerHTML += `<option value="${index+1}">Option ${index+1}</option>`;
            }
        });
    }

    optionsContainer.addEventListener('input', updateCorrectOptions);
    updateCorrectOptions();

    // Add option dynamically
    const addOptionBtn = document.getElementById('add-option');
    addOptionBtn.addEventListener('click', () => {
        const currentOptions = optionsContainer.querySelectorAll('input[name="options[]"]').length;
        if (currentOptions < 4) {
            const newOption = document.createElement('input');
            newOption.type = 'text';
            newOption.name = 'options[]';
            newOption.placeholder = `Option ${currentOptions + 1}`;
            newOption.className = 'w-full rounded-md bg-blue-900 text-white border border-blue-600 mb-2 placeholder-gray-400';
            optionsContainer.appendChild(newOption);
            updateCorrectOptions();
        } else {
            alert('Maximum 4 options allowed.');
        }
    });

    // AJAX Delete Question
    const questionsBody = document.getElementById('questions-body');

    questionsBody.addEventListener('click', function(e) {
        const btn = e.target.closest('.delete-btn');
        if (!btn) return;

        if (!confirm("Are you sure you want to delete this question?")) return;

        const id = btn.dataset.id;

        fetch(`/questions/${id}`, {
            method: 'DELETE',
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Content-Type": "application/json"
            },
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                btn.closest('tr').remove();
                if (questionsBody.querySelectorAll('tr').length === 0) {
                    questionsBody.innerHTML = '<tr id="no-questions"><td colspan="5" class="px-6 py-4 text-center text-gray-400">No questions found</td></tr>';
                }
            } else {
                alert(data.message || 'Failed to delete the question.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while deleting the question.');
        });
    });

});
</script>
@endsection
