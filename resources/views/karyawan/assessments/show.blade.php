@extends('layouts.karyawan')

@section('title', 'Detail Penilaian')

@section('content')

{{-- Header --}}
<div style="margin-bottom:20px;">
    <a href="{{ route('karyawan.assessments.my-report') }}"
        style="color:var(--mid); text-decoration:none; font-size:0.875rem;">
        ← Kembali ke Rapor
    </a>
    <div style="font-size:20px; font-weight:800; letter-spacing:-0.5px; margin-top:8px;">
        📋 Detail Penilaian
    </div>
    <div style="font-size:12px; color:var(--mid); font-family:var(--mono); margin-top:4px;">
        Periode: {{ $assessment->period }}
    </div>
</div>

{{-- Score Card --}}
<div class="card fade-in" style="margin-bottom:16px; text-align:center;">
    <div class="card-body" style="padding:24px;">
        <div style="font-size:12px; color:var(--mid); margin-bottom:8px;">Rata-rata Nilai</div>
        <div style="font-size:48px; font-weight:800; font-family:var(--mono); color:#4f7cff; letter-spacing:-2px; line-height:1;">
            {{ number_format($assessment->average_score, 1) }}
            <span style="font-size:20px; color:var(--mid);">/5</span>
        </div>
        <div style="margin-top:12px;">
            <span class="badge badge-{{ $assessment->score_badge }}" style="font-size:0.9rem; padding:6px 16px;">
                <span class="badge-dot"></span>{{ $assessment->score_label }}
            </span>
        </div>
        {{-- Tanggal dan keterangan di nilai oleh siapa --}}
        <div style="margin-top:16px; display:grid; grid-template-columns:1fr 1fr; gap:12px; text-align:left;">
            <div style="background:#f9fafb; padding:12px; border-radius:8px;">
                <div style="font-size:0.75rem; color:var(--mid);">Tanggal</div>
                <div style="font-weight:600; font-size:0.875rem;">{{ $assessment->assessment_date->format('d M Y') }}</div>
            </div>
            <div style="background:#f9fafb; padding:12px; border-radius:8px;">
                <div style="font-size:0.75rem; color:var(--mid);">Dinilai oleh</div>
                <div style="font-weight:600; font-size:0.875rem;">{{ $assessment->evaluator->adminProfile->nama_admin ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Radar Chart --}}
@if(!empty($radarLabels))
<div class="card fade-in" style="margin-bottom:16px;">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Grafik Performa
        </div>
    </div>
    <div style="padding:20px;">
        <canvas id="radarChart" height="300"></canvas>
    </div>
</div>
@endif

{{-- Detail Per Kategori --}}
<div class="card fade-in" style="margin-bottom:16px;">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Detail Nilai Per Indikator
        </div>
    </div>
    <div style="padding:20px; display:flex; flex-direction:column; gap:16px;">
        @foreach($assessment->details as $detail)
        <div>
            <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                <span style="font-weight:600; font-size:0.9rem;">{{ $detail->category->name }}</span>
                <span style="font-weight:700; color:#4f7cff;">{{ number_format($detail->score, 1) }}/5</span>
            </div>
            {{-- Bintang --}}
            <div style="margin-bottom:6px;">
                @for($i = 1; $i <= 5; $i++)
                    <span style="font-size:1.1rem; color:{{ $i <= $detail->score ? '#fbbf24' : '#e5e7eb' }}">★</span>
                @endfor
            </div>
            {{-- Progress Bar --}}
            @php
                $pct = ($detail->score / 5) * 100;
                $color = $detail->score >= 4 ? '#22c55e' : ($detail->score >= 3 ? '#4f7cff' : ($detail->score >= 2 ? '#fbbf24' : '#ef4444'));
            @endphp
            <div style="background:#f0f0f0; border-radius:99px; height:8px;">
                <div style="width:{{ $pct }}%; background:{{ $color }}; height:100%; border-radius:99px; transition:width 1s ease;"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Catatan Penilai --}}
@if($assessment->general_notes)
<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Catatan dari Penilai
        </div>
    </div>
    <div style="padding:20px;">
        <p style="margin:0; line-height:1.7; font-size:0.9rem;">{{ $assessment->general_notes }}</p>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
@if(!empty($radarLabels))
new Chart(document.getElementById('radarChart').getContext('2d'), {
    type: 'radar',
    data: {
        labels: @json($radarLabels),
        datasets: [{
            label: 'Nilai Saya',
            data: @json($radarScores),
            backgroundColor: 'rgba(79,124,255,0.15)',
            borderColor: '#4f7cff',
            borderWidth: 2,
            pointBackgroundColor: '#4f7cff',
            pointBorderColor: '#fff',
            pointRadius: 5,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            r: {
                min: 0, max: 5,
                ticks: { stepSize:1, callback: v => v+'★', font:{ size:10 } },
                pointLabels: { font:{ size:11, weight:'bold' } },
            }
        }
    }
});
@endif
</script>
@endpush