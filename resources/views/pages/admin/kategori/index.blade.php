@extends('layouts.admin')
@section('title', 'Kategori')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manajemen Kategori</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kategoriModal"
            onclick="openCreateModal()">
            <i class="ai-plus"></i> Tambah Kategori
        </button>
    </div>

    <div class="card border">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="kategoriTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kategoris as $index => $kategori)
                            <tr id="row-{{ $kategori->id }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $kategori->nama_kategori }}</td>
                                <td>{{ $kategori->slug }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button type="button" class="btn btn-sm btn-warning"
                                            onclick="editKategori({{ $kategori->id }})">
                                            <i class="ai-edit"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="deleteKategori({{ $kategori->id }}, '{{ $kategori->nama_kategori }}')">
                                            <i class="ai-trash"></i> Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Kategori -->
    <div class="modal fade" id="kategoriModal" tabindex="-1" aria-labelledby="kategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kategoriModalLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="kategoriForm">
                    <div class="modal-body">
                        <input type="hidden" id="kategoriId" name="kategori_id">
                        <div class="mb-3">
                            <label for="namaKategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="namaKategori" name="nama_kategori" required>
                            <div class="invalid-feedback" id="namaKategoriError"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span id="submitText">Simpan</span>
                            <span id="submitSpinner" class="d-none">
                                <span class="spinner-border spinner-border-sm me-2" role="status"
                                    aria-hidden="true"></span>
                                Menyimpan...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kategori <strong id="deleteKategoriName"></strong>?</p>
                    <input type="hidden" id="deleteKategoriId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn" onclick="confirmDelete()">
                        <span id="deleteText">Hapus</span>
                        <span id="deleteSpinner" class="d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Menghapus...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('addon-script')
    <script>
        let isEdit = false;

        // CSRF Token untuk AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Fungsi untuk membuka modal create
        function openCreateModal() {
            isEdit = false;
            $('#kategoriModalLabel').text('Tambah Kategori');
            $('#kategoriForm')[0].reset();
            $('#kategoriId').val('');
            $('#namaKategori').removeClass('is-invalid');
            $('#namaKategoriError').text('');
        }

        // Fungsi untuk edit kategori
        function editKategori(id) {
            isEdit = true;
            $('#kategoriModalLabel').text('Edit Kategori');

            // Ambil data kategori
            $.get(`/admin/kategori/${id}`, function(response) {
                if (response.success) {
                    $('#kategoriId').val(response.data.id);
                    $('#namaKategori').val(response.data.nama_kategori);
                    $('#kategoriModal').modal('show');
                } else {
                    showAlert('error', response.message);
                }
            }).fail(function() {
                showAlert('error', 'Gagal mengambil data kategori');
            });
        }

        // Fungsi untuk delete kategori
        function deleteKategori(id, nama) {
            $('#deleteKategoriId').val(id);
            $('#deleteKategoriName').text(nama);
            $('#deleteModal').modal('show');
        }

        // Konfirmasi delete
        function confirmDelete() {
            const id = $('#deleteKategoriId').val();
            const deleteBtn = $('#confirmDeleteBtn');
            const deleteText = $('#deleteText');
            const deleteSpinner = $('#deleteSpinner');

            // Show loading
            deleteBtn.prop('disabled', true);
            deleteText.addClass('d-none');
            deleteSpinner.removeClass('d-none');

            $.ajax({
                url: `/admin/kategori/${id}`,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        $('#deleteModal').modal('hide');
                        $(`#row-${id}`).fadeOut(300, function() {
                            $(this).remove();
                            updateRowNumbers();
                        });
                        showAlert('success', response.message);
                    } else {
                        showAlert('error', response.message);
                    }
                },
                error: function() {
                    showAlert('error', 'Gagal menghapus kategori');
                },
                complete: function() {
                    // Hide loading
                    deleteBtn.prop('disabled', false);
                    deleteText.removeClass('d-none');
                    deleteSpinner.addClass('d-none');
                }
            });
        }

        // Submit form
        $('#kategoriForm').on('submit', function(e) {
            e.preventDefault();

            const submitBtn = $('#submitBtn');
            const submitText = $('#submitText');
            const submitSpinner = $('#submitSpinner');
            const namaKategori = $('#namaKategori');
            const kategoriId = $('#kategoriId').val();

            // Reset validation
            namaKategori.removeClass('is-invalid');
            $('#namaKategoriError').text('');

            // Show loading
            submitBtn.prop('disabled', true);
            submitText.addClass('d-none');
            submitSpinner.removeClass('d-none');

            const url = isEdit ? `/admin/kategori/${kategoriId}` : '/admin/kategori';
            const method = isEdit ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: method,
                data: {
                    nama_kategori: namaKategori.val()
                },
                success: function(response) {
                    if (response.success) {
                        $('#kategoriModal').modal('hide');
                        showAlert('success', response.message);

                        if (isEdit) {
                            // Update existing row
                            const row = $(`#row-${kategoriId}`);
                            row.find('td:nth-child(2)').text(response.data.nama_kategori);
                            row.find('td:nth-child(3)').text(response.data.slug);
                        } else {
                            // Add new row
                            location.reload();
                        }
                    } else {
                        showAlert('error', response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        if (errors.nama_kategori) {
                            namaKategori.addClass('is-invalid');
                            $('#namaKategoriError').text(errors.nama_kategori[0]);
                        }
                    } else {
                        showAlert('error', 'Terjadi kesalahan pada server');
                    }
                },
                complete: function() {
                    // Hide loading
                    submitBtn.prop('disabled', false);
                    submitText.removeClass('d-none');
                    submitSpinner.addClass('d-none');
                }
            });
        });

        // Fungsi untuk update nomor urut
        function updateRowNumbers() {
            $('#kategoriTable tbody tr').each(function(index) {
                $(this).find('td:first-child').text(index + 1);
            });
        }

        // Fungsi untuk menampilkan alert
        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

            // Remove existing alerts
            $('.alert').remove();

            // Add new alert
            $('h2.fw-semibold').after(alertHtml);

            // Auto hide after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
        }
    </script>
@endpush
