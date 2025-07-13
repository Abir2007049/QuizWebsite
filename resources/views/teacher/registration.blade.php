@extends('layout')
@section('title', 'Registration')
@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fa;">
    <div class="card shadow p-4" style="width: 400px; border-radius: 10px;">
        <h2 class="text-center mb-4" style="color: #28a745;">Register</h2>
        <form action="{{ route('registration.post') }}" method="POST">
            @csrf 
            <div class="mb-3">
                <label for="name" class="form-label" style="font-weight: 600;">Name</label>
                <input type="text" id="name" class="form-control" name="name" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label" style="font-weight: 600;">Email Address</label>
                <input type="email" id="email" class="form-control" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label" style="font-weight: 600;">Password</label>
                <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" required>
            </div>
            <div class="mb-3">
                <label for="room_name" class="form-label" style="font-weight: 600;">Room Name</label>
                <input type="text" id="room_name" class="form-control" name="room_name" placeholder="Enter the room name" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn" style="background-color: #28a745; color: white; border-radius: 5px;">Register</button>
            </div>
        </form>
        @if (session('error'))
            <div class="alert alert-danger mt-3">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>
@endsection
