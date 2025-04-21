@extends('layouts.master')
    
@section('content')
<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    <main class="main-content position-relative border-radius-lg">
        <div class="container-fluid py-4">
            
            <!-- Tambah/Edit Mata Pelajaran -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4" id="formCard">
                        <div class="card-header pb-0">
                            <h6 id="formTitle">Tambah Mata Pelajaran</h6>
                        </div>
                        <div class="card-body">
                            <form id="formMataPelajaran" action="{{ route('admin.matapelajaran.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="_method" id="methodField" value="POST">
                                <input type="hidden" id="idEdit" value="">

                                <div class="mb-3">
                                    <label for="nama_mataPelajaran" class="form-label">Nama Mata Pelajaran</label>
                                    <input type="text" class="form-control" id="nama_mataPelajaran" name="nama_mataPelajaran" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="reset" class="btn btn-secondary" id="btnResetForm">Batal</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Mata Pelajaran -->
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Daftar Mata Pelajaran</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama Mata Pelajaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($matapelajaran as $mata)
                                    <tr>
                                        <td>{{ $mata->nama_mataPelajaran }}</td>
                                        <td>
                                            <button type="button"
                                                    class="btn btn-warning btn-sm btn-edit-mataPelajaran"
                                                    data-id="{{ $mata->id_mataPelajaran }}"
                                                    data-nama="{{ $mata->nama_mataPelajaran }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.matapelajaran.destroy', $mata->id_mataPelajaran) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- JavaScript untuk Edit -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('formMataPelajaran');
            const methodField = document.getElementById('methodField');
            const namaInput = document.getElementById('nama_mataPelajaran');
            const originalAction = form.action;
            const formTitle = document.getElementById('formTitle');

            document.querySelectorAll('.btn-edit-mataPelajaran').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');

                    form.action = `/admin/matapelajaran/${id}`;
                    methodField.value = 'PUT';
                    namaInput.value = nama;
                    formTitle.textContent = 'Edit Mata Pelajaran';

                    // Scroll ke form
                    document.getElementById('formCard').scrollIntoView({ behavior: 'smooth' });
                });
            });

            document.getElementById('btnResetForm').addEventListener('click', function () {
                form.action = originalAction;
                methodField.value = 'POST';
                namaInput.value = '';
                formTitle.textContent = 'Tambah Mata Pelajaran';
            });
        });
    </script>
</body>
@endsection
