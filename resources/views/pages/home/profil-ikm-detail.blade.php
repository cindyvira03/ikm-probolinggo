@extends('layouts.home')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profil-ikm') }}">Daftar Profil IKM</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $ikm->nama_usaha }}</li>
        </ol>
    </nav>
    <h2 class="text-dark fw-semibold mb-4">{{ $ikm->nama_usaha }}</h2>

    <div class="card border mb-3">
        <div class="card-header bg-white py-3">
            <p class="mb-0 fw-semibold">Informasi IKM</p>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-2">
                    <img src="{{ $ikm->gambar ? asset('storage/ikm/' . $ikm->gambar) : asset('no-image.png') }}"
                        alt="{{ $ikm->nama_usaha }}" class="img-thumbnail" style="width: 100%; object-fit: cover">
                </div>
                <div class="col-md-10">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-0 fs-7 text-secondary">Nama Usaha</p>
                            <p class="mb-0">{{ $ikm->nama_usaha }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0 fs-7 text-secondary">No Telp</p>
                            <p class="mb-0">{{ $ikm->no_telp }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0 fs-7 text-secondary">Kategori</p>
                            <p class="mb-0">{{ $ikm->kategori->nama_kategori }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0 fs-7 text-secondary">Merek</p>
                            <p class="mb-0">{{ $ikm->merek }}</p>
                        </div>
                        <div class="col-12">
                            <p class="mb-0 fs-7 text-secondary">Deskripsi Singkat</p>
                            <p class="mb-0">{{ $ikm->deskripsi_singkat }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border mb-3">
        <div class="card-header bg-white py-3">
            <p class="mb-0 fw-semibold">Outlet</p>
        </div>
        <div class="card-body">
            @forelse ($ikm->outlets as $item)
                <div class="card border mb-1">
                    <div class="card-body row align-items-center g-2 g-md-5">
                        <div class="col-3 col-md-2">
                            <img src="{{ $item->foto_lokasi_tampak_depan ? asset('storage/' . $item->foto_lokasi_tampak_depan) : asset('no-image.png') }}"
                                alt="{{ $item->alamat }}" class="img-thumbnail rounded-3" style="width: 100%">
                        </div>
                        <div class="col-7 col-md-6">
                            <p class="fw-semibold mb-0">{{ $item->alamat }}</p>
                            <p class="fs-7 text-secondary mb-0 d-none d-md-block">
                                {{ $item->lokasi_googlemap }}
                            </p>
                        </div>
                        <div class="col-12 col-md-2">
                            <a href="{{ $item->lokasi_googlemap }}" target="_blank" class="btn btn-success btn-sm mt-3">
                                Google Maps
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <h5 class="text-muted mb-0">Belum ada data outlet</h5>
                    <p class="text-muted">Belum ada outlet yang ditambahkan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="card border">
        <div class="card-header bg-white py-3">
            <p class="mb-0 fw-semibold">Produk</p>
        </div>
        <div class="card-body">
            @forelse ($ikm->produk as $item)
                <a href="{{ route('produk-ikm.detail', $item->id) }}" class="card-product card border mb-1">
                    <div class="card-body row align-items-center g-2 g-md-5">
                        <div class="col-2 col-md-1">
                            <img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('no-image.png') }}"
                                alt="No Image" class="img-thumbnail rounded-3">
                        </div>
                        <div class="col-6 col-md-5">
                            <p class="title fw-semibold mb-0">{{ $item->nama_produk }}</p>
                            <p class="text-secondary fs-7 mb-0">{{ $item->jenis_produk }}</p>
                        </div>
                        <div class="col-4">
                            <p class="fw-semibold mb-0">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-5">
                    <h5 class="text-muted mb-0">Belum ada data produk</h5>
                    <p class="text-muted">Belum ada produk yang ditambahkan.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection

@push('addon-style')
    <style>
        .title {
            font-size: 20px;
        }
        
        @media screen and (max-width: 768px) {
            .title {
                font-size: 16px;
            }
        }
    </style>
@endpush