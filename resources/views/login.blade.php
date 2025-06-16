@extends('layouts.app')

@section('title', 'Log in')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/forms/loginstyle.css') }}">
@endsection

@section('content')
    <div class="signup-container">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/elements/logo.png') }}" alt="Logo" class="container-logo">
        </a>
        <h1>Sign in to continue</h1>
        <form action="{{ route('login') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="text" id="username" name="username" placeholder=" " required>
                <label for="username">Username*</label>
            </div>
            <div class="form-group">               
                <input type="password" id="password" name="password" placeholder=" " required>
                <label for="password">Password*</label>
            </div>
            <div class="flex-row">
                <div>
                <input type="checkbox">
                <label>Remember me </label>
                </div>
                <span class="span">Forgot password?</span>
            </div>
            <button type="submit" class="btn">Continue</button>
            
            @if ($errors->has('login'))
                <p class="error-message">{{ $errors->first('login') }}</p>
            @endif
        </form>
        <p class="p">Don't have an account? <a href="{{ route('signup') }}" class="span">Sign Up</a></p>
    </div>
@endsection
