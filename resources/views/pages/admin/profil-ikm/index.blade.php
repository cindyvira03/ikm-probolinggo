@extends('layouts.admin')
@section('title', 'Profil IKM')

@section('content')
    <h2 class="mb-4">Profil IKM</h2>

    @include('components.alert')

    @forelse ($profilIkms as $item)
        <div class="dashboard-product-card mb-1">
            <div class="row align-items-center g-2">
                <div class="col-3 col-md-1">
                    <a href="{{ route('admin.profil-ikm.show', $item->id) }}" class="text-dark">
                        <img src="{{ $item->profilIkm->gambar ? asset('storage/ikm/' . $item->profilIkm->gambar) : asset('no-image.png') }}"
                            alt="{{ $item->profilIkm->nama_usaha }}" class="img-thumbnail"
                            style="width: 100%; height: 100px; object-fit: cover">
                    </a>
                </div>
                <div class="col-7 col-md-4">
                    <a href="{{ route('admin.profil-ikm.show', $item->id) }}" class="text-dark">
                        <p class="fw-semibold mb-0">{{ $item->profilIkm->nama_usaha }}</p>
                        <p class="fs-7 text-secondary mb-0">
                            Merek: {{ $item->profilIkm->merek }}
                        </p>
                        <p class="fs-7 text-secondary mb-0">
                            Kategori: {{ $item->profilIkm->kategori->nama_kategori }}
                        </p>
                    </a>
                </div>
                <div class="d-none d-md-block col-2 col-md-3">
                    <a href="{{ route('admin.profil-ikm.show', $item->id) }}" class="text-dark">
                        <p class="fs-7 text-secondary mb-0">
                            Nama Pemilik: {{ $item->name }}
                        </p>
                        <p class="fs-7 text-secondary mb-0">
                            No Telp: {{ $item->profilIkm->no_telp }}
                        </p>
                    </a>
                </div>
                <div class="col-2 col-md-1">
                    <span
                        class="badge {{ $item->profilIkm->status == 'aktif' ? 'bg-success' : ($item->profilIkm->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                        {{ ucfirst($item->profilIkm->status) }}
                    </span>
                </div>
                <div class="col-md-3">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.profil-ikm.show', $item->id) }}" class="btn btn-sm">
                            <i class="ai-eye-open"></i> <span class="d-none d-md-block">Detail</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <h5 class="text-muted mb-0">Belum ada data profil IKM</h5>
            <p class="text-muted">Belum ada profil IKM yang ditambahkan.</p>
        </div>
    @endforelse
@endsection
