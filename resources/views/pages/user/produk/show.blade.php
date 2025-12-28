@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Detail Produk</h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('user.produk.edit', $produk->id) }}" class="btn btn-warning">
                                <i class="ai-edit"></i> Edit
                            </a>
                            <a href="{{ route('user.produk.index') }}" class="btn btn-secondary">
                                <i class="ai-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                @if ($produk->foto)
                                    <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama_produk }}"
                                        class="img-fluid rounded">
                                @else
                                    <img src="{{ asset('no-image.png') }}" alt="No Image" class="img-fluid rounded">
                                @endif
                            </div>
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="200">Nama Produk</th>
                                        <td>: {{ $produk->nama_produk }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Produk</th>
                                        <td>: {{ $produk->jenis_produk }}</td>
                                    </tr>
                                    <tr>
                                        <th>Harga</th>
                                        <td>: Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Varian</th>
                                        <td>: {{ $produk->varian ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ukuran</th>
                                        <td>: {{ $produk->ukuran ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>IKM</th>
                                        <td>: {{ $produk->ikm->nama_ikm ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Dibuat</th>
                                        <td>: {{ $produk->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Diperbarui</th>
                                        <td>: {{ $produk->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Deskripsi</h5>
                                <div class="border rounded p-3 bg-light">
                                    {{ $produk->deskripsi }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
