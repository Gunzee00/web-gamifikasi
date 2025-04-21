@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-header text-white">
            <h4 class="mb-0">Daftar Admin Baru</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.register') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" required value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" required value="{{ old('username') }}">
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" required value="{{ old('tanggal_lahir') }}">
                    @error('tanggal_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                </div>

                <input type="hidden" name="role" id="role" value="admin">

                <div class="mb-3">
                    <label for="gender" class="form-label">Jenis Kelamin</label>
                    <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                        <option value="">-- Pilih --</option>
                        <option value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-person-plus-fill"></i> Daftarkan Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
