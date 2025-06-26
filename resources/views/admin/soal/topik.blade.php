@extends('layouts.master')

@section('title')

@section('content')
<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    <main class="main-content position-relative border-radius-lg">
        <div class="container-fluid py-4">

            <h4 class="text-white mb-4">{{ $title }}</h4>

            <!-- Daftar Topik -->
            <div class="row">
                @foreach($topiks as $topik)
                    <div class="col-12">
<a href="{{ route('admin.topik.show_soal', ['id' => $topik->id_topik]) }}" style="text-decoration: none;">
                            <div class="card mb-4 shadow-sm p-3 hover-shadow" style="cursor: pointer;">
                                <div class="card-body">
                                    <h5 class="text-dark">{{ $topik->nama_topik }}</h5>
                                    {{-- <p class="text-muted mb-0">ID Topik: {{ $topik->id_topik }}</p> --}}
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
