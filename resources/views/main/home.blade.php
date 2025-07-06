@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">

        <!-- Card Dashboard Item -->
        @php
            $cards = [
                [
                    'title' => 'Total Level',
                    'value' => $totalLevel,
                    'subtitle' => 'Jumlah level pembelajaran',
                    'icon' => 'fas fa-layer-group',
                    'color' => 'success'
                ],
                [
                    'title' => 'Total Topik',
                    'value' => $totalTopik,
                    'subtitle' => 'Jumlah topik yang tersedia',
                    'icon' => 'fas fa-lightbulb',
                    'color' => 'info'
                ],
                [
                    'title' => 'Total Soal',
                    'value' => $totalSoal,
                    'subtitle' => 'Total soal latihan',
                    'icon' => 'fas fa-question-circle',
                    'color' => 'danger'
                ],
            ];
        @endphp

        @foreach($cards as $card)
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="mb-3 d-flex align-items-center justify-content-center mx-auto rounded-circle bg-{{ $card['color'] }} text-white" style="width: 70px; height: 70px; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                            <i class="{{ $card['icon'] }} fa-2x"></i>
                        </div>
                        <h6 class="text-uppercase text-muted">{{ $card['title'] }}</h6>
                        <h3 class="font-weight-bold mb-1">{{ $card['value'] }}</h3>
                        <p class="text-muted small mb-0">{{ $card['subtitle'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
</div>
@endsection
