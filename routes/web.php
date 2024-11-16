<?php
use Illuminate\Support\Facades\Route;

// Login Route
Route::get('/login', function () {
    return view('login');  // Ensure 'login.blade.php' exists in the resources/views directory
})->name('login');

// Registration Route
Route::get('/register', function () {
    return view('signup');  // Ensure 'register.blade.php' exists in the resources/views directory
})->name('register');

// Password Reset Route
Route::get('/password/request', function () {
    return view('auth.passwords.email');  // Ensure this view exists
})->name('password.request');

// Dummy routes for Social Login (optional)
Route::post('/login/facebook', function() {
    return 'Facebook login (dummy)';
});
Route::post('/login/google', function() {
    return 'Google login (dummy)';
});
Route::post('/login/twitter', function() {
    return 'Twitter login (dummy)';
});
Route::post('/login/github', function() {
    return 'GitHub login (dummy)';
});
