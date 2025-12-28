@extends('layouts.home')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('produk-ikm') }}">Daftar Produk IKM</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $produk->nama_produk }}</li>
        </ol>
    </nav>

    <div class="row g-3 mt-2 mt-md-5">
        <div class="col-md-4">
            <img src="{{ $produk->foto ? asset('storage/' . $produk->foto) : asset('no-image.png') }}" alt="No Image"
                class="img-thumbnail rounded-3 img-product">
        </div>
        <div class="col-md-8">
            <span class="bg-primary text-white rounded-pill px-2 py-1 fs-8">
                {{ $produk->ikm->kategori->nama_kategori }}
            </span>
            <h2 class="fw-bold mt-1">{{ $produk->nama_produk }}</h2>
            <p class="text-primary fw-bold mb-0">Rp. {{ number_format($produk->harga) }}</p>
            <a href="{{ route('profil-ikm', $produk->ikm->id) }}"
                class="text-secondary fs-7">{{ $produk->ikm->nama_usaha }}</a>
            <div class="mt-3 fs-7 text-secondary">
                @if ($produk->jenis_produk)
                    <p class="mb-0">
                        Jenis Produk: {{ $produk->jenis_produk }}
                    </p>
                @endif
                @if ($produk->varian)
                    <p class="mb-0">
                        Varian: {{ $produk->varian }}
                    </p>
                @endif
                @if ($produk->ukuran)
                    <p>
                        Ukuran: {{ $produk->ukuran }}
                    </p>
                @endif
                <p class="mb-0">
                    {{ $produk->deskripsi }}
                </p>
                @if ($produk->ikm->outlets()->first())
                    <p class="mb-0">
                        Cara Order: {!! $produk->ikm->outlets()->first()->cara_order !!}
                    </p>
                @endif
            </div>
            <div class="d-flex gap-2 mt-3">
                <a href="https://wa.me/{{ $produk->ikm->no_telp }}" class="btn btn-success px-4">
                    <i class="bx bxl-whatsapp"></i> Chat Seller
                </a>
            </div>
        </div>
    </div>
@endsection
