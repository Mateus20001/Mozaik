<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
</head>
<body>
    <nav>
        <div class="nav-icon">
            <a href="{{ url('/') }}">
                <img src="{{ asset('favicon.ico') }}" alt="mozaikicon">
            </a>
        </div>
        <div id="navdiv"></div>
        <ul id="nav">
            <li><a class="noticon" id="firstmenu" href="{{ url('/konyvek') }}">Könyvek</a></li>
            <li id="otherside"><a href="{{ url('/profile') }}"><img id="profile" src="{{ asset('images/profile.png') }}" alt="profile"></a></li>
        </ul>
    </nav>
    <div class="content">
        @yield('content')
    </div>
</body>
</html>
