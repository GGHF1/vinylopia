<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <div class="top-nav">
                <input type="text" placeholder="Search..">
                <div class="button-container">
                    <form action="{{ route('marketplace') }}" method="get">
                        @csrf
                        <button type="submit">Marketplace</button>
                    </form>
                </div>
            </div>
            <div class="user-nav">
                @auth
                    <form action="{{ route('profile')}}" method="get">
                        @csrf
                        <button type="submit">Profile</button>
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
            </div>
        </div>    
        <div class="main-container">
            @yield('content')
        </div>
        <footer class="footer">
            <p>&copy;2025 Vinylopia Contact us</a></p>
        </footer>
    </div>
</body>
</html>