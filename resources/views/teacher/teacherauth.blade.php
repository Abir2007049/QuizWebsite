@extends('layout')

@section('title', 'Login')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #f8f9fa;">
    <div class="card shadow p-4" style="width: 400px; border-radius: 10px;">
        <h2 class="text-center mb-4" style="color: #28a745;">Login</h2>
        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label" style="font-weight: 600;">Email Address</label>
                <input type="email" id="email" class="form-control" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-1">
                <label for="password" class="form-label" style="font-weight: 600;">Password</label>
                <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" required>
            </div>

            <div class="mb-3 text-end">
                <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: #28a745; font-weight: 600;">
                    Forgot Your Password?
                </a>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn" style="background-color: #28a745; color: white; border-radius: 5px;">Login</button>
            </div>
        </form>
        <div class="text-center mt-4">
            <p style="color: #6c757d;">
                Don't have an account? 
                <a href="{{ route('registration') }}" class="text-decoration-none" style="color: #28a745; font-weight: 600;">Register</a>
            </p>
        </div>
    </div>
</div>
@endsection
