<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<link rel="shortcut icon" href="{{ asset('images/probolinggo-logo.png') }}" type="image/x-icon">
<title>@yield('title', config('app.name'))</title>

<!-- Scripts -->
{{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
<link rel="stylesheet" href="{{ asset('build/assets/app-DK18Jj3S.css') }}">
<script src="{{ asset('build/assets/app-BZpq9W-k.js') }}"></script>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

<script src="https://unpkg.com/akar-icons-fonts"></script>
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

@stack('addon-style')
