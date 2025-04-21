@extends('layouts.master')
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="row">
                        <div class="col-12">
                            <!-- Form -->
                            <div class="card mb-4" id="formCard">
                                <div class="card-header pb-0">
                                    <h6>Tambah Level</h6>
                                </div>
                                <div class="card-body px-3 pt-3 pb-3">
                                    @if(session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    <form id="formLevel" action="{{ route('admin.levels.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="_method" id="methodField" value="POST">
                                        <input type="hidden" id="idLevelEdit" value="">

                                        <div class="mb-3">
                                            <label for="id_mataPelajaran" class="form-label">Mata Pelajaran</label>
                                            <select id="id_mataPelajaran" name="id_mataPelajaran" class="form-control" required>
                                                <option value="">-- Pilih Mata Pelajaran --</option>
                                                @foreach($mataPelajaran as $mp)
                                                    <option value="{{ $mp->id_mataPelajaran }}" {{ old('id_mataPelajaran') == $mp->id_mataPelajaran ? 'selected' : '' }}>
                                                        {{ $mp->nama_mataPelajaran }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="penjelasan_level" class="form-label">Nama Level</label>
                                            <input type="text" id="penjelasan_level" name="penjelasan_level" class="form-control"
                                                   value="{{ old('penjelasan_level') }}"
                                                   required placeholder="Masukkan Nama Level">
                                        </div>

                                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
                                        <button type="reset" id="btnResetForm" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Batal</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Tombol Filter Mata Pelajaran -->
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6>Pilih Mata Pelajaran</h6>
                                </div>
                                <div class="card-body">
                                    <div class="btn-group" role="group">
                                        @foreach($mataPelajaran as $mp)
                                            <a href="{{ route('admin.levels.index', ['id_mataPelajaran' => $mp->id_mataPelajaran]) }}"
                                               class="btn btn-outline-primary {{ ($selectedMataPelajaran == $mp->id_mataPelajaran) ? 'active' : '' }}">
                                                {{ $mp->nama_mataPelajaran }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Daftar Level -->
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6>Daftar Level</h6>
                                </div>
                                <div class="card-body px-3 pt-3 pb-3">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>ID Level</th>
                                                    <th>Mata Pelajaran</th>
                                                    <th>Nama Level</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($levels as $level)
                                                    <tr>
                                                        <td>{{ $level->id_level }}</td>
                                                        <td>{{ $level->mataPelajaran->nama_mataPelajaran ?? 'Tidak Diketahui' }}</td>
                                                        <td>{{ $level->penjelasan_level }}</td>
                                                        <td>
                                                            <button class="btn btn-warning btn-sm btn-edit-level me-2" 
                                                                    data-id="{{ $level->id_level }}"
                                                                    data-id-mata-pelajaran="{{ $level->id_mataPelajaran }}"
                                                                    data-nama="{{ $level->penjelasan_level }}">
                                                                <i class="bi bi-pencil-square"></i> Edit
                                                            </button>
                                                            <button class="btn btn-danger btn-sm btn-hapus-level" 
                                                                    data-id="{{ $level->id_level }}"
                                                                    data-nama="{{ $level->penjelasan_level }}">
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

                        </div> <!-- col-12 -->
                    </div> <!-- row -->
                </div> <!-- card-body -->
            </div> <!-- card -->
        </div> <!-- col-12 -->
    </div> <!-- row -->
</div> <!-- container-fluid -->

<!-- Modal Hapus -->
<div class="modal fade" id="modalHapusLevel" tabindex="-1" aria-labelledby="modalHapusLevelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus level "<span id="namaLevelHapus"></span>"?
            </div>
            <div class="modal-footer">
                <form id="formHapusLevel" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formLevel');
        const methodField = document.getElementById('methodField');
        const idMataPelajaranInput = document.getElementById('id_mataPelajaran');
        const penjelasanLevelInput = document.getElementById('penjelasan_level');

        const originalAction = form.action;

        document.querySelectorAll('.btn-edit-level').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const idMataPelajaran = this.getAttribute('data-id-mata-pelajaran');
                const nama = this.getAttribute('data-nama');

                form.action = `/admin/levels/${id}`;
                methodField.value = 'PUT';
                idMataPelajaranInput.value = idMataPelajaran;
                penjelasanLevelInput.value = nama;

                // Scroll ke atas
                document.getElementById('formCard').scrollIntoView({ behavior: 'smooth' });
            });
        });

        document.getElementById('btnResetForm').addEventListener('click', function () {
            form.action = originalAction;
            methodField.value = 'POST';
            idMataPelajaranInput.value = '';
            penjelasanLevelInput.value = '';
        });
    });
</script>

@endsection
