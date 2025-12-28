@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h2 class="mb-0">Tambah Outlet Baru</h2>
        <p class="mb-0 text-secondary">Isi informasi outlet baru</p>
    </div>

    <form id="outletForm" method="POST" action="{{ route('user.outlet.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <div class="col-md-8">
                <div class="card border">
                    <div class="card-header bg-white py-3">
                        <p class="mb-0 fw-semibold">Informasi Outlet</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}"
                                    class="form-control @error('alamat') is-invalid @enderror" required>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="lokasi_googlemap" class="form-label">Link Google Maps <span
                                        class="text-danger">*</span></label>
                                <input type="url" id="lokasi_googlemap"
                                    class="form-control @error('lokasi_googlemap') is-invalid @enderror"
                                    name="lokasi_googlemap" value="{{ old('lokasi_googlemap') }}" required>
                                <small class="text-muted">Contoh: https://maps.google.com/...</small>
                                @error('lokasi_googlemap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Cara Order <span class="text-danger">*</span></label>

                                {{-- hidden textarea untuk submit ke server --}}
                                <textarea id="cara_order" name="cara_order" hidden>{{ old('cara_order', $outlet->cara_order ?? '') }}</textarea>

                                {{-- editor --}}
                                <div id="cara_order_editor" class="@error('cara_order') quill-invalid @enderror"
                                    style="min-height: 20px; height: 200px"></div>

                                <small class="text-muted">Jelaskan bagaimana cara pelanggan memesan produk</small>

                                @error('cara_order')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border mb-3">
                    <div class="card-header bg-white py-3">
                        <p class="mb-0 fw-semibold">Foto Lokasi Tampak Depan</p>
                    </div>
                    <div class="card-body">
                        <!-- Image Upload Area -->
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
                                    <p class="upload-title">This image will appear on the Explore Page as a model cover</p>
                                    <p class="upload-subtitle">(file must be less than 1MB)</p>
                                </div>
                                <div class="d-flex justify-content-center mt-3">
                                    <button type="button" class="btn btn-light border upload-btn" id="uploadBtn">
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

                        <input type="file" id="foto_lokasi_tampak_depan"
                            class="form-control d-none @error('foto_lokasi_tampak_depan') is-invalid @enderror"
                            name="foto_lokasi_tampak_depan" accept="image/*">

                        @error('foto_lokasi_tampak_depan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">Format: JPG, PNG, GIF. Maksimal 2MB</small>
                        @error('foto_lokasi_tampak_depan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card border">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row gap-2">
                            <a href="{{ route('user.outlet.index') }}"
                                class="w-100 btn btn-light border d-none d-md-block">
                                <i class="ai-arrow-back"></i> Kembali
                            </a>
                            <button type="submit" class="w-100 btn btn-primary" id="submitButton">
                                <span id="buttonText">
                                    <i class="ai-check"></i> Simpan Outlet
                                </span>
                                <span id="loadingSpinner" class="d-none">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"
                                        aria-hidden="true"></span>
                                    Memproses...
                                </span>
                            </button>
                            <a href="{{ route('user.outlet.index') }}"
                                class="w-100 btn btn-light border d-block d-md-none">
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
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <style>
        /* tampilkan border merah saat error */
        .quill-invalid .ql-container {
            border-color: #dc3545 !important;
        }

        .ql-toolbar {
            border-radius: 12px 12px 0 0;
        }

        .ql-container {
            border-radius: 0 0 12px 12px;
            font-size: 16px;
        }

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
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        // toolbar minimal
        const toolbar = [
            ['bold', 'italic', 'underline', 'link'],
            [{
                list: 'ordered'
            }, {
                list: 'bullet'
            }]
        ];

        const quill = new Quill('#cara_order_editor', {
            theme: 'snow',
            modules: {
                toolbar
            },
            placeholder: 'Tulis langkah pemesanan (contoh: pilih produk → isi form → pembayaran → konfirmasi)...'
        });

        // prefill dari old() atau nilai lama (sudah ditaruh di <textarea>)
        const hiddenField = document.getElementById('cara_order');
        if (hiddenField.value) {
            quill.clipboard.dangerouslyPasteHTML(hiddenField.value);
        }

        // sinkronkan ke <textarea> sebelum submit
        document.getElementById('outletForm').addEventListener('submit', function() {
            hiddenField.value = quill.root.innerHTML;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const uploadContainer = document.getElementById('imageUploadContainer');
            const uploadArea = document.getElementById('uploadArea');
            const previewArea = document.getElementById('previewArea');
            const fileInput = document.getElementById('foto_lokasi_tampak_depan');
            const imagePreview = document.getElementById('imagePreview');
            const uploadBtn = document.getElementById('uploadBtn');
            const removeBtn = document.getElementById('removeImage');
            const changeBtn = document.getElementById('changeImage');

            // Form submission handling
            const outletForm = document.getElementById('outletForm');
            const submitButton = document.getElementById('submitButton');
            const buttonText = document.getElementById('buttonText');
            const loadingSpinner = document.getElementById('loadingSpinner');

            // Handle form submission
            outletForm.addEventListener('submit', function(e) {
                // Disable the button
                submitButton.disabled = true;

                // Hide button text and show loading spinner
                buttonText.classList.add('d-none');
                loadingSpinner.classList.remove('d-none');

                // Optional: Re-enable button after some time if there's an error
                // This prevents the button from being permanently disabled if validation fails
                setTimeout(function() {
                    if (submitButton.disabled) {
                        submitButton.disabled = false;
                        buttonText.classList.remove('d-none');
                        loadingSpinner.classList.add('d-none');
                    }
                }, 15000); // Re-enable after 15 seconds as fallback
            });

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
                if (!file.type.startsWith('image/')) {
                    alert('Please select an image file.');
                    return;
                }

                // Validate file size (5MB = 5242880 bytes)
                if (file.size > 5242880) {
                    alert('File size must be less than 5MB.');
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
        });
    </script>
@endpush
