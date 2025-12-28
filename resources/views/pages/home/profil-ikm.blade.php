@extends('layouts.home')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Daftar Profil IKM</li>
        </ol>
    </nav>
    <h2 class="text-dark fw-semibold mb-4">Daftar Profil IKM</h2>

    @if (count($ikm) > 0)
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach ($ikm as $item)
                <div class="col">
                    <a href="{{ route('profil-ikm.detail', $item->id) }}" class="card-product card border position-relative">
                        <span style="top: 20px; right: 20px;"
                            class="position-absolute bg-primary text-white rounded-pill px-2 py-1 fs-8">
                            {{ $item->kategori->nama_kategori }}
                        </span>
                        <div class="card-body p-2">
                            <img src="{{ $item->gambar ? asset('storage/ikm/' . $item->gambar) : asset('no-image.png') }}"
                                alt="No Image" class="img-thumbnail mb-3 rounded-3">
                            <div class="px-2 pb-2">
                                <p class="fw-semibold mb-0">{{ $item->nama_usaha }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <h5 class="text-muted mb-0">Belum ada data produk</h5>
            <p class="text-muted">Belum ada produk yang ditambahkan.</p>
        </div>
    @endif
@endsection
