@extends('layouts.master')

@section('title', 'Level - ' . $mataPelajaran->nama_mataPelajaran)

@section('content')
    <div class="container-fluid py-4">
        <h4 class="text-white">Level untuk Mata Pelajaran: {{ $mataPelajaran->nama_mataPelajaran }}</h4>

        <!-- Daftar Level -->
        <div class="row">
            @foreach($levels as $level)
                <div class="col-12">
                    <a href="{{ route('admin.level.show_soal', $level->id_level) }}" class="text-decoration-none">
                        <div class="card mb-1 shadow-sm p-1">
                            <div class="card-body">
                                {{-- <h5 class="text-dark">Level {{ $level->id_level }}</h5> --}}
                                <p class="text-muted">{{ $level->penjelasan_level }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
