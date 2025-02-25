@extends('layouts.app')

@section('title', 'Vinylopia: Marketplace')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/marketstyle.css') }}">
@endsection

@section('content')

    <div class="marketplace-container" onload="checkOverflow()">
        @foreach($vinyls as $vinyl)
            <div class="vinyl-item">
                <img src="{{ $vinyl->cover }}" alt="{{ $vinyl->title }} cover" class="vinyl-cover" onclick="openModal({{ $vinyl->vinyl_id }})">
                <div class="text-container">
                    <h2>{{ $vinyl->artist }} - {{ $vinyl->title }}</h2>
                    <p><strong>Label:</strong> {{ $vinyl->label }}</p>
                    <p><strong>Year:</strong> {{ $vinyl->year }}</p>
                    <a href="{{ route('vinyl.details', $vinyl->vinyl_id) }}">View release</a>
                </div>
            </div>

            <div class="img-gallery" id="gallery-{{ $vinyl->vinyl_id }}" style="display: none;">
                <img src="{{ $vinyl->cover }}" class="gallery-img">
                @foreach(json_decode($vinyl->secondary_cover) as $secondaryCover) 
                    <img src="{{ $secondaryCover }}" class="d-block">
                @endforeach
            </div>

        @endforeach
    </div>

    <div id="myModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img id="modal-img" src="">
        </div>
        <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
        <a class="next" onclick="changeSlide(1)">&#10095;</a>

        <div id="thumbnails-container" class="thumbnails-container">
            <!--added dynamically-->
        </div>
    </div>
    <script src="{{ asset('js/marketscript.js') }}"></script>

@endsection