<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="icon" href="{{ asset('images/elements/icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/layouts/app.css') }}">
    @yield('head')
    <title>@yield('title', 'Vinylopia')</title>
</head>
<body>
    <div class="page-container">
        <div class="header">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/elements/logo.png') }}" alt="Vinylopia Logo" class="logo">
            </a>
            <div class="left-nav">
                <div class="search-container">
                    <input type="text" id="search" placeholder="Search for albums..." autocomplete="off">
                    <div id="search-results" class="dropdown-content"></div>
                </div>
                <div class="button-container">
                    <form action="{{ route('explore') }}" method="get">
                        <button type="submit" class="explore-btn">Explore</button>
                    </form>
                    <form action="{{ route('marketplace') }}" method="get">
                        <button type="submit" class="market-btn">Marketplace</button>
                    </form>
                </div>
            </div>
            <div class="right-nav">
                @auth
                    <form action="{{ route('profile')}}" method="get">
                        @csrf
                        <button type="submit" class="avatar-btn">
                            <img 
                                src="{{ asset(Auth::user()->avatar ? 'storage/' . Auth::user()->avatar : 'images/avatars/default-avatar.png') }}" 
                                alt="User Avatar" 
                                class="user-avatar"
                            >
                        </button>
                    </form>    
                    <form action="{{ route('logout')}}" method="post">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                @else
                    <form action="{{ route('login') }}" method="get">
                        @csrf
                        <button type="submit">Log In</button>
                    </form>    
                    <form action="{{ route('signup') }}" method="get">
                        @csrf
                        <button type="submit">Sign Up</button>
                    </form>
                @endauth

                <div class="cart-class">
                    <div class="cart-container">
                        <img src="{{  asset('images/elements/cart.png') }}" class="cart-icon" alt="Cart">
                    </div>
                    <div class="cart-container-hover">
                        <img src="{{  asset('images/elements/cart-hover.png') }}" class="cart-icon" alt="Cart">
                    </div>
                </div>
                
                <div class="mobile-drawer">
                    <div class="mobile-icon-container" id="mobile-icon">
                        <img src="{{ asset('images/elements/drawer.png') }}" class="mobile-icon" alt="Menu">
                    </div>
                </div>
            </div>
        </div>    
        <div class="main-container">
            <div class="mobile-drawer-content">
                <form action="{{ route('explore') }}" method="get">
                @csrf
                    <button type="submit" class="explore-btn">Explore</button>
                </form>
                <form action="{{ route('marketplace') }}" method="get">
                @csrf
                    <button type="submit" class="market-btn">Marketplace</button>
                </form>
            </div>
            
            @yield('content')
        </div>
        <footer class="footer">
            <p>&copy;2025 Vinylopia Contact us</a></p>
        </footer>
    </div>
    <script>
        
        let vinylsresult = JSON.parse("{!! addslashes(json_encode($vinylsresult)) !!}");
        
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        document.getElementById('search').addEventListener('input', debounce(function() {
            let query = this.value.toLowerCase();
            let results = vinylsresult.filter(vinyl => vinyl.title.toLowerCase().includes(query) || vinyl.artist.toLowerCase().includes(query));
            let dropdown = document.getElementById('search-results');
            dropdown.innerHTML = '';

            if (query.length >= 2) {
                if (results.length > 0) {
                    let fragment = document.createDocumentFragment();
                    results.slice(0, 3).forEach(vinyl => {
                        let item = document.createElement('div');
                        item.classList.add('dropdown-item');
                        
                        let cover = document.createElement('img');
                        cover.src = vinyl.cover;
                        cover.alt = vinyl.title;
                        cover.classList.add('dropdown-cover');

                        let info = document.createElement('div');
                        info.classList.add('dropdown-info');

                        let artist = document.createElement('div');
                        artist.classList.add('dropdown-artist');
                        artist.textContent = vinyl.artist;

                        let title = document.createElement('div');
                        title.classList.add('dropdown-title');
                        title.textContent = vinyl.title;

                        info.appendChild(artist);
                        info.appendChild(title);

                        item.appendChild(cover);
                        item.appendChild(info);

                        item.addEventListener('click', function() {
                            window.location.href = `http://127.0.0.1:8000/explore/release/${vinyl.vinyl_id}`;
                        });

                        fragment.appendChild(item);
                    });
                    let viewAllItem = document.createElement('div');
                    viewAllItem.classList.add('dropdown-item', 'view-all');
                    viewAllItem.textContent = 'View all results';
                    viewAllItem.addEventListener('click', function() {
                        window.location.href = `/explore/search?q=${query}`;
                    });
                    fragment.appendChild(viewAllItem);

                    dropdown.appendChild(fragment);
                    dropdown.style.display = 'block';
                } else {
                    dropdown.style.display = 'none';
                }
            } else {
                dropdown.style.display = 'none';
            }
        }, 300));

        document.getElementById('search').addEventListener('focus', function() {
            let dropdown = document.getElementById('search-results');
            if (this.value.length >= 2) {
                dropdown.style.display = 'block';
            }
        });

        document.addEventListener('click', function(event) {
            let dropdown = document.getElementById('search-results');
            if (!dropdown.contains(event.target) && event.target.id !== 'search') {
                dropdown.style.display = 'none';
            }
        });

        document.getElementById('mobile-icon').addEventListener('click', function() {
            let drawerContent = document.querySelector('.mobile-drawer-content');

            if (drawerContent.style.display === 'block') {
            drawerContent.style.display = 'none';
            } else {
            drawerContent.style.display = 'block';
            }
        });

        window.addEventListener('resize', function() {
            let drawerContent = document.querySelector('.mobile-drawer-content');
            
            if (window.innerWidth > 1125) {
            drawerContent.style.display = 'none';
            }
        });

    </script>

</body>
</html>