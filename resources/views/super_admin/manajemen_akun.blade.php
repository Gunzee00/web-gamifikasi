@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-header text-white">
                        <h4 class="mb-0">Manajemen Akun</h4>
        </div>
        <div class="card-body">
            <!-- Tabel yang lebih bersih dan responsif -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Tanggal Lahir</th>
                            <th>Gender</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ ucfirst($user->role) }}</td> <!-- Kapitalisasi role -->
                            <td>{{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d M Y') }}</td> <!-- Format tanggal -->
                            <td>{{ ucfirst($user->gender) }}</td> <!-- Kapitalisasi gender -->
                            <td>
                                <!-- Tombol edit -->
                                <a href="{{ route('super_admin.edit_user', $user->id_user) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                <!-- Tombol hapus -->
                                <form action="{{ route('super_admin.delete_user', $user->id_user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus akun ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
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
@endsection
