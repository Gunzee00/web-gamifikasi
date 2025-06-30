@extends('layouts.master')

@section('content')
@php
    use Illuminate\Support\Str;
    $tipe = $soal->tipeSoal;
@endphp

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    <main class="main-content position-relative border-radius-lg">
        <div class="container-fluid py-4">
            <div class="card p-4">
                <h4 class="mb-4">Edit Soal - {{ ucfirst($tipe) }}</h4>

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
                    <input type="hidden" name="id_topik" value="{{ $soal->id_topik }}">
                    <input type="hidden" name="id_level" value="{{ $soal->id_level }}">
                    <input type="hidden" name="tipeSoal" value="{{ $tipe }}">

                    {{-- Pertanyaan --}}
                    <div class="mb-3">
                        <label class="form-label">Pertanyaan</label>
                        <textarea name="pertanyaan" class="form-control" rows="3">{{ old('pertanyaan', $soal->pertanyaan) }}</textarea>
                    </div>

                    {{-- Audio Pertanyaan --}}
                    <div class="mb-3">
                        <label class="form-label">Audio Pertanyaan</label>
                        <input type="file" class="form-control" name="audioPertanyaan">
                        @if($soal->audioPertanyaan)
                        <audio controls class="mt-2" style="width: 250px;">
                            <source src="{{ $soal->audioPertanyaan }}">
                        </audio>
                        @endif
                    </div>

                    {{-- Media --}}
                    @if(!in_array($tipe, ['visual2', 'kinestetik1']))
                    <div class="mb-3">
                        <label class="form-label">Media</label>
                        <input type="file" class="form-control" name="media">
                        @if($soal->media)
                        <div class="mt-2">
                            @if(Str::endsWith($soal->media, ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ $soal->media }}" class="img-fluid rounded" style="max-width:300px;">
                            @else
                                <video controls class="w-100" style="max-width:400px;">
                                    <source src="{{ $soal->media }}">
                                </video>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Opsi --}}
                    <div class="mb-3">
                        <label class="form-label">Opsi</label>
                        @php
                            $opsiKeys = in_array($tipe, ['visual2', 'auditori2']) ? ['A', 'B'] : ['A', 'B', 'C', 'D'];
                            $isFileOpsi = in_array($tipe, ['visual2', 'auditori2']);
                        @endphp
                        @foreach($opsiKeys as $opt)
                        <div class="mt-3 border rounded p-3">
                            <label class="form-label">Opsi {{ $opt }}</label>
                            @if($isFileOpsi)
                            <input type="file" name="opsi{{ $opt }}" class="form-control">
                            @else
                            <input type="text" name="opsi{{ $opt }}" class="form-control" value="{{ old('opsi'.$opt, $soal->{'opsi'.$opt}) }}">
                            @endif
                        </div>
                        @endforeach
                    </div>

                    {{-- Pasangan (untuk kinestetik) --}}
                    @if(Str::startsWith($tipe, 'kinestetik'))
                    <div class="mb-3">
                        <label class="form-label">Pasangan (A-D)</label>
                        @foreach(['A','B','C','D'] as $opt)
                        <div class="mt-3 border rounded p-3">
                            <label class="form-label">Pasangan {{ $opt }}</label>
                            <input type="file" name="pasangan{{ $opt }}" class="form-control">
                            @php $file = $soal->{'pasangan'.$opt}; @endphp
                            @if($file)
                            <div class="mt-2">
                                @if(Str::endsWith($file, ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ $file }}" class="img-fluid rounded" style="max-width:150px;">
                                @else
                                    <video controls class="w-100" style="max-width:200px;">
                                        <source src="{{ $file }}">
                                    </video>
                                @endif
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Jawaban Benar --}}
                    <div class="mb-3">
                        <label class="form-label">Jawaban Benar</label>

                        @if(Str::startsWith($tipe, 'kinestetik'))
                            @php
                                $opsi = ['A','B','C','D'];
                                $pasangan = ['A','B','C','D'];
                                $jawabanBenar = old('jawabanBenar', $soal->jawabanBenar);
                                $jawabanArray = json_decode($jawabanBenar, true) ?? [];
                            @endphp
                            @foreach($opsi as $item)
                            <div class="row mb-2">
                                <div class="col-md-2">
                                    <label>Opsi {{ $item }}</label>
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
                        @else
                            @php
                                $opsiBenar = in_array($tipe, ['visual2', 'auditori2']) ? ['A', 'B'] : ['A', 'B', 'C', 'D'];
                            @endphp
                            <select name="jawabanBenar" class="form-control">
                                <option value="">-- Pilih Jawaban Benar --</option>
                                @foreach($opsiBenar as $opt)
                                    <option value="{{ $opt }}" {{ old('jawabanBenar', $soal->jawabanBenar) == $opt ? 'selected' : '' }}>
                                        {{ $opt }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
@endsection
