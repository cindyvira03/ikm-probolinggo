@extends('layouts.auth')

@section('content')
    <div class="head d-flex flex-column align-items-center justify-content-center gap-3 mb-4">
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR1HzMUPQSrTAmjS6MZYu35KS3GVzXfnWsjpg&s" class="logo"
            width="50" alt="">
        <div class="text-center text-md-start ">
            <h4 class="fw-semibold mb-0">{{ config('app.name') }}</h4>
        </div>
    </div>
    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <div class="mb-3">
            <label for="email">Alamat Email</label>

            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                value="{{ old('email') }}" required autocomplete="email" autofocus>

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
                    name="password" required autocomplete="current-password">
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


        <div class="mb-1">
            <button type="submit" class="btn btn-primary w-100" id="loginButton">
                <span id="buttonText">Masuk</span>
                <span id="loadingSpinner" class="d-none">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Memproses...
                </span>
            </button>
        </div>
        <p class="text-center text-secondary"> Belum memiliki akun?
            <a href="{{ route('register') }}" class="text-primary">Daftar</a>
        </p>
    </form>
@endsection

@push('addon-style')
    <style>
        .head {
            margin-top: 100px;
        }

        @media screen and (max-width: 768px) {
            .head {
                margin-top: 50px;
            }
        }
    </style>
@endpush

@push('addon-script')
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const loginForm = document.querySelector('#loginForm');
        const loginButton = document.querySelector('#loginButton');
        const buttonText = document.querySelector('#buttonText');
        const loadingSpinner = document.querySelector('#loadingSpinner');

        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle the icon
            this.querySelector('i').classList.toggle('ai-eye-open');
            this.querySelector('i').classList.toggle('ai-eye-closed');
        });

        // Handle form submission
        loginForm.addEventListener('submit', function(e) {
            // Disable the button
            loginButton.disabled = true;

            // Hide button text and show loading spinner
            buttonText.classList.add('d-none');
            loadingSpinner.classList.remove('d-none');

            // Optional: Re-enable button after some time if there's an error
            // This prevents the button from being permanently disabled if validation fails
            setTimeout(function() {
                if (loginButton.disabled) {
                    loginButton.disabled = false;
                    buttonText.classList.remove('d-none');
                    loadingSpinner.classList.add('d-none');
                }
            }, 10000); // Re-enable after 10 seconds as fallback
        });
    </script>
@endpush
