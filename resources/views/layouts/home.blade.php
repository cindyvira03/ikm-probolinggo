<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components.header')
</head>

<body class="bg-body-tertiary">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white py-3 border-bottom">
            <div class="container-fluid">
                <a class="navbar-brand fw-semibold d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <span class="mb-0 fs-6">
                        {{ env('APP_NAME') }}
                    </span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mx-auto gap-2">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">
                                Beranda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('rumah-batik') ? 'active' : '' }}"
                                href="{{ route('rumah-batik') }}">
                                Rumah Sentra Batik
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('produk-ikm*') ? 'active' : '' }}"
                                href="{{ route('produk-ikm') }}">
                                Produk IKM
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('outlet-ikm') ? 'active' : '' }}"
                                href="{{ route('outlet-ikm') }}">
                                Outlet IKM
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profil-ikm') ? 'active' : '' }}"
                                href="{{ route('profil-ikm') }}">
                                Profil IKM
                            </a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav gap-2">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                             <li class="nav-item">
                                <a class="nav-link {{ request()->is('profil-ikm') ? 'active' : '' }}"
                                    href="{{ route('user.home') }}">
                                    Dashboard Anda 
                                </a>
                            </li>
                            <!--<li class="nav-item dropdown">-->
                            <!--    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"-->
                            <!--        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>-->
                            <!--        {{ Auth::user()->name }}-->
                            <!--    </a>-->

                            <!--    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">-->
                            <!--        <a class="dropdown-item" href="{{ route('logout') }}"-->
                            <!--            onclick="event.preventDefault();-->
                            <!--                         document.getElementById('logout-form').submit();">-->
                            <!--            {{ __('Logout') }}-->
                            <!--        </a>-->

                            <!--        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">-->
                            <!--            @csrf-->
                            <!--        </form>-->
                            <!--    </div>-->
                            <!--</li>-->
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="{{ asset('jquery/jquery.js') }}"></script>
    @stack('addon-script')
</body>

</html>
