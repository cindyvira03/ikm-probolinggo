@extends('layouts.admin')

@section('content')
    <div class="mb-4">
        <h2 id="greeting" class="mb-1 fw-semibold">
            Selamat Datang, {{ Auth::user()->name }}
        </h2>
        <p class="mb-0 text-secondary">
            Anda sebagai admin
        </p>
    </div>

    <section>
        <h5 class="mb-3 fw-semibold">Overview</h5>
        <div class="row row-cols-1 row-cols-md-3 g-3">
            <div class="col">
                <div class="card border card-dashboard">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-4">
                            {{-- <a href="{{ route('user.outlet.index') }}"> --}}
                            <span
                                class="bg-primary rounded-4 p-2 text-white d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bx bx-store-alt fs-1"></i>
                            </span>
                            {{-- </a> --}}
                            <div>
                                <h4 class="text-dark fw-semibold mb-0">
                                    {{ number_format($jumlahIKM) }} IKM
                                </h4>
                                <p class="text-secondary mb-0">Total IKM yang terdaftar</p>
                                {{-- <a href="{{ route('user.outlet.index') }}"
                                    class="justify-content-start btn btn-link btn-sm text-primary ps-0 d-inline">
                                    Manage IKM <i class="ai-arrow-right"></i>
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border card-dashboard">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-4">
                            {{-- <a href="{{ route('user.outlet.index') }}"> --}}
                            <span
                                class="bg-primary rounded-4 p-2 text-white d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bx bx-store-alt fs-1"></i>
                            </span>
                            {{-- </a> --}}
                            <div>
                                <h4 class="text-dark fw-semibold mb-0">
                                    {{ number_format($jumlahOutlet) }} Outlet
                                </h4>
                                <p class="text-secondary mb-0">Total Outlet yang terdaftar</p>
                                {{-- <a href="{{ route('user.outlet.index') }}"
                                    class="justify-content-start btn btn-link btn-sm text-primary ps-0 d-inline">
                                    Manage Outlet <i class="ai-arrow-right"></i>
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border card-dashboard">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-4">
                            {{-- <a href="{{ route('user.produk.index') }}"> --}}
                            <span
                                class="bg-primary rounded-4 p-2 text-white d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="ai-shipping-box-v2 fs-1"></i>
                            </span>
                            {{-- </a> --}}
                            <div>
                                <h4 class="text-dark fw-semibold mb-0">
                                    {{ number_format($jumlahProduk) }} Produk
                                </h4>
                                <p class="text-secondary mb-0">
                                    @if ($jumlahProduk > 0)
                                        Total produk yang terdaftar
                                    @else
                                        Belum ada produk yang terdaftar
                                    @endif
                                </p>
                                {{-- <a href="{{ route('user.produk.index') }}"
                                    class="justify-content-start btn btn-link btn-sm text-primary ps-0 d-inline">
                                    Manage Produk <i class="ai-arrow-right"></i>
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('addon-script')
    <script>
        function updateGreeting() {
            const now = new Date();
            const hour = now.getHours();
            const userName = '{{ Auth::user()->name }}';
            let greeting;

            if (hour >= 5 && hour < 11) {
                greeting = 'Selamat Pagi';
            } else if (hour >= 11 && hour < 15) {
                greeting = 'Selamat Siang';
            } else if (hour >= 15 && hour < 18) {
                greeting = 'Selamat Sore';
            } else {
                greeting = 'Selamat Malam';
            }

            document.getElementById('greeting').textContent = `${greeting}, ${userName} 👋`;
        }

        // Update greeting when page loads
        document.addEventListener('DOMContentLoaded', updateGreeting);
    </script>
@endpush
