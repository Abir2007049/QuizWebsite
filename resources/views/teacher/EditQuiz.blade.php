@extends('layout')

@section('title', 'Quiz Details')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container py-4">
    {{-- Quiz Info --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h3 class="mb-0">{{ $quiz->title }}</h3>
            <small class="text-muted">Created: {{ $quiz->created_at->format('d M, Y H:i') }}</small>
        </div>
    </div>

    {{-- Questions List --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Questions</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Question</th>
                            <th>Options</th>
                            <th colspan="2">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="questions-body">
                        @forelse ($quiz->questions as $question)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($question->text) 
                                        {{ $question->text }}
                                    @elseif ($question->image)
                                        <img src="{{ asset('storage/' . $question->image) }}" class="img-fluid" style="max-width: 200px;">
                                    @else
                                        <em>No question content</em>
                                    @endif
                                </td>
                                <td>
                                    <ul class="mb-0 ps-3">
                                        @foreach([$question->option1,$question->option2,$question->option3,$question->option4] as $i=>$opt)
                                            <li style="@if($i+1==$question->right_option) font-weight: bold; color: green; @endif">
                                                {{ $opt }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <button class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $question->id }}">Delete</button>
                                </td>
                                <td>
                                    <form action="{{ route('questions.edit', ['id' => $question->id]) }}" method="GET">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm">Update</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-questions"><td colspan="5" class="text-center">No questions found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add Question --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Add New Question</h5>
        </div>
        <div class="card-body">
            <form id="ajx" action="{{ route('questions.add') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label>Question Type</label>
                        <select id="question_type" name="question_type" class="form-select" onchange="toggleQuestionInput()" required>
                            <option value="text">Text</option>
                            <option value="image">Image</option>
                        </select>
                    </div>

                    <div class="col-md-6" id="text-input">
                        <label>Question Text</label>
                        <input type="text" name="question_text" class="form-control" placeholder="Enter question text">
                    </div>

                    <div class="col-md-6 d-none" id="image-input">
                        <label>Question Image</label>
                        <input type="file" name="question_image" class="form-control" accept="image/*">
                    </div>

                    <div class="col-md-6">
                        <label>Options</label>
                        @for ($i = 1; $i <= 4; $i++)
                            <input type="text" name="options[{{ $i }}]" class="form-control mb-2" placeholder="Option {{ $i }}" required>
                        @endfor
                    </div>

                    <div class="col-md-4">
                        <label>Correct Option</label>
                        <select name="correct_option" class="form-select" required>
                            <option disabled selected>Select correct option</option>
                            @for ($i = 1; $i <= 4; $i++)
                                <option value="{{ $i }}">Option {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Duration (in sec)</label>
                        <input type="number" name="duration" class="form-control" min="1" placeholder="Duration">
                    </div>

                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Add Question</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Quiz Scheduling --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Schedule Quiz</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('quiz.schedule', $quiz->id) }}" method="POST" class="row g-3 align-items-end mb-3">
                @csrf
                <div class="col-md-6">
                    <label for="start_datetime" class="form-label">Start Date & Time</label>
                    <input type="text" id="start_datetime" name="start_datetime" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Schedule</button>
                </div>
            </form>

            <form action="{{ route('quiz.startnow', $quiz->id) }}" method="POST" class="text-end">
                @csrf
                <button type="submit" class="btn btn-success">Start Now</button>
            </form>
        </div>
    </div>
</div>

{{-- JS --}}
<script>
    function toggleQuestionInput() {
        const type = document.getElementById('question_type').value;
        document.getElementById('text-input').classList.toggle('d-none', type !== 'text');
        document.getElementById('image-input').classList.toggle('d-none', type !== 'image');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Submit question AJAX
        document.getElementById('ajx').onsubmit = async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const res = await fetch(this.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf },
                body: formData
            });
            const q = await res.json();
            if (!res.ok) return alert('Failed to add question.');

            const tbody = document.getElementById('questions-body');
            document.getElementById('no-questions')?.remove();
            const idx = tbody.querySelectorAll('tr').length + 1;
            const content = q.text ? q.text : q.image ? `<img src="/storage/${q.image}" style="max-width:200px;">` : '<em>No question content</em>';
            const options = [q.option1, q.option2, q.option3, q.option4]
                .map((opt, i) => `<li style="${i+1==q.right_option?'font-weight:bold;color:green;':''}">${opt}</li>`).join('');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${idx}</td>
                <td>${content}</td>
                <td><ul class="mb-0">${options}</ul></td>
                <td><button class="btn btn-outline-danger btn-sm delete-btn" data-id="${q.id}">Delete</button></td>
                <td>
                    <form action="/questions/${q.id}/edit" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-outline-success btn-sm">Update</button>
                    </form>
                </td>
            `;
            tbody.appendChild(row);
            this.reset();
            toggleQuestionInput();
        };

        // Delete AJAX
        document.addEventListener('click', async (e) => {
            if (!e.target.classList.contains('delete-btn')) return;
            if (!confirm('Are you sure?')) return;
            const id = e.target.dataset.id;
            const res = await fetch(`/questions/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                }
            });

            if (res.ok) {
                e.target.closest('tr').remove();
            } else {
                alert('Delete failed.');
            }
        });

        flatpickr("#start_datetime", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    });
</script>
@endsection
