@extends('layouts.master')
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="row">
                        <div class="col-12">

                            {{-- Form Tambah/Edit --}}
                            <div class="card mb-4" id="formCard">
                                <div class="card-header pb-0">
                                    <h6>Tambah Topik</h6>
                                </div>
                                <div class="card-body px-3 pt-3 pb-3">
                                    @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    <form id="formTopik" action="{{ route('admin.topik.store') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="_method" id="methodField" value="POST">
                                        <input type="hidden" id="idTopikEdit" value="">

                                        <div class="mb-3">
                                            <label for="nama_topik" class="form-label">Nama Topik</label>
                                            <input type="text" id="nama_topik" name="nama_topik" class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="id_level" class="form-label">Pilih Level</label>
                                            <select name="id_level" id="id_level" class="form-control" required>
                                                @foreach ($levels as $level)
                                                    <option value="{{ $level->id_level }}">{{ $level->nama_level }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="icon" class="form-label">Icon Topik (opsional)</label>
                                            <input type="file" id="icon" name="icon" class="form-control" accept="image/*">
                                        </div>

                                        <div class="mb-3" id="iconPreviewContainer" style="display: none;">
                                            <label class="form-label">Icon Saat Ini:</label><br>
                                            <img id="iconPreview" src="" alt="Icon Preview" style="max-width: 100px;">
                                        </div>

                                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
                                        <button type="reset" id="btnResetForm" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Batal</button>
                                    </form>
                                </div>
                            </div>

                            {{-- Daftar Topik --}}
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6>Daftar Topik</h6>
                                </div>
                                <div class="card-body px-3 pt-3 pb-3">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>ID Topik</th>
                                                    <th>Nama Topik</th>
                                                    <th>Level</th>
                                                    <th>Icon</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($topiks as $topik)
                                                    <tr>
                                                        <td>{{ $topik->id_topik }}</td>
                                                        <td>{{ $topik->nama_topik }}</td>
                                                        <td>{{ $topik->level->nama_level ?? '-' }}</td>
                                                        <td>
                                                            @if($topik->icon)
                                                                <img src="{{ $topik->icon }}" alt="Icon" style="max-width: 50px; cursor: pointer;" class="preview-icon" data-bs-toggle="modal" data-bs-target="#modalPreview" data-src="{{ $topik->icon }}">
                                                            @else
                                                                <span class="text-muted">Tidak ada</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-warning btn-sm btn-edit-topik me-2"
                                                                    data-id="{{ $topik->id_topik }}"
                                                                    data-nama="{{ $topik->nama_topik }}"
                                                                    data-idlevel="{{ $topik->id_level }}"
                                                                    data-icon="{{ $topik->icon }}">
                                                                <i class="bi bi-pencil-square"></i> Edit
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm btn-delete-topik" data-id="{{ $topik->id_topik }}" data-url="{{ route('admin.topik.destroy', $topik->id_topik) }}" data-bs-toggle="modal" data-bs-target="#modalConfirmDelete">
                                                                <i class="bi bi-trash"></i> Hapus
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Preview Gambar -->
                            <div class="modal fade" id="modalPreview" tabindex="-1" aria-labelledby="previewLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <div class="modal-body text-center">
                                    <img id="modalImage" src="" class="img-fluid rounded" alt="Preview Icon">
                                  </div>
                                </div>
                              </div>
                            </div>

                            <!-- Modal Konfirmasi Hapus -->
                            <div class="modal fade" id="modalConfirmDelete" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                  <div class="modal-header  text-white">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                  </div>
                                  <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus topik ini?
                                  </div>
                                  <div class="modal-footer">
                                    <form id="deleteForm" method="POST">
                                      @csrf
                                      @method('DELETE')
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                      <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>

                        </div> <!-- col-12 -->
                    </div> <!-- row -->
                </div> <!-- card-body -->
            </div> <!-- card -->
        </div> <!-- col-12 -->
    </div> <!-- row -->
</div> <!-- container-fluid -->

{{-- Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formTopik');
        const methodField = document.getElementById('methodField');
        const namaTopikInput = document.getElementById('nama_topik');
        const levelSelect = document.getElementById('id_level');
        const iconPreviewContainer = document.getElementById('iconPreviewContainer');
        const iconPreview = document.getElementById('iconPreview');
        const originalAction = form.action;

        // Edit topik
        document.querySelectorAll('.btn-edit-topik').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const idlevel = this.getAttribute('data-idlevel');
                const icon = this.getAttribute('data-icon');

                form.action = `/admin/topik/${id}`;
                methodField.value = 'PUT';
                namaTopikInput.value = nama;
                levelSelect.value = idlevel;

                if (icon) {
                    iconPreview.src = icon;
                    iconPreviewContainer.style.display = 'block';
                } else {
                    iconPreviewContainer.style.display = 'none';
                }

                document.getElementById('formCard').scrollIntoView({ behavior: 'smooth' });
            });
        });

        // Reset form
        document.getElementById('btnResetForm').addEventListener('click', function () {
            form.action = originalAction;
            methodField.value = 'POST';
            namaTopikInput.value = '';
            levelSelect.selectedIndex = 0;
            iconPreviewContainer.style.display = 'none';
        });

        // Preview gambar
        document.querySelectorAll('.preview-icon').forEach(img => {
            img.addEventListener('click', function () {
                const src = this.getAttribute('data-src');
                document.getElementById('modalImage').src = src;
            });
        });

        // Konfirmasi hapus
        document.querySelectorAll('.btn-delete-topik').forEach(btn => {
            btn.addEventListener('click', function () {
                const url = this.getAttribute('data-url');
                document.getElementById('deleteForm').action = url;
            });
        });
    });
</script>

@endsection
