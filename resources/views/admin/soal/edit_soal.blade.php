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

                    {{-- Pasangan (khusus kinestetik, input file) --}}
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
                        @if(Str::startsWith($soal->tipeSoal, 'kinestetik'))
                            <textarea name="jawabanBenar" class="form-control" rows="2">{{ old('jawabanBenar', $soal->jawabanBenar) }}</textarea>
                            <small class="form-text text-muted">
                                Format JSON (misal: {"A":"C","B":"D"}) atau pasangan dipisah koma (misal: A-C,B-D)
                            </small>
                        @else
                            <select name="jawabanBenar" class="form-control">
                                <option value="A" {{ old('jawabanBenar', $soal->jawabanBenar) == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('jawabanBenar', $soal->jawabanBenar) == 'B' ? 'selected' : '' }}>B</option>
                                <option value="C" {{ old('jawabanBenar', $soal->jawabanBenar) == 'C' ? 'selected' : '' }}>C</option>
                                <option value="D" {{ old('jawabanBenar', $soal->jawabanBenar) == 'D' ? 'selected' : '' }}>D</option>
                            </select>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>

                </form>
            </div>
        </div>
    </main>
</body>
@endsection
