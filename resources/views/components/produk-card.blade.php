<a href="{{ route('produk-ikm.detail', $item->id) }}" class="card-product card border mb-1 position-relative">
    <span style="top: 20px; right: 20px;" class="position-absolute bg-primary text-white rounded-pill px-2 py-1 fs-8">
        {{ $item->ikm->kategori->nama_kategori }}
    </span>
    <div class="card-body p-2">
        <img src="{{ $item->foto ? asset('storage/' . $item->foto) : asset('no-image.png') }}" alt="No Image"
            class="img-thumbnail mb-3 rounded-3 img-product">
        <div class="px-2 pb-2">
            <p class="title fw-semibold mb-0">{{ $item->nama_produk }}</p>
            <p class="text-primary fw-bold mb-0">Rp. {{ number_format($item->harga) }}</p>
            <p class="text-secondary fs-7 mb-0">{{ $item->ikm->nama_usaha }}</p>
        </div>
    </div>
</a>

<style>
    .title {
        font-size: 20px;
    }

    .img-product {
        width: 100%;
        height: 240px;
        object-fit: cover;
    }

    @media screen and (max-width: 768px) {
        .img-product {
            height: 180px;
        }

        .title {
            font-size: 16px;
        }
    }
    }
</style>
