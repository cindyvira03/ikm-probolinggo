@extends('layouts.auth')

@section('content')
    <div class="d-flex flex-column align-items-center justify-content-center gap-3 mb-4">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1HzMUPQSrTAmjS6MZYu35KS3GVzXfnWsjpg&s" class="logo"
            width="50" alt="">
        <div class="text-center text-md-start ">
            <h4 class="fw-semibold mb-0">{{ config('app.name') }}</h4>
        </div>
    </div>
    <form id="form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf
        <span class="badge bg-primary fw-semibold">Data Usaha</span>

        <div class="mb-3 mt-3">
            <label for="nama_usaha">Nama Usaha</label>
            <input id="nama_usaha" type="text" class="form-control @error('nama_usaha') is-invalid @enderror"
                name="nama_usaha" value="{{ old('nama_usaha') }}" required autofocus>
            @error('nama_usaha')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="no_telp">No. Telepon</label>
            <input id="no_telp" type="text" class="form-control @error('no_telp') is-invalid @enderror" name="no_telp"
                value="{{ old('no_telp', '62') }}" required>
            <span class="text-secondary fs-7">No. telepon harus diawali 62 tanpa +</span>
            @error('no_telp')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="merek">Merek</label>
            <input id="merek" type="text" class="form-control @error('merek') is-invalid @enderror" name="merek"
                value="{{ old('merek') }}" required>
            @error('merek')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="kategori_id">Kategori</label>
            <select id="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" name="kategori_id"
                required>
                <option value="">Pilih Kategori</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                        {{ $kategori->nama_kategori }}
                    </option>
                @endforeach
            </select>
            @error('kategori_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Data Pengguna --}}
        <span class="badge bg-primary fw-semibold">Data Pengguna</span>

        <div class="mb-3 mt-3">
            <label for="name">Nama Lengkap</label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                value="{{ old('name') }}" required autocomplete="name">
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email">Alamat Email</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password">Password</label>
            <div class="input-group">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="new-password">
                <button class="btn btn-light border" type="button" id="togglePassword">
                    <i class="ai-eye-open"></i>
                </button>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password-confirm">Konfirmasi Password</label>
            <div class="input-group">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                    autocomplete="new-password">
                <button class="btn btn-light border" type="button" id="togglePasswordConfirm">
                    <i class="ai-eye-open"></i>
                </button>
            </div>
        </div>

        <div class="mb-1">
            <button type="submit" class="btn btn-primary w-100" id="registerButton">
                <span id="buttonText">Daftar</span>
                <span id="loadingSpinner" class="d-none">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Memproses...
                </span>
            </button>
        </div>
        <p class="text-center text-secondary"> Sudah memiliki akun?
            <a href="{{ route('login') }}" class="text-primary">Masuk</a>
        </p>
    </form>
@endsection

@push('addon-script')
    <script>
        // Toggle password visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const togglePasswordConfirm = document.querySelector('#togglePasswordConfirm');
        const passwordConfirm = document.querySelector('#password-confirm');
        const registerForm = document.getElementById('form');
        const registerButton = document.querySelector('#registerButton');
        const buttonText = document.querySelector('#buttonText');
        const loadingSpinner = document.querySelector('#loadingSpinner');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('ai-eye-open');
            this.querySelector('i').classList.toggle('ai-eye-closed');
        });

        togglePasswordConfirm.addEventListener('click', function() {
            const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirm.setAttribute('type', type);
            this.querySelector('i').classList.toggle('ai-eye-open');
            this.querySelector('i').classList.toggle('ai-eye-closed');
        });

        registerForm.addEventListener('submit', function(e) {
            registerButton.disabled = true;

            buttonText.classList.add('d-none');
            loadingSpinner.classList.remove('d-none');

            setTimeout(function() {
                if (registerButton.disabled) {
                    registerButton.disabled = false;
                    buttonText.classList.remove('d-none');
                    loadingSpinner.classList.add('d-none');
                }
            }, 10000);
        });
    </script>
@endpush
