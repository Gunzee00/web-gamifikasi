@extends('layouts.master')

@section('content')
<div class="container text-center mt-5">
    <h2>Ujian Selesai</h2>
    <p>Terima kasih telah menyelesaikan soal untuk Level {{ $id_level }}.</p>
    <a href="{{ route('soal.showLevels', ['id' => $id_level]) }}" class="btn btn-primary">
        Kembali ke Daftar Level
    </a>
</div>
@endsection
