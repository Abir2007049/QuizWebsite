@extends('layout')

@section('title', 'Quiz Details')

@section('content')
<div class="container mt-4">
    <h1>{{ $quiz->title }}</h1>
    <p><strong>Created At:</strong> {{ $quiz->created_at->format('d M, Y H:i') }}</p>
    
    <h3>Questions</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>Options</th>
                <th>Correct Option</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($quiz->questions as $question)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $question->text }}</td>
                    <td>
                        <ul>
                            @if (is_array($question->options) && count($question->options) > 0)
                                @foreach ($question->options as $key => $option)
                                    <li 
                                        @if ($key == $question->correct_option) 
                                            style="font-weight: bold; color: green;"
                                        @endif>
                                        {{ $option }}
                                    </li>
                                @endforeach
                            @else
                                <li>No options available</li>
                            @endif
                        </ul>
                    </td>
                    <td>Option {{ $question->correct_option }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No questions found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
