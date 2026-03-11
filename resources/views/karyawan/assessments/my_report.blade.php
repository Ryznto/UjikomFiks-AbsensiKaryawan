@extends('layouts.karyawan')

@section('title', 'Rapor Penilaian Saya')

@section('content')

{{-- Header --}}
<div style="margin-bottom:20px;">
    <div style="font-size:20px; font-weight:800; letter-spacing:-0.5px;">
        📊 Rapor Penilaian Saya
    </div>
    <div style="font-size:12px; color:var(--mid); font-family:var(--mono); margin-top:4px;">
        Periode: {{ $period }}
    </div>
</div>

{{-- Filter Periode --}}
<form method="GET" style="margin-bottom:16px;">
    <input type="month" name="period_month" class="form-control"
        value="{{ now()->format('Y-m') }}"
        onchange="this.form.submit()">
</form>

{{-- Score Card --}}
@if($currentAssessment)
<div class="card fade-in" style="margin-bottom:16px; text-align:center;">
    <div class="card-body" style="padding:24px;">
        <div style="font-size:12px; color:var(--mid); margin-bottom:8px;">Rata-rata Nilai – {{ $period }}</div>
        <div style="font-size:48px; font-weight:800; font-family:var(--mono); color:#4f7cff; letter-spacing:-2px; line-height:1;">
            {{ number_format($currentAssessment->average_score, 1) }}
            <span style="font-size:20px; color:var(--mid);">/5</span>
        </div>
        <div style="margin-top:12px;">
            <span class="badge badge-{{ $currentAssessment->score_badge }}" style="font-size:0.9rem; padding:6px 16px;">
                <span class="badge-dot"></span>{{ $currentAssessment->score_label }}
            </span>
        </div>
        <div style="margin-top:12px; font-size:0.8rem; color:var(--mid);">
            Dinilai oleh: <strong>{{ $currentAssessment->evaluator->adminProfile->nama_admin ?? '-' }}</strong>
        </div>
    </div>
</div>
@else
<div class="card fade-in" style="margin-bottom:16px;">
    <div class="card-body" style="text-align:center; padding:32px;">
        <div style="font-size:32px; margin-bottom:8px;">⏳</div>
        <div style="font-weight:600; margin-bottom:4px;">Belum ada penilaian</div>
        <div style="font-size:0.8rem; color:var(--mid);">Tunggu admin memberikan evaluasi untuk periode ini</div>
    </div>
</div>
@endif

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

{{-- Detail Nilai Per Kategori --}}
@if($currentAssessment && $currentAssessment->details->count() > 0)
<div class="card fade-in" style="margin-bottom:16px;">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Detail Nilai
        </div>
    </div>
    <div style="padding:20px; display:flex; flex-direction:column; gap:16px;">
        @foreach($currentAssessment->details as $detail)
        <div>
            <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
               <span style="font-weight:600; font-size:0.9rem;">{{ $detail->statement->statement ?? '-' }}</span>
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

        {{-- Catatan --}}
        @if($currentAssessment->general_notes)
        <div style="background:#eff0f1; border-radius:12px; padding:16px; margin-top:8px;">
            <div style="font-weight:600; margin-bottom:8px; color:#131328;">💬 Catatan dari Penilai</div>
            <p style="margin:0; line-height:1.7; font-size:0.9rem; color:#1a1a2e;">{{ $currentAssessment->general_notes }}</p>
        </div>
        @endif
    </div>
</div>
@endif

{{-- Riwayat Penilaian --}}
<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Riwayat Penilaian
        </div>
        <span style="font-size:0.8rem; color:var(--mid);">{{ $history->count() }} periode</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Periode</th>
                    <th>Tanggal</th>
                    <th>Rata-rata</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($history as $item)
                <tr>
                    <td style="font-weight:600;">{{ $item->period }}</td>
                    <td style="font-family:var(--mono); font-size:0.8rem;">
                        {{ $item->assessment_date->format('d M Y') }}
                    </td>
                    <td>
                        <span style="font-weight:700; color:#4f7cff; font-family:var(--mono);">
                            {{ number_format($item->average_score, 1) }}
                        </span>
                        <span style="color:var(--mid); font-size:0.8rem;">/5</span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $item->score_badge }}">
                            <span class="badge-dot"></span>{{ $item->score_label }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('karyawan.assessments.show', $item) }}"
                            style="color:#4f7cff; text-decoration:none; font-size:0.85rem;">
                             Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <p>Belum ada riwayat penilaian</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
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