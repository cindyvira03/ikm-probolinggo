@extends('layouts.home')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Daftar Outlet IKM</li>
        </ol>
    </nav>
    <h2 class="text-dark fw-semibold mb-4">Daftar Outlet IKM</h2>

    @if (count($outlet) > 0)
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-3 g-3">
            @foreach ($outlet as $item)
                <div class="col">
                    <div class="card border position-relative">
                        <span style="top: 20px; right: 20px;"
                            class="position-absolute bg-primary text-white rounded-pill px-2 py-1 fs-8">
                            {{ $item->profilIkm->kategori->nama_kategori }}
                        </span>
                        <div class="card-body p-2">
                            <img src="{{ $item->foto_lokasi_tampak_depan ? asset('storage/' . $item->foto_lokasi_tampak_depan) : asset('no-image.png') }}"
                                alt="No Image" class="img-thumbnail mb-3 rounded-3">
                            <div class="px-2 pb-2">
                                <p class="fw-semibold mb-0">{{ $item->alamat }}</p>
                                <p class="text-secondary fs-7 mb-0">{{ $item->profilIkm->nama_usaha }}</p>
                                <a href="{{ $item->lokasi_googlemap }}" target="_blank" class="btn btn-success btn-sm mt-3">
                                    Google Maps
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <h5 class="text-muted mb-0">Belum ada data outlet</h5>
            <p class="text-muted">Belum ada outlet yang ditambahkan.</p>
        </div>
    @endif
@endsection

@push('addon-style')
    <style>
        img {
            width: 100%;
            height: 240px !important;
            object-fit: cover;
        }

        @media screen and (max-width: 768px) {
            img {
                height: 180px !important;
            }
        }
    </style>
@endpush
