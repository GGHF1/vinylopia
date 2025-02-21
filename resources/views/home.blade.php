@extends('layouts.app')

@section('title', 'Vinylopia: Home')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/homestyle.css') }}">
@endsection

@section('content')

    <div class="home-container">
        <h1>Welcome to Vinylopia</h1>
        <p>Your one-stop shop for all things vinyl</p>

        @auth
            <p>Welcome back, {{ Auth::user()->username }}!</p>
        @else
            <p>Discover, buy, and sell vinyl records with ease. Join us today!</p>
        @endauth
    </div>
@endsection
