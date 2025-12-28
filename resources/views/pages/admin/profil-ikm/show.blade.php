@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Detail Profil IKM</h2>
        <a href="{{ route('admin.profil-ikm.index') }}" class="btn btn-light border">
            <i class="ai-arrow-back"></i> Kembali
        </a>
    </div>

    <div class="card border mb-3">
        <div class="card-header bg-white py-3">
            <p class="mb-0 fw-semibold">Informasi IKM</p>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-2">
                    <img src="{{ $profilIkm->profilIkm->gambar ? asset('storage/ikm/' . $profilIkm->profilIkm->gambar) : asset('no-image.png') }}"
                        alt="{{ $profilIkm->profilIkm->nama_usaha }}" class="img-thumbnail"
                        style="width: 100%; object-fit: cover">
                </div>
                <div class="col-md-10">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="mb-0 fs-7 text-secondary">Nama Usaha</p>
                            <p class="mb-0">{{ $profilIkm->profilIkm->nama_usaha }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0 fs-7 text-secondary">No Telp</p>
                            <p class="mb-0">{{ $profilIkm->profilIkm->no_telp }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0 fs-7 text-secondary">Kategori</p>
                            <p class="mb-0">{{ $profilIkm->profilIkm->kategori->nama_kategori }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0 fs-7 text-secondary">Merek</p>
                            <p class="mb-0">{{ $profilIkm->profilIkm->merek }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0 fs-7 text-secondary">Deskripsi Singkat</p>
                            <p class="mb-0">{{ $profilIkm->profilIkm->deskripsi_singkat }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-0 fs-7 text-secondary">Status</p>
                            <select class="form-select" id="status-select" data-ikm-id="{{ $profilIkm->profilIkm->id }}">
                                <option value="pending" {{ $profilIkm->profilIkm->status == 'pending' ? 'selected' : '' }}>
                                    Pending
                                </option>
                                <option value="aktif" {{ $profilIkm->profilIkm->status == 'aktif' ? 'selected' : '' }}>
                                    Aktif
                                </option>
                                <option value="tidak_aktif"
                                    {{ $profilIkm->profilIkm->status == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif
                                </option>
                            </select>
                            <div id="status-message" class="mt-2"></div>
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
            @forelse ($profilIkm->profilIkm->outlets as $item)
                <div class="card border mb-1">
                    <div class="card-body row align-items-center g-2 g-md-5">
                        <div class="col-3 col-md-2">
                            <img src="{{ $item->foto_lokasi_tampak_depan ? asset('storage/' . $item->foto_lokasi_tampak_depan) : asset('no-image.png') }}"
                                alt="{{ $item->alamat }}" class="img-thumbnail rounded-3" style="width: 100%">
                        </div>
                        <div class="col-7 col-md-8">
                            <p class="fw-semibold mb-0">{{ $item->alamat }}</p>
                            <p class="fs-7 text-secondary mb-0 d-none d-md-block">
                                {{ $item->lokasi_googlemap }}
                            </p>
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
            @forelse ($profilIkm->profilIkm->produk as $item)
                <div class="card border mb-1">
                    <div class="card-body row align-items-center g-2 g-md-5">
                        <div class="col-3 col-md-1">
                            <img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('no-image.png') }}"
                                alt="No Image" class="img-thumbnail rounded-3">
                        </div>
                        <div class="col-7 col-md-5">
                            <p class="fs-5 fw-semibold mb-0">{{ $item->nama_produk }}</p>
                            <p class="text-secondary fs-7 mb-0">{{ $item->jenis_produk }}</p>
                        </div>
                        <div class="col-md-2 d-none d-md-block">
                            <p class="fw-semibold mb-0">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
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
        </div>
    </div>
@endsection

@push('addon-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status-select');
            const statusMessage = document.getElementById('status-message');

            statusSelect.addEventListener('change', function() {
                const ikmId = this.getAttribute('data-ikm-id');
                const status = this.value;
                const originalStatus = this.getAttribute('data-original-status');

                // Disable select while processing
                statusSelect.disabled = true;

                // Show loading message
                statusMessage.innerHTML = '<small class="text-info">Memperbarui status...</small>';

                // Send AJAX request
                fetch(`/admin/profil-ikm/${ikmId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content')
                        },
                        body: JSON.stringify({
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            statusMessage.innerHTML =
                                '<small class="text-success">Status berhasil diperbarui</small>';
                            // Update data-original-status attribute
                            statusSelect.setAttribute('data-original-status', status);
                        } else {
                            statusMessage.innerHTML =
                                `<small class="text-danger">${data.message}</small>`;
                            // Revert to original value if failed
                            statusSelect.value = originalStatus;
                        }
                    })
                    .catch(error => {
                        statusMessage.innerHTML =
                            '<small class="text-danger">Terjadi kesalahan saat memperbarui status</small>';
                        console.error('Error:', error);
                        // Revert to original value if failed
                        statusSelect.value = originalStatus;
                    })
                    .finally(() => {
                        // Re-enable select
                        statusSelect.disabled = false;

                        // Clear status message after 3 seconds
                        setTimeout(() => {
                            statusMessage.innerHTML = '';
                        }, 3000);
                    });
            });

            // Store original status for reverting if needed
            statusSelect.setAttribute('data-original-status', statusSelect.value);
        });
    </script>
@endpush
