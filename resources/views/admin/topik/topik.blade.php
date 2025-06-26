@extends('layouts.master')
@section('content')

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="row">
                        <div class="col-12">
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

                                    <form id="formTopik" action="{{ route('admin.topik.store') }}" method="POST">
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

                                        <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Simpan</button>
                                        <button type="reset" id="btnResetForm" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Batal</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Daftar Topik -->
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
                                                            <button class="btn btn-warning btn-sm btn-edit-topik me-2"
                                                                    data-id="{{ $topik->id_topik }}"
                                                                    data-nama="{{ $topik->nama_topik }}"
                                                                    data-idlevel="{{ $topik->id_level }}">
                                                                <i class="bi bi-pencil-square"></i> Edit
                                                            </button>
                                                            <form action="{{ route('admin.topik.destroy', $topik->id_topik) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Hapus</button>
                                                            </form>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formTopik');
        const methodField = document.getElementById('methodField');
        const namaTopikInput = document.getElementById('nama_topik');
        const levelSelect = document.getElementById('id_level');
        const originalAction = form.action;

        document.querySelectorAll('.btn-edit-topik').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const idlevel = this.getAttribute('data-idlevel');

                form.action = `/admin/topik/${id}`;
                methodField.value = 'PUT';
                namaTopikInput.value = nama;
                levelSelect.value = idlevel;

                document.getElementById('formCard').scrollIntoView({ behavior: 'smooth' });
            });
        });

        document.getElementById('btnResetForm').addEventListener('click', function () {
            form.action = originalAction;
            methodField.value = 'POST';
            namaTopikInput.value = '';
            levelSelect.selectedIndex = 0;
        });
    });
</script>

@endsection
