<!-- resources/views/result/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Quiz Results</h1>

    @if($results->isEmpty())
        <p>No results available.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Quiz Title</th>
                    <th>Score</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $result)
                    <tr>
                        <td>{{ $result->quiz->title }}</td>
                        <td>{{ $result->score }}</td>
                        <td>{{ $result->created_at->format('d-m-Y H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
