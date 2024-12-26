@extends('layout')
@section('title', 'Login')
@section('content')
<div class="container">
    <form action="{{ route('login.post') }}" method="POST" class="ms-auto me-auto mt-3" style="width: 500px">
        @csrf
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" class="form-control" name="email">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <div class="text-center mt-3">
        <!-- Link to Registration Page -->
        <p>
            Don't have an account? 
            <a href="{{ route('registration') }}" class="text-decoration-none">Register</a>
        </p>
    </div>
</div>
@endsection
