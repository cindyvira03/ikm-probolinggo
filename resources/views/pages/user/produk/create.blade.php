@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h2 class="mb-0">Tambah Produk Baru</h2>
        <p class="mb-0 text-secondary">Isi informasi produk baru</p>
    </div>

    <form action="{{ route('user.produk.store') }}" method="POST" enctype="multipart/form-data" id="createForm">
        @csrf

        <div class="row g-3">
            <div class="col-md-8">
                <div class="card border">
                    <div class="card-header bg-white py-3">
                        <p class="mb-0 fw-semibold">Informasi Produk</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nama_produk" class="form-label">Nama Produk <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama_produk') is-invalid @enderror"
                                    id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" required>
                                @error('nama_produk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="jenis_produk" class="form-label">Jenis Produk <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('jenis_produk') is-invalid @enderror"
                                    id="jenis_produk" name="jenis_produk" value="{{ old('jenis_produk') }}" required>
                                @error('jenis_produk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="harga_display" class="form-label">Harga <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control @error('harga') is-invalid @enderror"
                                        id="harga_display" placeholder="0" required
                                        style="border-end-end-radius: 12px; border-start-end-radius: 12px">
                                    <input type="hidden" id="harga" name="harga" value="{{ old('harga') }}">
                                </div>
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="varian" class="form-label">Varian</label>
                                <input type="text" class="form-control @error('varian') is-invalid @enderror"
                                    id="varian" name="varian" value="{{ old('varian') }}">
                                @error('varian')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="ukuran" class="form-label">Ukuran</label>
                                <input type="text" class="form-control @error('ukuran') is-invalid @enderror"
                                    id="ukuran" name="ukuran" value="{{ old('ukuran') }}">
                                @error('ukuran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="deskripsi" class="form-label">Deskripsi <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4"
                                    required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border mb-3">
                    <div class="card-header bg-white py-3">
                        <p class="mb-0 fw-semibold">Foto Produk</p>
                    </div>
                    <div class="card-body">
                        <div class="image-upload-container" id="imageUploadContainer">
                            <div class="upload-area" id="uploadArea">
                                <div class="upload-icon">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21,15 16,10 5,21" />
                                    </svg>
                                </div>
                                <div class="upload-text">
                                    <p class="upload-title">Foto produk akan ditampilkan di halaman katalog</p>
                                    <p class="upload-subtitle">(ukuran file maksimal 5MB)</p>
                                </div>
                                <div class="d-flex justify-content-center mt-1">
                                    <button type="button" class="btn btn-light border btn-sm upload-btn" id="uploadBtn">
                                        Upload Image
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

                        <input type="file" id="foto"
                            class="form-control d-none @error('foto') is-invalid @enderror" name="foto"
                            accept="image/*">

                        <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 5MB.</small>
                        @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card border">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row gap-2">
                            <a href="{{ route('user.produk.index') }}"
                                class="btn btn-light border w-100 d-none d-md-block">
                                <i class="ai-arrow-back"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                <span id="submitText">
                                    <i class="ai-check"></i> Simpan Produk
                                </span>
                                <span id="loadingText" style="display: none;">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"
                                        aria-hidden="true"></span>
                                    Menyimpan...
                                </span>
                            </button>
                            <a href="{{ route('user.produk.index') }}"
                                class="btn btn-light border w-100 d-block d-md-none">
                                <i class="ai-arrow-back"></i> Kembali
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
            max-height: 150px;
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
            const hargaDisplay = document.getElementById('harga_display');
            const hargaHidden = document.getElementById('harga');

            // Format number with thousand separators
            function formatNumber(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            // Remove formatting and get raw number
            function unformatNumber(str) {
                return str.replace(/,/g, '');
            }

            // Set initial value if old value exists
            if (hargaHidden.value) {
                hargaDisplay.value = formatNumber(hargaHidden.value);
            }

            // Handle input event for formatting
            hargaDisplay.addEventListener('input', function(e) {
                let value = e.target.value;

                // Remove all non-numeric characters except comma
                value = value.replace(/[^0-9,]/g, '');

                // Remove existing commas to get raw number
                let rawValue = unformatNumber(value);

                // Only allow numbers
                if (rawValue && !isNaN(rawValue)) {
                    // Format with commas
                    e.target.value = formatNumber(rawValue);
                    // Set hidden field with raw number
                    hargaHidden.value = rawValue;
                } else if (rawValue === '') {
                    e.target.value = '';
                    hargaHidden.value = '';
                }
            });

            // Handle paste event
            hargaDisplay.addEventListener('paste', function(e) {
                setTimeout(function() {
                    let value = hargaDisplay.value;
                    let rawValue = unformatNumber(value.replace(/[^0-9,]/g, ''));

                    if (rawValue && !isNaN(rawValue)) {
                        hargaDisplay.value = formatNumber(rawValue);
                        hargaHidden.value = rawValue;
                    }
                }, 10);
            });

            // Prevent non-numeric input
            hargaDisplay.addEventListener('keypress', function(e) {
                // Allow: backspace, delete, tab, escape, enter
                if ([46, 8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                    // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                    (e.keyCode === 65 && e.ctrlKey === true) ||
                    (e.keyCode === 67 && e.ctrlKey === true) ||
                    (e.keyCode === 86 && e.ctrlKey === true) ||
                    (e.keyCode === 88 && e.ctrlKey === true)) {
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode >
                        105)) {
                    e.preventDefault();
                }
            });

            // Image upload handling
            const uploadContainer = document.getElementById('imageUploadContainer');
            const uploadArea = document.getElementById('uploadArea');
            const previewArea = document.getElementById('previewArea');
            const fileInput = document.getElementById('foto');
            const imagePreview = document.getElementById('imagePreview');
            const uploadBtn = document.getElementById('uploadBtn');
            const removeBtn = document.getElementById('removeImage');
            const changeBtn = document.getElementById('changeImage');

            // Click to upload
            uploadContainer.addEventListener('click', function() {
                if (uploadArea.style.display !== 'none') {
                    fileInput.click();
                }
            });

            uploadBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.click();
            });

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
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file tidak valid. Gunakan JPG, JPEG, atau PNG.');
                    return;
                }

                // Validate file size (5MB = 5 * 1024 * 1024 bytes)
                const maxSize = 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar. Maksimal 5MB.');
                    return;
                }

                // Create file reader
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    uploadArea.style.display = 'none';
                    previewArea.style.display = 'block';
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
                uploadArea.style.display = 'block';
                previewArea.style.display = 'none';
            });

            // Change image
            changeBtn.addEventListener('click', function() {
                fileInput.click();
            });

            // Handle form submission loading state
            const form = document.getElementById('createForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const loadingText = document.getElementById('loadingText');

            form.addEventListener('submit', function(e) {
                submitBtn.disabled = true;
                submitText.style.display = 'none';
                loadingText.style.display = 'inline-block';

                setTimeout(function() {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitText.style.display = 'inline-block';
                        loadingText.style.display = 'none';
                    }
                }, 10000);
            });
        });
    </script>
@endpush
