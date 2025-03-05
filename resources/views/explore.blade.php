@extends('layouts.app')

@section('title', isset($query) ? 'Vinylopia: Search results' : 'Vinylopia: Explore vinyls')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/explorestyle.css') }}">
@endsection

@section('content')

    <div class="marketplace-container">
        @if (isset($query))
            <div class="search-text">
                <h2>Search results for "{{ $query }}"</h2>
            </div>
            @if ($vinyls->count())
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
            @else
                <p>No results found for "{{ $query }}".</p>
            @endif
        @else
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
        @endif
    </div>

@endsection
