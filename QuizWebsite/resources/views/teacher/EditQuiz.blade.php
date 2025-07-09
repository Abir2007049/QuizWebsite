@extends('layout')

@section('title', 'Quiz Details')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container mt-4 p-4 bg-light shadow rounded">
    <div class="d-flex justify-content-between mb-4">
        <h1>{{ $quiz->title }}</h1>
        <p><strong>Created At:</strong> {{ $quiz->created_at->format('d M, Y H:i') }}</p>
    </div>

    <h3>Questions</h3>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-striped">
            <thead class="bg-light">
                <tr>
                    <th>#</th><th>Question</th><th>Options</th><th>Actions</th>
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
                                <img src="{{ asset('storage/' . $question->image) }}" style="max-width:200px;">
                            @else
                                <em>No question content</em>
                            @endif
                        </td>
                        <td>
                            <ul class="mb-0">
                                @foreach([$question->option1,$question->option2,$question->option3,$question->option4] as $i=>$opt)
                                  <li style="@if($i+1==$question->right_option)font-weight:bold;color:green;@endif">{{ $opt }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <button class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $question->id }}">Delete</button>
                        </td>
                          <td>
                        <form action="{{ route('questions.edit', ['id' => $question->id]) }}" method="GET" class="d-inline-block">
                                @csrf
                                <button type="submit" class="btn btn-outline-success btn-sm">Update</button>
                            </form>

</td>
                    </tr>
                @empty
                    <tr id="no-questions"><td colspan="4" class="text-center">No questions found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <h3>Add New Question</h3>
    <form id="ajx" action="{{ route('questions.add') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

        <div class="row g-3">
            <div class="col-md-6">
                <select id="question_type" name="question_type" class="form-control" onchange="toggleQuestionInput()" required>
                    <option value="text">Text</option>
                    <option value="image">Image</option>
                </select>
            </div>

            <div class="col-md-6" id="text-input">
                <input type="text" name="question_text" class="form-control" placeholder="Question text">
            </div>

            <div class="col-md-6 d-none" id="image-input">
                <input type="file" name="question_image" class="form-control" accept="image/*">
            </div>

            <div class="col-md-6">
                @for ($i = 1; $i <= 4; $i++)
                    <input type="text" name="options[{{ $i }}]" class="form-control mb-2" placeholder="Option {{ $i }}" required>
                @endfor
            </div>

            <div class="col-md-4">
                <select name="correct_option" class="form-control" required>
                    <option disabled selected>Correct option</option>
                    @for ($i = 1; $i <= 4; $i++)
                        <option value="{{ $i }}">Option {{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="col-md-4">
                <input type="number" name="duration" class="form-control" min="1" placeholder="Duration (sec)">
            </div>

            <div class="col-md-4">
                <button type="submit" class="btn btn-success w-100">Add Question</button>
            </div>
        </div>
    </form>
</div>

<script>
function toggleQuestionInput() {
    const type = document.getElementById('question_type').value;
    document.getElementById('text-input').classList.toggle('d-none', type !== 'text');
    document.getElementById('image-input').classList.toggle('d-none', type !== 'image');
}

document.addEventListener('DOMContentLoaded', () => {
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  
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

        const content = q.text ? q.text
            : q.image ? `<img src="/storage/${q.image}" style="max-width:200px;">`
            : '<em>No question content</em>';

        const options = [q.option1, q.option2, q.option3, q.option4]
            .map((opt, i) => `<li style="${i+1==q.right_option?'font-weight:bold;color:green;':''}">${opt}</li>`)
            .join('');

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${idx}</td><td>${content}</td>
            <td><ul class="mb-0">${options}</ul></td>
            <td><button class="btn btn-outline-danger btn-sm delete-btn" data-id="${q.id}">Delete</button></td>
        `;
        tbody.appendChild(row);
        this.reset();
        toggleQuestionInput();
    };

    // AJAX Delete
    document.addEventListener('click', async (e) => {
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
});
</script>
@endsection
