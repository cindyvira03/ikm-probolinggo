@extends('layouts.app')

@section('content')
    <div class="mb-3 d-flex flex-wrap justify-content-between align-items-center">
        <h2 class="mb-0">Daftar Outlet</h2>
        <a href="{{ route('user.outlet.create') }}" class="btn btn-primary">
            <i class="ai-plus"></i> Tambah Outlet
        </a>
    </div>

    @include('components.alert')

    @forelse ($outlets as $item)
        <div class="dashboard-product-card mb-1">
            <div class="row align-items-center g-2">
                <div class="col-3 col-md-1">
                    <a href="{{ route('user.outlet.edit', $item->id) }}" class="text-dark">
                        <img src="{{ $item->foto_lokasi_tampak_depan ? asset('storage/' . $item->foto_lokasi_tampak_depan) : asset('no-image.png') }}"
                            alt="{{ $item->alamat }}" class="img-thumbnail" style="width: 100%">
                    </a>
                </div>
                <div class="col-7 col-md-8">
                    <a href="{{ route('user.outlet.edit', $item->id) }}" class="text-dark">
                        <p class="fw-semibold mb-0">{{ $item->alamat }}</p>
                        <p class="fs-7 text-secondary mb-0 d-none d-md-block">
                            {{ $item->lokasi_googlemap }}
                        </p>
                    </a>
                </div>
                <div class="col-2 col-md-3">
                    <div class="d-flex justify-content-end pe-md-4">
                        <a href="{{ route('user.outlet.edit', $item->id) }}" class="btn btn-sm">
                            <i class="ai-edit"></i> <span class="d-none d-md-block">Edit Outlet</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <h5 class="text-muted mb-0">Belum ada data outlet</h5>
            <p class="text-muted">Belum ada outlet yang ditambahkan.</p>
            <div class="d-flex justify-content-center">
                <a href="{{ route('user.outlet.create') }}" class="btn btn-primary">
                    <i class="ai-plus"></i> Tambah Outlet Pertama
                </a>
            </div>
        </div>
    @endforelse
@endsection
