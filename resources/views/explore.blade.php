@extends('layouts.app')

@section('title', isset($query) ? 'Vinylopia: Search results' : 'Vinylopia: Explore vinyls')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/explorestyle.css') }}">
@endsection

@section('content')

    <div class="marketplace-container">
        <div class="filter-section">
            <h3>Filter By</h3>
            <!--will be added later-->
            <label><input type="checkbox"> Option 1</label>
            <label><input type="checkbox"> Option 2</label>
            <label><input type="checkbox"> Option 3</label>
            <label><input type="radio" name="filter"> Radio 1</label>
            <label><input type="radio" name="filter"> Radio 2</label>
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
                    <label for="sort-by">Sort by:</label>
                    <select id="sort-by">
                        <option value="recent">Most Recent</option>
                        <option value="popular">Most Popular</option>
                    </select>
                </div>
            </div>

            <div class="vinyl-list">
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
            <div class="pagination">
                {{ $vinyls->links('pagination::bootstrap-4') }}
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

    </script>

@endsection
