@extends('layout')

@section('custom_header')
<header class="bg-gray-900 shadow z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-indigo-400 tracking-tight">📘 EduHub</h1>
        <nav class="space-x-6 text-gray-300 font-medium">
            <a href="/" class="hover:text-indigo-400 transition">Home</a>
            <a href="{{ route('quiz.listStud') }}" class="hover:text-indigo-400 transition">Quizzes</a>
        </nav>
    </div>
</header>
@endsection

@section('title', 'Quiz Details')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 px-4 py-12 text-white relative overflow-hidden flex justify-center">

    {{-- Background glowing blobs --}}
    <div class="absolute -top-32 -left-32 w-[500px] h-[500px] bg-indigo-700 opacity-20 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-[400px] h-[400px] bg-purple-600 opacity-20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="relative z-10 max-w-4xl w-full space-y-12">

        {{-- Quiz Info --}}
        <div class="bg-gray-900 bg-opacity-80 border border-indigo-600 rounded-2xl p-8 shadow-2xl text-center">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white leading-tight mb-6">
                {{ $quiz->title }}
            </h2>
            <p class="text-sm text-indigo-300 mt-2">🕒 Created on: {{ $quiz->created_at->format('d M, Y H:i') }}</p>
        </div>

        {{-- Questions --}}
        <div class="bg-gray-900 bg-opacity-80 border border-indigo-600 rounded-2xl overflow-hidden shadow-2xl">
            <div class="bg-gradient-to-r from-purple-800 to-indigo-900 px-6 py-4 rounded-t-2xl">
                <h3 class="text-xl font-bold text-white">📋 Questions</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-black/30 text-indigo-300 uppercase font-semibold tracking-wide">
                        <tr>
                            <th class="p-4">#</th>
                            <th class="p-4">Question</th>
                            <th class="p-4">Options</th>
                            <th class="p-4 text-center" colspan="2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-indigo-600/40" id="questions-body">
                        @forelse ($quiz->questions as $question)
                            <tr>
                                <td class="p-4 font-semibold text-purple-400">{{ $loop->iteration }}</td>
                                <td class="p-4">
                                    @if ($question->text)
                                        {{ $question->text }}
                                    @elseif ($question->image)
                                        <img src="{{ asset('storage/' . $question->image) }}" class="w-40 rounded-lg shadow-md" alt="Question Image">
                                    @else
                                        <em class="text-indigo-500">No content</em>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <ul class="list-disc ml-5 space-y-1 text-indigo-300">
                                        @foreach([$question->option1,$question->option2,$question->option3,$question->option4] as $i=>$opt)
                                            <li class="@if($i+1 == $question->right_option) font-bold text-green-400 @endif">{{ $opt }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="p-4 text-center">
                                    <button 
                                        class="bg-red-600 hover:bg-red-700 text-white text-xs px-4 py-2 rounded-xl shadow-lg transition duration-300 delete-btn" 
                                        data-id="{{ $question->id }}">
                                        Delete
                                    </button>
                                </td>
                                <td class="p-4 text-center">
                                    <form action="{{ route('questions.edit', $question->id) }}" method="GET">
                                        @csrf
                                        <button 
                                            type="submit" 
                                            class="bg-green-600 hover:bg-green-700 text-white text-xs px-4 py-2 rounded-xl shadow-lg transition duration-300">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-questions">
                                <td colspan="5" class="text-center py-4 text-indigo-400 italic">No questions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Add Question --}}
        <div class="bg-gray-900 bg-opacity-80 border border-indigo-600 rounded-2xl shadow-2xl p-8">
            <h3 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-300 mb-6">➕ Add New Question</h3>
            <form id="ajx" action="{{ route('questions.add') }}" method="POST" enctype="multipart/form-data" class="grid gap-6 sm:grid-cols-2">
                @csrf
                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                <div>
                    <label class="block text-sm font-semibold text-indigo-300">Question Type</label>
                    <select id="question_type" name="question_type" onchange="toggleQuestionInput()" class="mt-1 px-4 py-2 bg-black/40 text-white border border-indigo-600 rounded-lg w-full">
                        <option value="text">Text</option>
                        <option value="image">Image</option>
                    </select>
                </div>

                <div id="text-input">
                    <label class="block text-sm font-semibold text-indigo-300">Question Text</label>
                    <input type="text" name="question_text" class="w-full mt-1 px-4 py-2 bg-black/40 text-white border border-indigo-600 rounded-lg" placeholder="Enter question text">
                </div>

                <div id="image-input" class="hidden">
                    <label class="block text-sm font-semibold text-indigo-300">Question Image</label>
                    <input type="file" name="question_image" class="w-full mt-1 px-4 py-2 bg-black/40 text-white border border-indigo-600 rounded-lg" accept="image/*">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-indigo-300">Options</label>
                    <div class="grid grid-cols-2 gap-2 mt-1">
                        @for ($i = 1; $i <= 4; $i++)
                            <input type="text" name="options[{{ $i }}]" class="px-4 py-2 bg-black/40 text-white border border-indigo-600 rounded-lg" placeholder="Option {{ $i }}" required>
                        @endfor
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-indigo-300">Correct Option</label>
                    <select name="correct_option" class="w-full mt-1 px-4 py-2 bg-black/40 text-white border border-indigo-600 rounded-lg">
                        <option disabled selected>Select correct option</option>
                        @for ($i = 1; $i <= 4; $i++)
                            <option value="{{ $i }}">Option {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-indigo-300">Duration (in seconds)</label>
                    <input type="number" name="duration" class="w-full mt-1 px-4 py-2 bg-black/40 text-white border border-indigo-600 rounded-lg" min="1">
                </div>

                <div class="sm:col-span-2">
                    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 rounded-xl shadow-lg transition">
                        Add Question
                    </button>
                </div>
            </form>
        </div>

        {{-- Quiz Scheduling --}}
        <div class="bg-gray-900 bg-opacity-80 border border-indigo-600 rounded-2xl shadow-2xl p-8">
            <h3 class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-indigo-300 mb-4">🕒 Schedule Quiz</h3>
            <form action="{{ route('quiz.schedule', $quiz->id) }}" method="POST" class="grid gap-4 sm:grid-cols-3">
                @csrf
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-indigo-300">Start Date & Time</label>
                    <input type="text" id="start_datetime" name="start_datetime" class="w-full mt-1 px-4 py-2 bg-black/40 text-white border border-indigo-600 rounded-lg" required>
                </div>
                <div>
                    <button type="submit" class="w-full bg-indigo-700 hover:bg-indigo-800 text-white py-3 rounded-xl shadow-md transition">
                        Schedule
                    </button>
                </div>
            </form>
            <form action="{{ route('quiz.startnow', $quiz->id) }}" method="POST" class="mt-4 text-right">
                @csrf
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-6 py-2 rounded-xl shadow-md transition">
                    Start Now
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    function toggleQuestionInput() {
        const type = document.getElementById('question_type').value;
        document.getElementById('text-input').classList.toggle('hidden', type !== 'text');
        document.getElementById('image-input').classList.toggle('hidden', type !== 'image');
    }

    document.addEventListener('DOMContentLoaded', () => {
        flatpickr("#start_datetime", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today"
        });
        toggleQuestionInput(); // initialize input visibility
    });

    // Delete question logic
    document.addEventListener('DOMContentLoaded', () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', () => {
                const questionId = button.getAttribute('data-id');
                if (!confirm('Are you sure you want to delete this question?')) return;

                fetch(`/questions/${questionId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })
                .then(response => {
                    if (response.ok) {
                        button.closest('tr').remove();
                    } else {
                        alert('Failed to delete question.');
                    }
                })
                .catch(() => alert('Something went wrong.'));
            });
        });
    });
</script>
@endsection
