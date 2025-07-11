@extends('layouts.master')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    <main class="main-content position-relative border-radius-lg">
        <div class="container-fluid py-4">

            <!-- Tombol Tambah Soal -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-white">Soal {{ $topik->level->nama_level ?? 'Tanpa Level' }}</h4>
                <a href="{{ route('admin.soal.create_by_topik', ['id_topik' => $topik->id_topik]) }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Soal
                </a>
            </div>

            <!-- Daftar Soal -->
            <div class="row">
                @foreach($soals as $soal)
                    <div class="col-12">
                        <div class="card mb-4 shadow-sm p-3">
                            <div class="card-body">
                                <h5 class="text-dark">
                                    {{ ($soals->currentPage() - 1) * $soals->perPage() + $loop->iteration }}. {{ $soal->pertanyaan }}
                                </h5>
                                <p><strong>Tipe Soal:</strong> {{ ucfirst($soal->tipeSoal) }}</p>

                                {{-- Media --}}
                                @if($soal->media)
                                    <p><strong>Media:</strong></p>
                                    @if(preg_match('/\.(jpg|jpeg|png|gif)$/i', $soal->media))
                                        <img src="{{ $soal->media }}" class="img-fluid rounded" style="max-width: 300px; min-width: 150px; height: auto;">
                                    @elseif(preg_match('/\.(mp4|webm|ogg)$/i', $soal->media))
                                        <video controls class="w-100" style="max-width: 400px;">
                                            <source src="{{ $soal->media }}" type="video/mp4">
                                            Browser tidak mendukung pemutaran video.
                                        </video>
                                    @else
                                        <a href="{{ $soal->media }}" target="_blank">Lihat Media</a>
                                    @endif
                                @endif

                                {{-- Audio --}}
                                @if($soal->audioPertanyaan)
                                    <p><strong>Audio:</strong></p>
                                    <audio controls style="width: 250px;">
                                        <source src="{{ $soal->audioPertanyaan }}" type="audio/mpeg">
                                        Browser tidak mendukung pemutaran audio.
                                    </audio>
                                @endif

                                {{-- Opsi & Pasangan --}}
                                @if(Str::startsWith($soal->tipeSoal, 'kinestetik') && $soal->tipeSoal !== 'kinestetik2')
                                    <div class="row">
                                        <!-- Opsi -->
                                        <div class="col-md-6">
                                            <h6><strong>Opsi:</strong></h6>
                                            <ul class="list-group list-group-flush">
                                                @foreach(['A', 'B', 'C', 'D'] as $huruf)
                                                    @php
                                                        $opsiField = 'opsi' . $huruf;
                                                        $opsiValue = $soal->$opsiField;
                                                    @endphp
                                                    @if(!empty($opsiValue))
                                                        <li class="list-group-item">
                                                            <strong>{{ $huruf }}.</strong>
                                                            @if(preg_match('/\.(jpg|jpeg|png|gif)$/i', $opsiValue))
                                                                <img src="{{ $opsiValue }}" class="img-thumbnail mt-2" style="max-width: 150px; height: auto;">
                                                            @elseif(preg_match('/\.(mp4|webm|ogg)$/i', $opsiValue))
                                                                <video controls class="mt-2" style="max-width: 200px; height: auto;">
                                                                    <source src="{{ $opsiValue }}">
                                                                    Browser tidak mendukung video.
                                                                </video>
                                                            @elseif(preg_match('/\.(mp3|wav|ogg)$/i', $opsiValue))
                                                                <audio controls class="mt-2" style="width: 250px;">
                                                                    <source src="{{ $opsiValue }}">
                                                                    Browser tidak mendukung audio.
                                                                </audio>
                                                            @elseif(Str::startsWith($opsiValue, 'http'))
                                                                <a href="{{ $opsiValue }}" target="_blank">Lihat File</a>
                                                            @else
                                                                {{ $opsiValue }}
                                                            @endif
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>

                                        <!-- Pasangan -->
                                        <div class="col-md-6">
                                            <h6><strong>Pasangan:</strong></h6>
                                            <div class="row">
                                                @foreach(['A', 'B', 'C', 'D'] as $huruf)
                                                    @php
                                                        $pasanganField = 'pasangan' . $huruf;
                                                        $pasanganValue = $soal->$pasanganField;
                                                    @endphp
                                                    @if(!empty($pasanganValue))
                                                        <div class="col-6 mb-2">
                                                            <p class="mb-1"><strong>{{ $huruf }}:</strong></p>
                                                            @if(preg_match('/\.(mp3|wav|ogg)$/i', $pasanganValue))
                                                                <audio controls style="width: 250px;">
                                                                    <source src="{{ $pasanganValue }}">
                                                                    Browser tidak mendukung audio.
                                                                </audio>
                                                            @elseif(preg_match('/\.(jpg|jpeg|png|gif)$/i', $pasanganValue))
                                                                <img src="{{ $pasanganValue }}" class="img-thumbnail" style="max-width: 100%; min-width: 100px; max-height: 150px; object-fit: contain;">
                                                            @else
                                                                {{ $pasanganValue }}
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @elseif($soal->tipeSoal !== 'kinestetik2')
                                    {{-- Soal biasa --}}
                                    <ul class="list-group list-group-flush mt-3">
                                        @foreach(['A', 'B', 'C', 'D'] as $huruf)
                                            @php
                                                $opsiField = 'opsi' . $huruf;
                                                $opsiValue = $soal->$opsiField;
                                            @endphp
                                            @if(!empty($opsiValue))
                                                <li class="list-group-item">
                                                    <strong>{{ $huruf }}.</strong>
                                                    @if(preg_match('/\.(jpg|jpeg|png|gif)$/i', $opsiValue))
                                                        <img src="{{ $opsiValue }}" class="img-thumbnail mt-2" style="max-width: 150px; height: auto;">
                                                    @elseif(preg_match('/\.(mp4|webm|ogg)$/i', $opsiValue))
                                                        <video controls class="mt-2" style="max-width: 200px; height: auto;">
                                                            <source src="{{ $opsiValue }}">
                                                            Browser tidak mendukung video.
                                                        </video>
                                                    @elseif(preg_match('/\.(mp3|wav|ogg)$/i', $opsiValue))
                                                        <audio controls class="mt-2" style="width: 250px;">
                                                            <source src="{{ $opsiValue }}">
                                                            Browser tidak mendukung audio.
                                                        </audio>
                                                    @elseif(Str::startsWith($opsiValue, 'http'))
                                                        <a href="{{ $opsiValue }}" target="_blank">Lihat File</a>
                                                    @else
                                                        {{ $opsiValue }}
                                                    @endif
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif

                                {{-- Jawaban Benar --}}
                                <div class="mt-3">
                                    <strong class="text-success">Jawaban Benar:</strong>
                                    @if(Str::startsWith($soal->tipeSoal, 'kinestetik') && $soal->jawabanBenar)
                                        <ul class="mt-2">
                                            @php
                                                $jawabanPair = json_decode($soal->jawabanBenar, true);
                                            @endphp

                                            @if(is_array($jawabanPair))
                                                {{-- Format JSON valid --}}
                                                @foreach($jawabanPair as $opsi => $pasangan)
                                                    <li>{{ $opsi }} cocok dengan {{ $pasangan }}</li>
                                                @endforeach
                                            @elseif($soal->tipeSoal === 'kinestetik2')
                                                @php
                                                    $pairs = explode(',', $soal->jawabanBenar);
                                                @endphp
                                                @foreach($pairs as $pair)
                                                    <li>{{ $pair }}</li>
                                                @endforeach
                                            @else
                                                <li class="text-danger">Format jawaban tidak valid atau kosong.</li>
                                            @endif
                                        </ul>
                                    @else
                                        <p class="mt-2">{{ $soal->jawabanBenar }}</p>
                                    @endif
                                </div>

                                {{-- Tombol Aksi --}}
                                <div class="mt-3">
                                    <a href="{{ route('soal.edit', ['id' => $soal->id_soal]) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Soal
                                    </a>

                                    <form action="{{ route('soal.destroy', ['id' => $soal->id_soal]) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash-alt"></i> Hapus Soal
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $soals->links() }}
            </div>

        </div>
    </main>
</body>
@endsection
