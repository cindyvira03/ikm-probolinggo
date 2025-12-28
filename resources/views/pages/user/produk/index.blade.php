@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Daftar Produk</h2>
        <a href="{{ route('user.produk.create') }}" class="btn btn-primary">
            <i class="ai-plus"></i> Tambah <span class="d-none d-md-block">Produk</span>
        </a>
    </div>

    @include('components.alert')

    @forelse ($produk as $item)
        <div class="dashboard-product-card mb-1">
            <div class="row align-items-center g-2">
                <div class="col-3 col-md-1">
                    <a href="{{ route('user.produk.edit', $item->id) }}" class="text-dark">
                        <img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('no-image.png') }}"
                            alt="No Image" class="img-thumbnail">
                    </a>
                </div>
                <div class="col-7 col-md-5">
                    <a href="{{ route('user.produk.edit', $item->id) }}" class="text-dark">
                        <p class="fs-5 fw-semibold mb-0">{{ $item->nama_produk }}</p>
                        <p class="text-secondary fs-7 mb-0">{{ $item->jenis_produk }}</p>
                    </a>
                </div>
                <div class="col-md-2 d-none d-md-block">
                    <p class="fw-semibold mb-0">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                </div>
                <div class="col-2 col-md-4">
                    <div class="d-flex justify-content-end pe-md-4">
                        <a href="{{ route('user.produk.edit', $item->id) }}" class="btn btn-sm">
                            <i class="ai-edit"></i> <span class="d-none d-md-block">Edit Produk</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <h5 class="text-muted mb-0">Belum ada data produk</h5>
            <p class="text-muted">Belum ada produk yang ditambahkan.</p>
            <div class="d-flex justify-content-center">
                <a href="{{ route('user.produk.create') }}" class="btn btn-primary">
                    <i class="ai-plus"></i> Tambah Produk Pertama
                </a>
            </div>
        </div>
    @endforelse
@endsection
