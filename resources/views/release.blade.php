@extends('layouts.app')

@section('title', $vinyl->artist . ' - ' . $vinyl->title . ' | [r' . $vinyl->release_id . '] | Vinylopia')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/releasestyle.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" 
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                @php
                    // Get count of listings for this vinyl
                    $listingsCount = App\Models\Listing::where('vinyl_id', $vinyl->vinyl_id)->count();
                    
                    // Get minimum price from listings
                    $minPrice = App\Models\Listing::where('vinyl_id', $vinyl->vinyl_id)
                                    ->min('price');
                    
                    // Format the price with currency symbol
                    $formattedPrice = $minPrice ? '€' . number_format($minPrice, 2) : 'N/A';
                @endphp
                
                <div class="listing-summary">
                    @if($listingsCount > 0)
                        <p>{{ $listingsCount }} {{ $listingsCount == 1 ? 'copy' : 'copies' }} from {{ $formattedPrice }}</p>
                    @else
                        <p>No copies currently for sale</p>
                    @endif
                </div>
                <div class="button-group">
                    <a href="{{ route('listing.create', ['vinyl_id' => $vinyl->vinyl_id]) }}" class="sell-button">Sell Your Copy</a>
                    <button class="buy-button">Buy Now</button>
                    <button class="wishlist-button">Add to Wishlist</button>
                </div>
                <div class="streaming-links">
                    <a href="{{ $vinyl->spotify_link }}" target="_blank" class="spotify-link">
                        <img src="{{ asset('images/elements/spotify.png') }}" alt="Spotify" class="spotify">
                    </a>
                    <a href="{{ $vinyl->itunes_link }}" target="_blank" class="apple-music-link">
                        <img src="{{ asset('images/elements/apple-music.png') }}" alt="Apple Music" class="apple-music">
                    </a>
                </div>
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
