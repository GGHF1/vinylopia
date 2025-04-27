@extends('layouts.app')

@section('title', 'Sell Your Vinyl | Vinylopia')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/listingcreate.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="listing-create-container">
        <h1>Sell Your Vinyl</h1>
        
        <div class="step-indicator">
            <div class="step active" id="step-1">
                <div class="step-number">1</div>
                <div class="step-label">Find Your Vinyl</div>
            </div>
            <div class="step-line"></div>
            <div class="step" id="step-2">
                <div class="step-number">2</div>
                <div class="step-label">Confirm Selection</div>
            </div>
            <div class="step-line"></div>
            <div class="step" id="step-3">
                <div class="step-number">3</div>
                <div class="step-label">Set Price & Condition</div>
            </div>
        </div>
        
        <!-- Step 1 -->
        <div class="step-content" id="step-1-content">
            <p class="step-description">Search for the vinyl you want to sell by entering artist name, title, year or barcode.</p>
            
            <div class="search-form">
                <div class="form-group">
                    <label for="artist">Artist</label>
                    <input type="text" id="artist" class="form-control" placeholder="Enter artist name">
                    <div id="artist-suggestions" class="suggestions-dropdown"></div>
                </div>
                
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" class="form-control" placeholder="Enter vinyl title">
                    <div id="title-suggestions" class="suggestions-dropdown"></div>
                </div>
                
                <div class="form-row">
                    <div class="form-group half">
                        <label for="year">Year</label>
                        <input type="text" id="year" class="form-control" placeholder="Enter release year">
                        <div id="year-suggestions" class="suggestions-dropdown"></div>
                    </div>
                    
                    <div class="form-group half">
                        <label for="barcode">Barcode</label>
                        <input type="text" id="barcode" class="form-control" placeholder="Enter barcode">
                        <div id="barcode-suggestions" class="suggestions-dropdown"></div>
                    </div>
                </div>
            </div>
            
            <div class="search-results">
                <h3>Search Results</h3>
                <div id="vinyl-results" class="vinyl-results-container">
                    <p class="no-results">Enter at least 3 characters in any field above to see matching results.</p>
                </div>
            </div>
        </div>
        
        <!-- Step 2 -->
        <div class="step-content d-none" id="step-2-content">
            <p class="step-description">Confirm this is the vinyl you want to sell.</p>
            
            <div id="selected-vinyl" class="selected-vinyl"></div>
            
            <div class="button-row">
                <button id="back-to-step-1" class="secondary-button">Back to Search</button>
                <button id="confirm-selection" class="primary-button">Confirm Selection</button>
            </div>
        </div>
        
        <!-- Step 3 -->
        <div class="step-content d-none" id="step-3-content">
            <p class="step-description">Set the price and condition for your vinyl listing.</p>
            
            <form id="listing-form" method="POST" action="{{ route('listing.store') }}">
                @csrf
                <input type="hidden" id="selected_vinyl_id" name="vinyl_id">
                
                <div class="form-row">
                    <div class="form-group half">
                        <label for="price">Price (€)</label>
                        <input type="number" id="price" name="price" class="form-control" placeholder="0.00" step="0.01" min="0.01" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group half">
                        <label for="vinyl_condition">Vinyl Condition</label>
                        <select id="vinyl_condition" name="vinyl_condition" class="form-control" required>
                            <option value="" disabled selected>Select condition</option>
                            <option value="Mint">Mint (M)</option>
                            <option value="Near Mint">Near Mint (NM)</option>
                            <option value="Very Good Plus">Very Good Plus (VG+)</option>
                            <option value="Very Good">Very Good (VG)</option>
                            <option value="Good/Good Plus">Good/Good Plus (G/G+)</option>
                            <option value="Poor/Fair">Poor/Fair (P/F)</option>
                        </select>
                    </div>
                    
                    <div class="form-group half">
                        <label for="cover_condition">Cover Condition</label>
                        <select id="cover_condition" name="cover_condition" class="form-control" required>
                            <option value="" disabled selected>Select condition</option>
                            <option value="Mint">Mint (M)</option>
                            <option value="Near Mint">Near Mint (NM)</option>
                            <option value="Very Good Plus">Very Good Plus (VG+)</option>
                            <option value="Very Good">Very Good (VG)</option>
                            <option value="Good/Good Plus">Good/Good Plus (G/G+)</option>
                            <option value="Poor/Fair">Poor/Fair (P/F)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="comments">Comments (Optional)</label>
                    <textarea id="comments" name="comments" class="form-control" placeholder="Add any additional information about your vinyl (e.g., specific defects, special features, etc.)" rows="4"></textarea>
                </div>
                
                <div class="button-row">
                    <button id="back-to-step-2" type="button" class="secondary-button">Back</button>
                    <button type="submit" class="primary-button">List For Sale</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const step1 = document.getElementById('step-1');
        const step2 = document.getElementById('step-2');
        const step3 = document.getElementById('step-3');
        
        const step1Content = document.getElementById('step-1-content');
        const step2Content = document.getElementById('step-2-content');
        const step3Content = document.getElementById('step-3-content');
        
        const artistInput = document.getElementById('artist');
        const titleInput = document.getElementById('title');
        const yearInput = document.getElementById('year');
        const barcodeInput = document.getElementById('barcode');
        
        const artistSuggestions = document.getElementById('artist-suggestions');
        const titleSuggestions = document.getElementById('title-suggestions');
        const yearSuggestions = document.getElementById('year-suggestions');
        const barcodeSuggestions = document.getElementById('barcode-suggestions');
        
        const vinylResults = document.getElementById('vinyl-results');
        const selectedVinyl = document.getElementById('selected-vinyl');
        const selectedVinylId = document.getElementById('selected_vinyl_id');
        
        const backToStep1 = document.getElementById('back-to-step-1');
        const confirmSelection = document.getElementById('confirm-selection');
        const backToStep2 = document.getElementById('back-to-step-2');
        
        let selectedVinylData = null;
        
        // Functions
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }
        
        async function searchVinyls(query, field) {
            if (query.length < 3) return [];
            
            try {
                const response = await fetch("{{ route('listing.search') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ query, field })
                });
                
                return await response.json();
            } catch (error) {
                console.error('Error searching vinyls:', error);
                return [];
            }
        }
        
        function updateSearchResults(results) {
            vinylResults.innerHTML = '';
            
            if (results.length === 0) {
                vinylResults.innerHTML = '<p class="no-results">No results found. Try different search terms.</p>';
                return;
            }
            
            results.forEach(vinyl => {
                const resultItem = document.createElement('div');
                resultItem.classList.add('vinyl-result-item');
                resultItem.innerHTML = `
                    <div class="vinyl-thumb">
                        <img src="${vinyl.cover}" alt="${vinyl.title}">
                    </div>
                    <div class="vinyl-details">
                        <h4>${vinyl.artist} - ${vinyl.title}</h4>
                        <p><strong>Year:</strong> ${vinyl.year}</p>
                        <p><strong>Barcode:</strong> ${vinyl.barcode || 'N/A'}</p>
                        <button class="select-vinyl-btn" data-vinyl-id="${vinyl.vinyl_id}">Select</button>
                    </div>
                `;
                vinylResults.appendChild(resultItem);
                
                // Add click event to the select button
                resultItem.querySelector('.select-vinyl-btn').addEventListener('click', function() {
                    selectedVinylData = vinyl;
                    goToStep2(vinyl);
                });
            });
        }

        @if(isset($preSelectedVinyl))
            const preSelectedVinyl = {
                vinyl_id: {{ $preSelectedVinyl->vinyl_id }},
                artist: "{{ $preSelectedVinyl->artist }}",
                title: "{{ $preSelectedVinyl->title }}",
                year: "{{ $preSelectedVinyl->year }}",
                barcode: "{{ $preSelectedVinyl->barcode }}",
                cover: "{{ $preSelectedVinyl->cover }}"
            };
            selectedVinylData = preSelectedVinyl;
            goToStep2(preSelectedVinyl);
        @endif
        
        function goToStep2(vinyl) {
            // Update selected vinyl display
            selectedVinyl.innerHTML = `
                <div class="vinyl-details-large">
                    <div class="vinyl-cover-large">
                        <img src="${vinyl.cover}" alt="${vinyl.title}">
                    </div>
                    <div class="vinyl-info-large">
                        <h3>${vinyl.artist} - ${vinyl.title}</h3>
                        <p><strong>Year:</strong> ${vinyl.year}</p>
                        <p><strong>Barcode:</strong> ${vinyl.barcode || 'N/A'}</p>
                    </div>
                </div>
            `;
            
            // Hide step 1, show step 2
            step1Content.classList.add('d-none');
            step2Content.classList.remove('d-none');
            
            // Update step indicators
            step1.classList.remove('active');
            step2.classList.add('active');
        }
        
        function goToStep3() {
            // Set the selected vinyl ID
            selectedVinylId.value = selectedVinylData.vinyl_id;
            
            // Hide step 2, show step 3
            step2Content.classList.add('d-none');
            step3Content.classList.remove('d-none');
            
            // Update step indicators
            step2.classList.remove('active');
            step3.classList.add('active');
        }
        
        // Search input event handlers
        const handleSearch = debounce(async function(input, field) {
            const query = input.value.trim();
            if (query.length < 3) {
                return;
            }
            
            const results = await searchVinyls(query, field);
            updateSearchResults(results);
        }, 300);
        
        artistInput.addEventListener('input', () => handleSearch(artistInput, 'artist'));
        titleInput.addEventListener('input', () => handleSearch(titleInput, 'title'));
        yearInput.addEventListener('input', () => handleSearch(yearInput, 'year'));
        barcodeInput.addEventListener('input', () => handleSearch(barcodeInput, 'barcode'));
        
        // Navigation buttons
        backToStep1.addEventListener('click', function() {
            step2Content.classList.add('d-none');
            step1Content.classList.remove('d-none');
            step2.classList.remove('active');
            step1.classList.add('active');
        });
        
        confirmSelection.addEventListener('click', function() {
            goToStep3();
        });
        
        backToStep2.addEventListener('click', function() {
            step3Content.classList.add('d-none');
            step2Content.classList.remove('d-none');
            step3.classList.remove('active');
            step2.classList.add('active');
        });
    });
</script>
@endsection