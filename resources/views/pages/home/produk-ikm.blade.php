@extends('layouts.home')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Daftar Produk IKM</li>
        </ol>
    </nav>
    <h2 class="text-dark fw-semibold mb-4">Daftar Produk IKM</h2>

    <!-- Link kategori -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="{{ route('produk-ikm') }}" class="btn {{ !isset($currentKategori) ? 'btn-primary' : 'btn-light border' }}">
            Semua
        </a>
        @foreach ($kategori as $kat)
            <a href="{{ route('produk-ikm.kategori', $kat->slug) }}"
                class="btn {{ isset($currentKategori) && $currentKategori->id == $kat->id ? 'btn-primary' : 'btn-light border' }}">
                {{ $kat->nama_kategori }}
            </a>
        @endforeach
    </div>

    <!-- Tampilan produk -->
    @if (count($produk) > 0)
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach ($produk as $item)
                <div class="col">
                    @include('components.produk-card')
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <h5 class="text-muted mb-0">Belum ada data produk</h5>
            <p class="text-muted">
                {{ isset($currentKategori) ? 'Belum ada produk untuk kategori ini.' : 'Belum ada produk yang ditambahkan.' }}
            </p>
        </div>
    @endif
@endsection
