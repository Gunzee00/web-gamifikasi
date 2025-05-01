@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <div class="row">
         <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-body text-center">
                     <div class="icon icon-shape icon-md bg-primary text-white rounded-circle mb-3">
                        <i class="fas fa-users"></i>
                    </div>
                     <h6 class="card-title text-uppercase mb-2">Total User</h6>
                     {{-- <p class="h4 font-weight-bold">{{ $totalUsers }}</p> --}}
                     <p class="text-muted">Total user yang terdaftar </p>
                </div>
            </div>
        </div>

         <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded">
                <div class="card-body text-center">
                    <!-- Ikon dengan ukuran lebih kecil -->
                    <div class="icon icon-shape icon-md bg-warning text-white rounded-circle mb-3">
                        <i class="fas fa-book"></i>
                    </div>
                     <h6 class="card-title text-uppercase mb-2">Total Mata Pelajaran</h6>
                     {{-- <p class="h4 font-weight-bold">{{ $totalMatapelajaran }}</p> --}}
                     <p class="text-muted">Total mata pelajaran yang tersedia</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
