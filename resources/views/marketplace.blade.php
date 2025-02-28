@extends('layouts.app')

@section('title', 'Vinylopia: Marketplace')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/marketstyle.css') }}">
@endsection

@section('content')

    <div class="marketplace-container">
        @foreach($vinyls as $vinyl)
            <div class="vinyl-item">
                <a href="{{ route('vinyl.release', $vinyl->vinyl_id) }}">
                    <img src="{{ $vinyl->cover }}" alt="{{ $vinyl->title }} cover" class="vinyl-cover">
                </a>
                <div class="text-container">
                    <h2>{{ $vinyl->artist }} - {{ $vinyl->title }}</h2>
                    <p><strong>Year:</strong> {{ $vinyl->year }}</p>
                    <p><strong>Format:</strong> {!! nl2br(e($vinyl->format)) !!}</p>
                    <a href="{{ route('vinyl.release', $vinyl->vinyl_id) }}">View release</a>
                </div>
            </div>
        @endforeach
    </div>

@endsection