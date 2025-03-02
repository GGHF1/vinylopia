@extends('layouts.app')

@section('title', $vinyl->artist . ' - ' . $vinyl->title . ' | [r' . $vinyl->release_id . '] | Vinylopia')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/releasestyle.css') }}">
@endsection

@section('content')
    <div class="release-container">
        <div class="release-grid">
            <!-- Album Cover -->
            <div class="vinyl-cover-container" onclick="openModal('{{ $vinyl->vinyl_id }}')">
                <img src="{{ $vinyl->cover }}" alt="{{ $vinyl->title }} cover" class="vinyl-cover">
                <p class="moreImages">More images</p>
            </div>

            <!-- Album Information -->
            <div class="vinyl-info">
                <h1><a href="{{ route('artist.show', ['artist' => $vinyl->artist]) }}">{{ $vinyl->artist }}</a> – {{ $vinyl->title }}</h1>
                <table class="vinyl-info-table">
                    <tr>
                        <td><strong>Label:</strong></td>
                        <td>{{ $vinyl->label }}</td>
                    </tr>
                    <tr>
                        <td><strong>Released:</strong></td>
                        <td>{{ $vinyl->year }}</td>
                    </tr>
                    <tr>
                        <td><strong>Genre:</strong></td>
                        <td>{{ $vinyl->genre }}</td>
                    </tr>
                    <tr>
                        <td><strong>Style:</strong></td>
                        <td>{{ $vinyl->style }}</td>
                    </tr>
                    <tr>
                        <td><strong>Format:</strong></td>
                        <td>
                            @php
                                $formats = explode("\n", $vinyl->format);
                            @endphp
                            @foreach($formats as $index => $format)
                                @if ($index > 0)
                                    <br>
                                @endif
                                {{ trim($format) }}
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Featuring:</strong></td>
                        <td>{{ $vinyl->feat }}</td>
                    </tr>
                    <tr>
                        <td><strong>Barcode:</strong></td>
                        <td><span class="barcode">{{ $vinyl->barcode }}</span></td>
                    </tr>
                </table>
            </div>
            <div class="purchase-section">
                <button class="buy-button">Buy Now</button>
                <button class="wishlist-button">Add to Wishlist</button>
                <a href="{{ $vinyl->spotify_link }}" target="_blank">
                    <button class="spotify-button">Open in Spotify</button>
                </a>
            </div>
        </div>

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
    <script src="{{ asset('js/marketscript.js') }}"></script>
@endsection
