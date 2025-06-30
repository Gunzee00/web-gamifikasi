@extends('layouts.master')

@section('content')
<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    <main class="main-content position-relative border-radius-lg">
        <div class="container-fluid py-4">
            <div class="card p-4">
                <h4 class="mb-4">Edit Soal - {{ ucfirst($soal->tipeSoal) }}</h4>

                {{-- Tampilkan error jika ada --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

               <form action="{{ route('soal.update', $soal->id_soal) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Hidden id_topik --}}
    <input type="hidden" name="id_topik" value="{{ $soal->id_topik }}">

    {{-- Hidden id_level & tipeSoal --}}
    <input type="hidden" name="id_level" value="{{ $soal->id_level }}">
    <input type="hidden" name="tipeSoal" value="{{ $soal->tipeSoal }}">

    {{-- Pertanyaan --}}
    <div class="mb-3">
        <label for="pertanyaan" class="form-label">Pertanyaan</label>
        <textarea class="form-control" name="pertanyaan" rows="3">{{ old('pertanyaan', $soal->pertanyaan) }}</textarea>
    </div>

    {{-- Audio Pertanyaan --}}
    <div class="mb-3">
        <label for="audioPertanyaan" class="form-label">Audio Pertanyaan (Opsional)</label>
        <input type="file" class="form-control" name="audioPertanyaan">
        @if($soal->audioPertanyaan)
            <audio controls class="mt-2" style="width: 250px;">
                <source src="{{ $soal->audioPertanyaan }}">
            </audio>
        @endif
    </div>

    {{-- Media --}}
    @if(!in_array($soal->tipeSoal, ['kinestetik 1', 'visual 2']))
    <div class="mb-3">
        <label for="media" class="form-label">Media (Opsional)</label>
        <input type="file" class="form-control" name="media">
        @if($soal->media)
            <div class="mt-2">
                @if(preg_match('/\.(jpg|jpeg|png|gif)$/i', $soal->media))
                    <img src="{{ $soal->media }}" class="img-fluid rounded" style="max-width: 300px;">
                @elseif(preg_match('/\.(mp4|webm|ogg)$/i', $soal->media))
                    <video controls class="w-100" style="max-width: 400px;">
                        <source src="{{ $soal->media }}">
                    </video>
                @else
                    <a href="{{ $soal->media }}" target="_blank">Lihat Media</a>
                @endif
            </div>
        @endif
    </div>
    @endif

    {{-- Opsi --}}
    <div class="mb-3">
        <label class="form-label">Opsi</label>
        @foreach(['A','B','C','D'] as $opt)
        <div class="mt-3 border rounded p-3">
            <label class="form-label">Opsi {{ $opt }}</label>
            <input type="text" name="opsi{{ $opt }}" class="form-control" value="{{ old('opsi'.$opt, $soal->{'opsi'.$opt}) }}">
        </div>
        @endforeach
    </div>

    {{-- Pasangan (khusus kinestetik) --}}
    @if(Str::startsWith($soal->tipeSoal, 'kinestetik'))
    <div class="mb-3">
        <label class="form-label">Pasangan (Upload File untuk A-D)</label>
        @foreach(['A','B','C','D'] as $opt)
        <div class="mt-3 border rounded p-3">
            <label class="form-label">File Pasangan {{ $opt }}</label>
            <input type="file" name="pasangan{{ $opt }}" class="form-control">
            @php $file = $soal->{'pasangan'.$opt}; @endphp
            @if($file)
                <div class="mt-2">
                    @if(preg_match('/\.(jpg|jpeg|png|gif)$/i', $file))
                        <img src="{{ $file }}" class="img-fluid rounded" style="max-width: 150px;">
                    @elseif(preg_match('/\.(mp4|webm|ogg)$/i', $file))
                        <video controls class="w-100" style="max-width: 200px;">
                            <source src="{{ $file }}">
                        </video>
                    @else
                        <a href="{{ $file }}" target="_blank">Lihat File Pasangan {{ $opt }}</a>
                    @endif
                </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- Jawaban Benar --}}
    <div class="mb-3">
        <label for="jawabanBenar" class="form-label">Jawaban Benar</label>
        @php
            $opsi = ['A', 'B', 'C', 'D'];
            $pasangan = ['A', 'B', 'C', 'D'];
            $jawabanBenar = old('jawabanBenar', $soal->jawabanBenar);
            $jawabanArray = [];
            if ($jawabanBenar) {
                if (Str::startsWith($jawabanBenar, '{')) {
                    $jawabanArray = json_decode($jawabanBenar, true);
                } elseif (strpos($jawabanBenar, '-') !== false) {
                    foreach (explode(',', $jawabanBenar) as $pair) {
                        [$left, $right] = explode('-', $pair);
                        $jawabanArray[trim($left)] = trim($right);
                    }
                }
            }
        @endphp

        @foreach($opsi as $item)
        <div class="row mb-2">
            <div class="col-md-2">
                <label class="form-label">Opsi {{ $item }}</label>
            </div>
            <div class="col-md-4">
                <select name="jawabanBenarArray[{{ $item }}]" class="form-control">
                    <option value="">-- Pilih Pasangan --</option>
                    @foreach($pasangan as $p)
                        <option value="{{ $p }}" {{ (isset($jawabanArray[$item]) && $jawabanArray[$item] == $p) ? 'selected' : '' }}>
                            {{ $p }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        @endforeach

        <input type="hidden" name="jawabanBenar" id="jawabanBenarFinal">
    </div>

    {{-- Tombol Simpan --}}
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save"></i> Simpan Perubahan
    </button>
</form>

            </div>
        </div>
    </main>
</body>
@endsection
