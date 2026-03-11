@extends('layouts.app')

@section('title', 'Detail Penilaian')
@section('page-title', 'Detail Penilaian')
@section('page-sub', 'Periode: ' . $assessment->period)

@section('content')

<a href="{{ route('admin.assessments.report') }}"
    style="display:inline-flex; align-items:center; gap:6px; margin-bottom:20px; color:var(--mid); text-decoration:none;">
    ← Kembali ke Laporan
</a>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">

    {{-- Kolom Kiri --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Info Karyawan --}}
        <div class="card">
            <div style="padding:24px; text-align:center;">
                <div style="width:80px; height:80px; border-radius:50%; background:linear-gradient(135deg,#4f7cff,#a855f7); display:flex; align-items:center; justify-content:center; font-size:32px; color:white; font-weight:700; margin:0 auto 16px;">
                    {{ strtoupper(substr($assessment->evaluatee->nama, 0, 2)) }}
                </div>
                <h4 style="margin:0 0 4px;">{{ $assessment->evaluatee->nama }}</h4>
                <div style="color:var(--mid); font-size:0.875rem; margin-bottom:16px;">
                    {{ $assessment->evaluatee->jabatan->nama_jabatan ?? '-' }} •
                    {{ $assessment->evaluatee->divisi->nama_divisi ?? '-' }}
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; text-align:left;">
                    <div style="background:#f9fafb; padding:12px; border-radius:8px;">
                        <div style="font-size:0.75rem; color:var(--mid);">Periode</div>
                        <div style="font-weight:600;">{{ $assessment->period }}</div>
                    </div>
                    <div style="background:#f9fafb; padding:12px; border-radius:8px;">
                        <div style="font-size:0.75rem; color:var(--mid);">Tanggal</div>
                        <div style="font-weight:600;">{{ $assessment->assessment_date->format('d M Y') }}</div>
                    </div>
                    <div style="background:#f9fafb; padding:12px; border-radius:8px;">
                        <div style="font-size:0.75rem; color:var(--mid);">Penilai</div>
                        <div style="font-weight:600;">{{ $assessment->evaluator->adminProfile->nama_admin ?? '-' }}</div>
                    </div>
                    <div style="background:#f9fafb; padding:12px; border-radius:8px;">
                        <div style="font-size:0.75rem; color:var(--mid);">Rata-rata</div>
                        <div style="font-weight:700; color:#4f7cff; font-size:1.2rem;">
                            {{ number_format($assessment->average_score, 2) }}/5
                        </div>
                    </div>
                </div>
                <div style="margin-top:16px;">
                    <span class="badge badge-{{ $assessment->score_badge }}" style="font-size:0.9rem; padding:6px 16px;">
                        <span class="badge-dot"></span>{{ $assessment->score_label }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Radar Chart --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-dot"></div>
                    Grafik Spider Web
                </div>
            </div>
            <div style="padding:20px;">
                @if(!empty($radarLabels))
                    <canvas id="radarChart" height="280"></canvas>
                @else
                    <div class="empty-state"><p>Tidak ada data</p></div>
                @endif
            </div>
        </div>
    </div>

    {{-- Kolom Kanan --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Detail Per Kategori --}}
        <div class="card">
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
                        <span style="font-weight:600;">{{ $detail->category->name }}</span>
                        <span style="font-weight:700; color:#4f7cff;">{{ number_format($detail->score, 1) }}/5</span>
                    </div>
                    {{-- Bintang --}}
                    <div style="margin-bottom:6px;">
                        @for($i = 1; $i <= 5; $i++)
                            <span style="font-size:1.2rem; color:{{ $i <= $detail->score ? '#fbbf24' : '#e5e7eb' }}">★</span>
                        @endfor
                    </div>
                    {{-- Progress Bar --}}
                    <div style="background:#f0f0f0; border-radius:99px; height:8px;">
                        @php
                            $pct = ($detail->score / 5) * 100;
                            $color = $detail->score >= 4 ? '#22c55e' : ($detail->score >= 3 ? '#4f7cff' : ($detail->score >= 2 ? '#fbbf24' : '#ef4444'));
                        @endphp
                        <div style="width:{{ $pct }}%; background:{{ $color }}; height:100%; border-radius:99px; transition:width 1s ease;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Catatan Penilai --}}
        @if($assessment->general_notes)
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-dot"></div>
                    Catatan Penilai
                </div>
            </div>
            <div style="padding:20px;">
                <p style="line-height:1.7; color:var(--dark); margin:0;">{{ $assessment->general_notes }}</p>
            </div>
        </div>
        @endif

    </div>
</div>

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
            label: 'Nilai',
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
                ticks: { stepSize: 1, callback: v => v + ' ★', font: { size: 10 } },
                pointLabels: { font: { size: 11, weight: 'bold' } },
            }
        }
    }
});
@endif
</script>
@endpush