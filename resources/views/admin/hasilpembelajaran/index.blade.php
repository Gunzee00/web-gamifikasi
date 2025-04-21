@extends('layouts.master')

@section('content')
<div class="container">
    {{-- <h2 class="mt-4 text-center font-weight-bold text-primary"> Hasil Pembelajaran</h2> --}}

    <div class="row mt-4">
        @foreach($users as $index => $user)
        <div class="col-md-6">
            <a href="{{ route('admin.hasilpembelajaran.show', $user->id_user) }}" class="card user-card shadow-sm p-3 mb-3 border-0">
                <div class="d-flex align-items-center">
                    <!-- Avatar User -->
                    <div class="avatar bg-gradient-primary text-white d-flex align-items-center justify-content-center">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <div class="ms-3">
                        <h5 class="mb-1 text-dark">{{ $index + 1 }}. {{ $user->name }}</h5>
                        <small class="text-muted">@ {{ $user->username }}</small>
                    </div>

                    <i class="ni ni-bold-right text-primary ms-auto"></i>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<!-- Custom Style -->
<style>
    .user-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 12px;
        background: #fff;
    }

    .user-card:hover {
        transform: translateY(-5px);
        box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);
        background: linear-gradient(135deg, #F0F8FF, #E6E6FA);
    }

    .avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        font-size: 20px;
        font-weight: bold;
    }
</style>
@endsection
