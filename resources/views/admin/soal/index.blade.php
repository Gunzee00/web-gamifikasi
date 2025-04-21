@extends('layouts.master')

@section('content')
<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    <main class="main-content position-relative border-radius-lg">
        <div class="container-fluid py-4">
            
            <!-- Daftar Mata Pelajaran -->
            <div class="row">
                @foreach($matapelajaran as $mapel)
                <div class="col-12">
                    <a href="{{ route('admin.matapelajaran.show_levels', $mapel->id_mataPelajaran) }}" class="text-decoration-none">
                        <div class="card mb-4 shadow-sm p-3">
                            <div class="card-body">
                                <h5 class="text-dark">{{ $mapel->nama_mataPelajaran }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
            
            </div>
        </div>
    </main>
</body>
@endsection
