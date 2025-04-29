@extends('layouts.app')

@section('title', 'Vinylopia: Marketplace')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/marketstyle.css') }}">
@endsection

@section('content')
    <div class="marketplace-container">
        <div class="filter-section">
            <h3>Filter By</h3>
            <form id="filter-form" method="get" action="{{ route('marketplace') }}">
                <label for="genre">Genre:</label>
                <select name="genre" id="genre" onchange="updateFilters()">
                    <option value="">All</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>{{ $genre }}</option>
                    @endforeach
                </select>
        
                <label for="artist">Artist:</label>
                <select name="artist" id="artist" onchange="updateFilters()">
                    <option value="">All</option>
                    @foreach($artists as $artist)
                        <option value="{{ $artist }}" {{ request('artist') == $artist ? 'selected' : '' }}>{{ $artist }}</option>
                    @endforeach
                </select>
        
                <label for="year">Year:</label>
                <input type="text" name="year" id="year" placeholder="Enter year" value="{{ request('year') }}" oninput="updateYearFilter()">
                
                <div style="text-align: center; margin-top: 10px;">
                    <a href="{{ route('marketplace') }}" class="clear-filters">Clear Filters</a>
                </div>
            </form>
        </div>

        <div class="results-container">
            <div class="wrapper-section">
                <div class="scanner-container">
                    <button id="start-scan">Scan Barcode</button>
                    <div class="help-tip">
                        <p>Scan the barcode on the back side of the vinyl cover.</p>
                    </div>
                </div>
                <div class="sort-section">
                <select id="sort-by">
                    <option value="">Default</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                    <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Newest First</option>
                    <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Oldest First</option>
                </select>
                </div>
            </div>

            <div class="vinyl-list">
                @if($listings->count() > 0)
                    @foreach($listings as $listing)
                        <div class="listing-item">
                            <div class="listing-image">
                                <a href="{{ route('vinyl.release', $listing->vinyl->vinyl_id) }}">
                                    <img src="{{ $listing->vinyl->cover }}" alt="{{ $listing->vinyl->title }} cover" class="vinyl-cover">
                                </a>
                            </div>
                            <div class="listing-details">
                                <h2><a href="{{ route('vinyl.release', $listing->vinyl->vinyl_id) }}">{{ $listing->vinyl->artist }} - {{ $listing->vinyl->title }}</a></h2>
                                <div class="listing-info">
                                    <div class="listing-meta">
                                        <p><strong>Seller:</strong> {{ $listing->user->username }}</p>
                                        <p><strong>Year:</strong> {{ $listing->vinyl->year }}</p>
                                        <p><strong>Condition:</strong> <span class="condition-badge">Vinyl: {{ $listing->vinyl_condition }}</span> <span class="condition-badge">Cover: {{ $listing->cover_condition }}</span></p>
                                        @if($listing->comments)
                                            <p><strong>Comments:</strong> {{ \Illuminate\Support\Str::limit($listing->comments, 100) }}</p>
                                        @endif
                                    </div>
                                    <div class="listing-actions">
                                        <div class="price">€{{ number_format($listing->price, 2) }}</div>
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="listing_id" value="{{ $listing->listing_id }}">
                                            <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="no-listings">
                        <p>No listings found matching your criteria.</p>
                        <p>Try changing your filters or <a href="{{ route('listing.create') }}">add your own vinyl for sale</a>.</p>
                    </div>
                @endif
            </div>
            
            <div class="pagination">
                {{ $listings->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <div id="barcodeModal" class="video-modal">
        <div class="video-modal-content">
            <span class="video-close" onclick="closeBarcodeModal()">&times;</span>
            <div class="video-container">
                <video id="barcode-scanner" autoplay></video>
                <div class="scan-line"></div>
            </div>
        </div>
    </div>
    
    <script>
        let vinylRelease = JSON.parse("{!! addslashes(json_encode($vinylRelease)) !!}");
        document.getElementById('start-scan').addEventListener('click', function () {
            openBarcodeModal();

            let videoElement = document.getElementById('barcode-scanner');

            /* 
            quagga doesnt support outputting video img 
            so we need to use the video element directly
            */
            navigator.mediaDevices.getUserMedia({
                video: { facingMode: "environment" }
            })
            .then((stream) => {
                videoElement.srcObject = stream;
                videoElement.play();
            })
            .catch((err) => {
                alert("Failed to access the camera. Please check your permissions.");
                closeBarcodeModal();
            });

            Quagga.init({
                inputStream: {
                    type: "LiveStream",
                    target: videoElement 
                },
                decoder: {
                    readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader"]
                }
            }, function (err) {
                if (err) {
                    alert("Failed to initialize barcode scanner.");
                    closeBarcodeModal();
                    return;
                }
                Quagga.start();
            });

            Quagga.onDetected(function (result) {
                let barcode = result.codeResult.code;
                console.log("Barcode detected: ", barcode);
                let vinyl = vinylRelease.find(v => {
                    let storedBarcode = v.barcode;

                    // If the stored barcode doesn't start with '0', add a zero
                    if (!storedBarcode.startsWith('0')) {
                        storedBarcode = `0${storedBarcode}`;
                    }

                    // Match the modified stored barcode with the scanned barcode
                    return storedBarcode === barcode;
                });
                if (vinyl) {
                    window.location.href = `http://127.0.0.1:8000/explore/release/${vinyl.vinyl_id}`;
                } else {
                    console.log("No vinyl found with this barcode.");
                    alert("No vinyl found with this barcode.");
                }
                Quagga.stop();
                closeBarcodeModal();
            });
        });

        document.getElementById('sort-by').addEventListener('change', function () {
            const sort = this.value;
            const params = new URLSearchParams(window.location.search);
            if (sort) {
                params.set('sort', sort);
            } else {
                params.delete('sort');
            }
            window.location.href = window.location.pathname + '?' + params.toString();
        });

        function openBarcodeModal() {
                    document.getElementById('barcodeModal').style.display = 'block';
                }

        function closeBarcodeModal() {
            document.getElementById('barcodeModal').style.display = 'none';
            Quagga.stop();

            let videoElement = document.getElementById('barcode-scanner');
            let stream = videoElement.srcObject;
            if (stream) {
                let tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
            }
            videoElement.srcObject = null; 
        }

        function updateFilters() {
            const params = new URLSearchParams(window.location.search);

            const genre = document.getElementById('genre').value;
            const artist = document.getElementById('artist').value;

            if (genre) {
                params.set('genre', genre);
            } else {
                params.delete('genre');
            }

            if (artist) {
                params.set('artist', artist);
            } else {
                params.delete('artist');
            }

            window.location.href = window.location.pathname + '?' + params.toString();
        }

        let debounceTimer;

        function updateYearFilter() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const params = new URLSearchParams(window.location.search);
                const year = document.getElementById('year').value;

                if (year) {
                    params.set('year', year);
                } else {
                    params.delete('year');
                }

                window.location.href = window.location.pathname + '?' + params.toString();
            }, 1000); // Delay only for year
        }

    </script>
@endsection