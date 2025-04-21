@extends('layouts.master')

@section('content')
<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    <main class="main-content position-relative border-radius-lg">
        <div class="container-fluid py-4">
            <div class="card p-4">
                <form action="{{ route('soal.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id_level" value="{{ $level->id_level }}">

                    <div class="mb-3">
                        <label for="pertanyaan" class="form-label">Pertanyaan</label>
                        <input type="text" name="pertanyaan" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="tipeSoal" class="form-label">Tipe Soal</label>
                        <select name="tipeSoal" id="tipeSoal" class="form-control" required onchange="toggleFields()">
                            <option value="visual1">Visual1</option>
                            <option value="visual2">Visual2</option>
                            <option value="auditori1">Auditori1</option>
                            <option value="auditori2">Auditori2</option>
                            <option value="kinestetik1">Kinestetik1</option>
                            <option value="kinestetik2">Kinestetik2</option>
                        </select>
                    </div>

                    {{-- OPSI --}}
                    <div class="mb-3">
                        <label class="form-label">Opsi</label>
                        @foreach(['A','B','C','D'] as $opt)
                        <div class="mt-3 border rounded p-3">
                            <label class="form-label">Opsi {{ $opt }}</label>
                            <select class="form-select mb-2" onchange="toggleInput(this, 'opsi{{ $opt }}')">
                                <option value="text">Teks</option>
                                <option value="file">File</option>
                            </select>
                            <input type="text" name="opsi{{ $opt }}" id="opsi{{ $opt }}_text" class="form-control opsi-input">
                            <input type="file" name="opsi{{ $opt }}_file" id="opsi{{ $opt }}_file" class="form-control opsi-input d-none">
                        </div>
                        @endforeach
                    </div>

                    {{-- PASANGAN --}}
                    <div id="pasanganFields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Pasangan</label>
                            @foreach(['A','B','C','D'] as $opt)
                            <div class="mt-3 border rounded p-3">
                                <label class="form-label">Pasangan {{ $opt }}</label>
                                <select class="form-select mb-2" onchange="toggleInput(this, 'pasangan{{ $opt }}')">
                                    <option value="text">Teks</option>
                                    <option value="file">File</option>
                                </select>
                                <input type="text" name="pasangan{{ $opt }}" id="pasangan{{ $opt }}_text" class="form-control pasangan-input">
                                <input type="file" name="pasangan{{ $opt }}_file" id="pasangan{{ $opt }}_file" class="form-control pasangan-input d-none">
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Jawaban untuk visual & auditori --}}
                    <div class="mb-3" id="jawabanSingleField">
                        <label for="jawabanBenar" class="form-label">Jawaban Benar</label>
                        <select name="jawabanBenar" class="form-control">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>

                    {{-- Jawaban kinestetik1 --}}
                    <div class="mb-3" id="jawabanBenarKinestetik1" style="display: none;">
                        <label class="form-label">Jawaban Benar (Pasangan Opsi dan Pasangan)</label>
                        <div class="row">
                            @foreach(['A','B','C','D'] as $opt)
                            <div class="col-md-6 mb-2">
                                <label>Pasangan untuk Opsi {{ $opt }}</label>
                                <select class="form-control" name="jawaban_pair[{{ $opt }}]">
                                    <option value="">-- Tidak ada --</option>
                                    <option value="A">Pasangan A</option>
                                    <option value="B">Pasangan B</option>
                                    <option value="C">Pasangan C</option>
                                    <option value="D">Pasangan D</option>
                                </select>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Jawaban kinestetik2 --}}
                    <div class="mb-3" id="jawabanBenarKinestetik2" style="display: none;">
                        <label for="jawabanBenarText" class="form-label">Jawaban Benar</label>
                        <input type="text" name="jawabanBenarText" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="media" class="form-label">Media (Opsional)</label>
                        <input type="file" name="media" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="audioPertanyaan" class="form-label">Audio (Opsional)</label>
                        <input type="file" name="audioPertanyaan" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Soal
                    </button>
                    <a href="{{ route('soal.list', ['id' => $level->id_level]) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </form>
            </div>
        </div>
    </main>

    <script>
        function toggleInput(selectEl, baseId) {
            const type = selectEl.value;
            const textInput = document.getElementById(`${baseId}_text`);
            const fileInput = document.getElementById(`${baseId}_file`);
            if (type === 'text') {
                textInput.classList.remove('d-none');
                fileInput.classList.add('d-none');
                textInput.name = baseId;
                fileInput.name = '';
            } else {
                textInput.classList.add('d-none');
                fileInput.classList.remove('d-none');
                textInput.name = '';
                fileInput.name = baseId;
            }
        }

        function toggleFields() {
            const tipe = document.getElementById('tipeSoal').value;
            const pasanganFields = document.getElementById('pasanganFields');
            const jawabanSingle = document.getElementById('jawabanSingleField');
            const jawabanKinestetik1 = document.getElementById('jawabanBenarKinestetik1');
            const jawabanKinestetik2 = document.getElementById('jawabanBenarKinestetik2');
            const mediaFormGroup = document.querySelector('input[name="media"]').closest('.mb-3');
            const audioFormGroup = document.querySelector('input[name="audioPertanyaan"]').closest('.mb-3');

            jawabanSingle.style.display = 'none';
            jawabanKinestetik1.style.display = 'none';
            jawabanKinestetik2.style.display = 'none';
            pasanganFields.style.display = 'none';
            mediaFormGroup.style.display = 'block';
            audioFormGroup.style.display = 'block';

            ['A','B','C','D'].forEach(opt => {
                const el = document.querySelector(`[name="opsi${opt}"]`)?.closest('.border');
                if (el) el.style.display = 'block';
            });

            if (tipe.startsWith('kinestetik')) {
                pasanganFields.style.display = 'block';

                if (tipe === 'kinestetik1') {
                    jawabanKinestetik1.style.display = 'block';
                    mediaFormGroup.style.display = 'none';
                } else if (tipe === 'kinestetik2') {
                    jawabanKinestetik2.style.display = 'block';
                }

            } else if (tipe === 'visual2' || tipe === 'auditori2') {
                jawabanSingle.style.display = 'block';
                mediaFormGroup.style.display = 'none';
                ['C','D'].forEach(opt => {
                    const el = document.querySelector(`[name="opsi${opt}"]`)?.closest('.border');
                    if (el) el.style.display = 'none';
                });
            } else {
                jawabanSingle.style.display = 'block';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            toggleFields();
        });
    </script>
</body>
@endsection
