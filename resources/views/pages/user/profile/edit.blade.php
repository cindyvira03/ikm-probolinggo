@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h2 class="mb-1 fw-semibold">Edit Profile</h2>
        <p class="mb-0 text-secondary">Perbarui informasi profil Anda dan IKM Anda</p>
    </div>

    @include('components.alert')

    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-white py-3">
                        <p class="mb-0 fw-semibold">Informasi Pengguna</p>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white py-3">
                        <p class="mb-0 fw-semibold">Informasi IKM</p>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="nama_usaha" class="form-label">Nama Usaha</label>
                            <input type="text" class="form-control @error('nama_usaha') is-invalid @enderror"
                                id="nama_usaha" name="nama_usaha" value="{{ old('nama_usaha', $profilIkm->nama_usaha) }}"
                                required>
                            @error('nama_usaha')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="no_telp" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp"
                                name="no_telp" value="{{ old('no_telp', $profilIkm->no_telp) }}" required>
                            @error('no_telp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="merek" class="form-label">Merek</label>
                            <input type="text" class="form-control @error('merek') is-invalid @enderror" id="merek"
                                name="merek" value="{{ old('merek', $profilIkm->merek) }}">
                            @error('merek')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select class="form-select @error('kategori_id') is-invalid @enderror" id="kategori_id"
                                name="kategori_id">
                                <option value="" disabled>Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}"
                                        {{ old('kategori_id', $profilIkm->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi_singkat" class="form-label">Deskripsi Singkat</label>
                            <textarea class="form-control @error('deskripsi_singkat') is-invalid @enderror" id="deskripsi_singkat"
                                name="deskripsi_singkat" rows="4" required>{{ old('deskripsi_singkat', $profilIkm->deskripsi_singkat) }}</textarea>
                            @error('deskripsi_singkat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border mb-3">
                    <div class="card-header bg-white py-3">
                        <p class="mb-0 fw-semibold">Logo IKM</p>
                    </div>
                    <div class="card-body">
                        <!-- Image Upload Area -->
                        <div class="image-upload-container" id="imageUploadContainer">
                            <!-- Current Image (if exists) -->
                            @if ($profilIkm->gambar)
                                <div id="currentImageContainer" class="mb-3">
                                    <img src="{{ asset('storage/ikm/' . $profilIkm->gambar) }}" alt="Logo IKM"
                                        class="img-fluid rounded mb-2" style="max-height: 200px;">
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            id="removeCurrentImage">
                                            Hapus Logo
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <div class="upload-area" id="uploadArea"
                                @if ($profilIkm->gambar) style="display: none;" @endif>
                                <div class="upload-icon">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21,15 16,10 5,21" />
                                    </svg>
                                </div>
                                <div class="upload-text">
                                    <p class="upload-title">Logo ini akan ditampilkan di halaman profil IKM Anda</p>
                                    <p class="upload-subtitle">(ukuran file maksimal 5MB)</p>
                                </div>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="button" class="btn btn-light border upload-btn" id="uploadBtn">
                                        Upload Logo
                                    </button>
                                </div>
                            </div>

                            <!-- Preview Area (hidden by default) -->
                            <div class="preview-area" id="previewArea" style="display: none;">
                                <img id="imagePreview" src="" alt="Preview" class="preview-image">
                                <div class="preview-actions">
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="removeImage">
                                        Remove
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="changeImage">
                                        Change
                                    </button>
                                </div>
                            </div>
                        </div>

                        <input type="file" id="fileInput"
                            class="form-control d-none @error('gambar') is-invalid @enderror" name="gambar"
                            accept="image/*">
                        <input type="hidden" name="delete_gambar" id="remove_foto" value="0">

                        @error('gambar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">Format: JPG, PNG, GIF, WEBP. Maksimal 5MB</small>
                    </div>
                </div>

                <div class="card border">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row gap-2">
                            <a href="{{ route('user.home') }}" class="w-100 btn btn-light border d-none d-md-block">
                                <i class="bx bx-arrow-back"></i> Kembali
                            </a>
                            <button type="submit" class="w-100 btn btn-primary" id="submitBtn">
                                <span id="buttonText">
                                    <i class="bx bx-check"></i> Simpan Perubahan
                                </span>
                                <span id="loadingSpinner" class="d-none">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"
                                        aria-hidden="true"></span>
                                    Memproses...
                                </span>
                            </button>
                            <a href="{{ route('user.home') }}" class="w-100 btn btn-light border d-block d-md-none">
                                <i class="bx bx-arrow-back"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('addon-style')
    <style>
        .image-upload-container {
            border: 2px dashed #e0e0e0;
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            background-color: #fafafa;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .image-upload-container:hover {
            border-color: #007bff;
            background-color: #f8f9ff;
        }

        .image-upload-container.dragover {
            border-color: #007bff;
            background-color: #e3f2fd;
        }

        .upload-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .upload-icon {
            color: #9e9e9e;
        }

        .upload-text {
            margin: 0;
        }

        .upload-title {
            font-size: 16px;
            color: #333;
            margin-bottom: 4px;
            font-weight: 500;
        }

        .upload-subtitle {
            font-size: 14px;
            color: #666;
            margin: 0;
        }

        .upload-btn {
            padding: 8px 24px;
        }

        .preview-area {
            position: relative;
        }

        .preview-image {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .preview-actions {
            margin-top: 16px;
            display: flex;
            gap: 8px;
            justify-content: center;
        }
    </style>
@endpush

@push('addon-script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadContainer = document.getElementById('imageUploadContainer');
            const uploadArea = document.getElementById('uploadArea');
            const previewArea = document.getElementById('previewArea');
            const fileInput = document.getElementById('fileInput');
            const imagePreview = document.getElementById('imagePreview');
            const uploadBtn = document.getElementById('uploadBtn');
            const removeBtn = document.getElementById('removeImage');
            const changeBtn = document.getElementById('changeImage');
            const removeCurrentImageBtn = document.getElementById('removeCurrentImage');
            const currentImageContainer = document.getElementById('currentImageContainer');
            const removeFotoInput = document.getElementById('remove_foto');

            // Form submission handling
            const profileForm = document.getElementById('profileForm');
            const submitBtn = document.getElementById('submitBtn');
            const buttonText = document.getElementById('buttonText');
            const loadingSpinner = document.getElementById('loadingSpinner');

            // Handle form submission
            profileForm.addEventListener('submit', function(e) {
                // Disable the button
                submitBtn.disabled = true;

                // Hide button text and show loading spinner
                buttonText.classList.add('d-none');
                loadingSpinner.classList.remove('d-none');

                // Optional: Re-enable button after some time if there's an error
                // This prevents the button from being permanently disabled if validation fails
                setTimeout(function() {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        buttonText.classList.remove('d-none');
                        loadingSpinner.classList.add('d-none');
                    }
                }, 15000); // Re-enable after 15 seconds as fallback
            });

            // Click to upload
            uploadContainer.addEventListener('click', function() {
                if (uploadArea.style.display !== 'none' && (!currentImageContainer || currentImageContainer
                        .style.display === 'none')) {
                    fileInput.click();
                }
            });

            uploadBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.click();
            });

            // Handle remove current image
            if (removeCurrentImageBtn) {
                removeCurrentImageBtn.addEventListener('click', function() {
                    if (currentImageContainer) {
                        currentImageContainer.style.display = 'none';
                        uploadArea.style.display = 'block';
                        removeFotoInput.value = '1'; // Mark for deletion
                    }
                });
            }

            // Drag and drop functionality
            uploadContainer.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadContainer.classList.add('dragover');
            });

            uploadContainer.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadContainer.classList.remove('dragover');
            });

            uploadContainer.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadContainer.classList.remove('dragover');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    handleFile(files[0]);
                }
            });

            // File input change
            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    handleFile(e.target.files[0]);
                }
            });

            // Handle file selection
            function handleFile(file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.');
                    fileInput.value = '';
                    return;
                }

                // Validate file size (5MB = 5242880 bytes)
                if (file.size > 5242880) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    fileInput.value = '';
                    return;
                }

                // Create file reader
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    uploadArea.style.display = 'none';
                    previewArea.style.display = 'block';

                    // Hide current image if exists
                    if (currentImageContainer) {
                        currentImageContainer.style.display = 'none';
                    }

                    // Reset remove flag since we're uploading a new image
                    removeFotoInput.value = '0';
                };
                reader.readAsDataURL(file);

                // Set the file to input (for form submission)
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
            }

            // Remove image
            removeBtn.addEventListener('click', function() {
                fileInput.value = '';
                imagePreview.src = '';
                previewArea.style.display = 'none';

                // If there was a current image, show it again
                if (currentImageContainer) {
                    currentImageContainer.style.display = 'block';
                    removeFotoInput.value = '0'; // Reset remove flag
                } else {
                    uploadArea.style.display = 'block';
                }
            });

            // Change image
            changeBtn.addEventListener('click', function() {
                fileInput.click();
            });
        });
    </script>
@endpush
