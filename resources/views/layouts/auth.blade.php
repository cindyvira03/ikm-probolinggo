<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components.header')
    <style>
        .scrollable-content {
            height: 100vh;
            overflow-y: auto;
            padding: 2rem;
        }

        .scrollable-content::-webkit-scrollbar {
            width: 0;
            background: transparent;
            /* Chrome/Safari/Webkit */
        }

        .scrollable-content {
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE/Edge */
        }

        .sticky-image {
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .sticky-image img {
            height: 100vh;
            width: 100%;
            object-fit: cover;
        }

        body {
            overflow: hidden;
        }

        /* Responsive untuk mobile */
        @media (max-width: 767.98px) {
            .content-column {
                width: 100%;
                flex: 0 0 100%;
                max-width: 100%;
            }

            .image-column {
                display: none;
                /* Menyembunyikan kolom gambar pada mobile */
            }

            body {
                overflow-y: auto;
                /* Mengaktifkan scroll pada body untuk mobile */
            }

            .scrollable-content {
                height: auto;
                min-height: 100vh;

            }
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <main id="app" class="row m-0">
            <div class="col-md-5 p-0 content-column">
                <div class="scrollable-content">
                    @yield('content')
                </div>
            </div>
            <div class="col-md-7 p-0 image-column">
                <div class="sticky-image">
                    <img src="{{ asset('batik.jpg') }}"
                        alt="">
                </div>
            </div>
        </main>
    </div>

    @stack('addon-script')
</body>

</html>
