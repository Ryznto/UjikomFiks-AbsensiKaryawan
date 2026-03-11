@extends('layouts.karyawan')

@section('title', 'Detail Penilaian')

@section('content')

{{-- Header --}}
<div style="margin-bottom:20px;">
    <a href="{{ route('karyawan.assessments.my-report') }}"
        style="display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:linear-gradient(135deg,#1e1e2e,#2d2d44); color:white; text-decoration:none; border-radius:12px; font-size:0.85rem; font-weight:600; box-shadow:0 4px 12px rgba(0,0,0,0.3); border:1px solid rgba(255,255,255,0.1); margin-bottom:20px;">
        <span style="font-size:1rem;">←</span>
        <span>Kembali ke Rapor</span>
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
    <div style="padding:24px;">
        <div style="font-size:12px; color:var(--mid); margin-bottom:8px; letter-spacing:1px; text-transform:uppercase;">Rata-rata Nilai</div>
        <div style="font-size:56px; font-weight:800; font-family:var(--mono); color:#4f7cff; letter-spacing:-2px; line-height:1;">
            {{ number_format($assessment->average_score, 1) }}
            <span style="font-size:22px; color:var(--mid);">/5</span>
        </div>
        <div style="margin-top:12px;">
            <span class="badge badge-{{ $assessment->score_badge }}" style="font-size:0.9rem; padding:6px 16px;">
                <span class="badge-dot"></span>{{ $assessment->score_label }}
            </span>
        </div>
        <div style="margin-top:20px; display:grid; grid-template-columns:1fr 1fr; gap:12px; text-align:left;">
            <div style="background:linear-gradient(135deg,#1e1e2e,#2d2d44); padding:14px; border-radius:10px; border:1px solid rgba(255,255,255,0.08);">
                <div style="font-size:0.75rem; color:rgba(255,255,255,0.5); margin-bottom:4px; text-transform:uppercase; letter-spacing:0.5px;">Tanggal</div>
                <div style="font-weight:600; font-size:0.875rem; color:white;">{{ $assessment->assessment_date->format('d M Y') }}</div>
            </div>
            <div style="background:linear-gradient(135deg,#1e1e2e,#2d2d44); padding:14px; border-radius:10px; border:1px solid rgba(255,255,255,0.08);">
                <div style="font-size:0.75rem; color:rgba(255,255,255,0.5); margin-bottom:4px; text-transform:uppercase; letter-spacing:0.5px;">Dinilai oleh</div>
                <div style="font-weight:600; font-size:0.875rem; color:white;">{{ $assessment->evaluator->adminProfile->nama_admin ?? '-' }}</div>
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

{{-- Detail Per Kategori (Accordion) --}}
<div class="card fade-in" style="margin-bottom:16px;">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Detail Nilai Per Indikator
        </div>
    </div>
    <div style="padding:12px 16px; display:flex; flex-direction:column; gap:8px;">
        @foreach($assessment->details->groupBy('statement.category.name') as $categoryName => $details)
        @php $avgCat = round($details->avg('score'), 1); @endphp
        <div style="border:1px solid #e5e7eb; border-radius:12px; overflow:hidden;">
            <div onclick="toggleAccordion({{ $loop->index }})"
                style="display:flex; justify-content:space-between; align-items:center; padding:14px 16px; cursor:pointer; background:#1e1e2e; color:white;">
                <div style="font-weight:700; color:white;">{{ $categoryName }}</div>
                <div style="display:flex; align-items:center; gap:10px;">
                    <div>
                        @for($i = 1; $i <= 5; $i++)
                            <span style="color:{{ $i <= $avgCat ? '#fbbf24' : '#555' }}">★</span>
                        @endfor
                    </div>
                    <span style="font-weight:700; color:#4f7cff; font-size:0.9rem;">{{ $avgCat }}/5</span>
                    <span id="arrow-{{ $loop->index }}" style="color:white; transition:transform 0.3s;">▼</span>
                </div>
            </div>
            <div id="accordion-{{ $loop->index }}" style="display:none; padding:12px 16px; flex-direction:column; gap:12px;">
                @foreach($details as $detail)
                <div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                        <span style="font-size:0.85rem;">{{ $detail->statement->statement ?? '-' }}</span>
                        <span style="font-weight:700; color:#4f7cff; font-size:0.85rem; white-space:nowrap; margin-left:8px;">{{ number_format($detail->score, 1) }}/5</span>
                    </div>
                    <div>
                        @for($i = 1; $i <= 5; $i++)
                            <span style="font-size:0.9rem; color:{{ $i <= $detail->score ? '#fbbf24' : '#e5e7eb' }}">★</span>
                        @endfor
                    </div>
                    @php
                        $pct = ($detail->score / 5) * 100;
                        $color = $detail->score >= 4 ? '#22c55e' : ($detail->score >= 3 ? '#4f7cff' : ($detail->score >= 2 ? '#fbbf24' : '#ef4444'));
                    @endphp
                    <div style="background:#f0f0f0; border-radius:99px; height:6px; margin-top:4px;">
                        <div style="width:{{ $pct }}%; background:{{ $color }}; height:100%; border-radius:99px;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Catatan Penilai --}}
@if($assessment->general_notes)
<div class="card fade-in">
    <div style="background:linear-gradient(135deg,#1e1e2e,#2d2d44); border-radius:12px; padding:16px; margin:16px; border:1px solid rgba(255,255,255,0.08);">
        <div style="font-weight:600; margin-bottom:8px; color:white;">💬 Catatan dari Penilai</div>
        <p style="margin:0; line-height:1.7; font-size:0.9rem; color:rgba(255,255,255,0.7);">{{ $assessment->general_notes }}</p>
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
            backgroundColor: 'rgba(79,124,255,0.25)',
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
                grid: { color: 'rgba(255,255,255,0.5)' },
                angleLines: { color: 'rgba(255,255,255,0.5)' },
                ticks: {
                    stepSize: 1,
                    callback: v => v+'★',
                    font: { size:10 },
                    color: 'white',
                    backdropColor: 'transparent'
                },
                pointLabels: {
                    font: { size:11, weight:'bold' },
                    color: 'white'
                },
            }
        }
    }
});
@endif

function toggleAccordion(index) {
    const content = document.getElementById(`accordion-${index}`);
    const arrow   = document.getElementById(`arrow-${index}`);
    if (content.style.display === 'none' || content.style.display === '') {
        content.style.display = 'flex';
        arrow.style.transform = 'rotate(180deg)';
    } else {
        content.style.display = 'none';
        arrow.style.transform = 'rotate(0deg)';
    }
}
</script>
@endpush