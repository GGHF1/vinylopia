@extends('layouts.app')

@section('title', $vinyl->artist . ' - ' . $vinyl->title . ' | [r' . $vinyl->release_id . '] | Vinylopia')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/releasestyle.css') }}">
@endsection

@section('content')
    <div class="release-container">
        <div class="vinyl-header">
            <div class="vinyl-cover-container" onclick="openModal('{{ $vinyl->vinyl_id }}')">
                <img src="{{ $vinyl->cover }}" alt="{{ $vinyl->title }} cover" class="vinyl-cover">
                <p class="moreImages">More images</p>
            </div>
            <div class="vinyl-info">
                <h1><a href="{{ route('artist.show', ['artist' => $vinyl->artist]) }}">{{ $vinyl->artist }}</a> – {{ $vinyl->title }}</h1>
                <p><strong>Label:</strong> {{ $vinyl->label }}</p>
                <p><strong>Released:</strong> {{ $vinyl->year }}</p>
                <p><strong>Genre:</strong> {{ $vinyl->genre }}</p>
                <p><strong>Style:</strong> {{ $vinyl->style }}</p>
                <p><strong>Format:</strong> {!! nl2br(e($vinyl->format)) !!}</p>
                <p><strong>Featuring:</strong> {{ $vinyl->feat }}</p>
                <p><strong>Barcode:</strong> <span class="barcode">{{ $vinyl->barcode }}</span></p>
            </div>
        </div>

        <div class="img-gallery" id="gallery-{{ $vinyl->vinyl_id }}" style="display: none;">
            <img src="{{ $vinyl->cover }}" class="gallery-img">
            @foreach(json_decode($vinyl->secondary_cover) as $secondaryCover) 
                <img src="{{ $secondaryCover }}" class="d-block">
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

        <h2>Tracklist</h2>
        <table class="tracklist">
            <thead>
                <tr>
                    <th>Position</th>
                    <th>Title</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vinyl->tracks as $track)
                    <tr>
                        <td>{{ $track->position }}</td>
                        <td>{{ $track->title }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="purchase-section">
            <button class="buy-button">Buy Now</button>
            <button class="wishlist-button">Add to Wishlist</button>
        </div>
    </div>
@endsection
