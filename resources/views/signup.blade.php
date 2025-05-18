@extends('layouts.app')

@section('title', 'Registration')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/forms/signupstyle.css') }}">
@endsection

@section('content')
    <div class="signup-container">
        <a href="{{  route('home') }}">
            <img src="{{ asset('images/elements/logo.png') }}" alt="Logo" class="container-logo">
        </a>
        <h1>Sign up to Vinylopia to continue</h1>
        <form action="{{ route('signup') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="text" id="fname" name="fname" placeholder=" " required>
                <label for="fname">First Name*</label>
            </div>
            <div class="form-group">
                <input type="text" id="lname" name="lname" placeholder=" " required>
                <label for="lname">Last Name*</label>
            </div>
            <div class="form-group">
                <input type="email" id="email" name="email" placeholder=" " required>
                <label for="email">Email*</label>
            </div>
            <div class="form-group">
                <input type="text" id="username" name="username" placeholder=" " required>
                <label for="username">Username*</label>
            </div>
            <div class="form-group">               
                <input type="password" id="password" name="password" placeholder=" " required>
                <label for="password">Password*</label>
            </div>
            <div class="form-group">               
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder=" " required>
                <label for="password_confirmation">Confirm Password*</label>
            </div>
            <div class="form-group full-width">
                <input type="text" id="address" name="address" placeholder=" " required>
                <label for="address">Shipping Address*</label>
            </div>
            <div class="form-group full-width">
                <select name="country_id" id="country" placeholder=" " required>
                    <option value="" disabled selected></option>
                    @foreach($countries as $country)
                        <option value="{{ $country->country_id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                <label for="country">Country*</label>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <p class="p">Already have an account? <a href="{{ route('login') }}" class="span">Log In</a></p>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var selects = document.querySelectorAll('.form-group select');
            selects.forEach(function(select) {
                select.addEventListener('change', function() {
                    if (select.value) {
                        select.classList.add('has-value');
                    } else {
                        select.classList.remove('has-value');
                    }
                });
                select.dispatchEvent(new Event('change'));
            });
        });
    </script>
@endsection