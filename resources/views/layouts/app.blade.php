<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components.header')
</head>

<body class="bg-body-tertiary">
    <div id="app">
        <nav class="navbar navbar-expand-md bg-white border-bottom">
            <div class="container-fluid">
                <a style="font-size: 16px" class="navbar-brand fw-semibold d-flex align-items-center gap-2"
                    href="{{ url('/') }}">
                    <span class="mb-0">
                        {{ env('APP_NAME') }}
                    </span>
                </a>
                <button class="navbar-toggler shadow-none p-2 border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}" id="navbarToggler">
                    <i class="ai-text-align-justified fs-3 text-dark" id="togglerIcon"></i>
                </button>

                <div class="collapse navbar-collapse mt-3 mt-md-0" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto ms-0 ms-md-5 gap-2">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('user-area/dashboard') ? 'active' : '' }}"
                                href="{{ route('user.home') }}">
                                <i class="ai-home"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('user-area/outlet*') ? 'active' : '' }}"
                                href="{{ route('user.outlet.index') }}">
                                <i class="bx bx-store-alt"></i>
                                Outlet
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('user-area/produk*') ? 'active' : '' }}"
                                href="{{ route('user.produk.index') }}">
                                <i class="ai-shipping-box-v2"></i>
                                Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('user-area/profile*') ? 'active' : '' }}"
                                href="{{ route('user.profile.edit') }}">
                                <i class="ai-person"></i>
                                Edit Profil
                            </a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link d-flex gap-2 align-items-center dropdown-toggle"
                                href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" v-pre>
                                <div class="span d-flex align-items-center justify-content-center p-1 rounded-circle bg-white"
                                    style="width: 30px; height: 30px;">
                                    <img src="{{ Auth::user()->profilIkm->gambar ? asset('storage/ikm/' . Auth::user()->profilIkm->gambar) : asset('user.png') }}"
                                        alt="">
                                </div>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container-fluid">
                @yield('content')
            </div>
        </main>
    </div>

    <footer class="mt-5 pb-2">
        <div class="container">
            <hr class="border-secondary-subtle mb-3">
            <p class="text-center fs-7 text-secondary">
                &copy; {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.
            </p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbarCollapse = document.getElementById('navbarSupportedContent');
            const togglerIcon = document.getElementById('togglerIcon');

            // Event listener untuk ketika navbar mulai collapse (show)
            navbarCollapse.addEventListener('show.bs.collapse', function() {
                togglerIcon.classList.remove('ai-text-align-justified');
                togglerIcon.classList.add('ai-cross');
            });

            // Event listener untuk ketika navbar mulai expand (hide)
            navbarCollapse.addEventListener('hide.bs.collapse', function() {
                togglerIcon.classList.remove('ai-cross');
                togglerIcon.classList.add('ai-text-align-justified');
            });
        });
    </script>

    @stack('addon-script')
</body>

</html>
