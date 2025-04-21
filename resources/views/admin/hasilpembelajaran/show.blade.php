@extends('layouts.master')

@section('content')
<div class="container">
    <h2 class="mt-4 text-primary text-center">📊 Statistik Hasil Pembelajaran</h2>

    {{-- Card User --}}
    <div class="card shadow-lg p-4 mt-4 bg-light border-0">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="ni ni-single-02 text-primary" style="font-size: 40px;"></i>
            </div>
            <div>
                <h4 class="text-dark m-0">👤 {{ $user->name }}</h4>
                <p class="text-muted">@ {{ $user->username }}</p>
            </div>
        </div>
    </div>

    {{-- Looping Data Statistik --}}
    @foreach($rekapSkor as $index => $rekap)
    <div class="card shadow-lg p-4 mt-4 border-0">
        {{-- <h4 class="text-dark">📚 Mata Pelajaran: <span class="text-primary">{{ $rekap->mataPelajaran->nama_mataPelajaran ?? '-' }}</span></h4> --}}
        {{-- <h5 class="text-muted">🎯 Level: {{ $rekap->level->nama_level ?? '-' }}</h5> --}}

        {{-- Diagram Lingkaran --}}
        <div class="text-center">
            <canvas id="chart-{{ $index }}" width="250" height="250"></canvas>
        </div>

        {{-- Tipe Dominan --}}
        <div class="mt-4 text-center">
            <h5 class="fw-bold">🧠 Tipe Dominan: 
                @if($rekap->tipe_dominan === 'visual')
                    <span class="badge bg-primary fs-6 px-3 py-2">Visual 🎨</span>
                @elseif($rekap->tipe_dominan === 'auditory')
                    <span class="badge bg-success fs-6 px-3 py-2">Auditory 🎧</span>
                @elseif($rekap->tipe_dominan === 'kinestetik')
                    <span class="badge bg-danger fs-6 px-3 py-2">Kinestetik 🏃</span>
                @else
                    <span class="badge bg-secondary fs-6 px-3 py-2">Tidak Ada</span>
                @endif
            </h5>
        </div>
    </div>
    @endforeach
</div>

{{-- Tambahkan Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        @foreach($rekapSkor as $index => $rekap)
            var ctx = document.getElementById('chart-{{ $index }}').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Visual 🎨', 'Auditory 🎧', 'Kinestetik 🏃'],
                    datasets: [{
                        data: [{{ $rekap->total_visual }}, {{ $rekap->total_auditory }}, {{ $rekap->total_kinestetik }}],
                        backgroundColor: ['#007bff', '#28a745', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        @endforeach
    });
</script>
@endsection
