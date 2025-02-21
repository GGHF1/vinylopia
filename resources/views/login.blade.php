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
        <h1>Sign in to Vinylopia to continue</h1>
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
            <button type="submit" class="btn">Continue</button>
        </form>
    </div>
@endsection
