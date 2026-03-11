@extends('layouts.app')

@section('title', 'Laporan Penilaian')
@section('page-title', 'Laporan Penilaian')
@section('page-sub', 'Periode: ' . $period)

@section('content')

{{-- Filter Periode --}}
<div class="card" style="margin-bottom:20px;">
    <div style="padding:20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <h3 style="margin:0;">📊 Laporan Penilaian Karyawan</h3>
        <form method="GET" style="display:flex; gap:8px;">
            <input type="month" name="period_month" class="form-control"
                value="{{ now()->format('Y-m') }}"
                onchange="this.form.submit()"
                style="width:auto;">
        </form>
    </div>
</div>

{{-- Statistik --}}
@if($stats && $stats->total > 0)
<div class="stats-grid" style="margin-bottom:20px;">
    <div class="stat-card blue">
        <div class="stat-icon">📋</div>
        <div class="stat-value">{{ $stats->total }}</div>
        <div class="stat-label">Total Penilaian</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">⭐</div>
        <div class="stat-value">{{ number_format($stats->overall_avg, 2) }}</div>
        <div class="stat-label">Rata-rata Keseluruhan</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon">🏆</div>
        <div class="stat-value">{{ number_format($stats->highest, 2) }}</div>
        <div class="stat-label">Nilai Tertinggi</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon">📉</div>
        <div class="stat-value">{{ number_format($stats->lowest, 2) }}</div>
        <div class="stat-label">Nilai Terendah</div>
    </div>
</div>
@endif

{{-- Tabel Laporan --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Detail Penilaian
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Karyawan</th>
                    <th>Jabatan</th>
                    <th>Penilai</th>
                    <th>Tanggal</th>
                    <th>Rata-rata</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assessments as $assessment)
                <tr>
                    <td>{{ $assessments->firstItem() + $loop->index }}</td>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background:linear-gradient(135deg,#4f7cff,#a855f7)">
                                {{ strtoupper(substr($assessment->evaluatee->nama, 0, 2)) }}
                            </div>
                            <div>
                                <div class="emp-name">{{ $assessment->evaluatee->nama }}</div>
                                <div class="emp-sub">{{ $assessment->evaluatee->user->nip }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $assessment->evaluatee->jabatan->nama_jabatan ?? '-' }}</td>
                    <td>{{ $assessment->evaluator->adminProfile->nama_admin ?? '-' }}</td>
                    <td style="font-size:0.85rem;">{{ $assessment->assessment_date->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:6px;">
                            <span style="font-size:1.2rem; font-weight:700; color:#4f7cff;">
                                {{ number_format($assessment->average_score, 1) }}
                            </span>
                            <span style="color:var(--mid); font-size:0.8rem;">/5</span>
                        </div>
                        <div style="font-size:0.75rem; line-height:1;">
                            @for($i = 1; $i <= 5; $i++)
                                <span style="color: {{ $i <= round($assessment->average_score) ? '#fbbf24' : '#e5e7eb' }}">★</span>
                            @endfor
                        </div>
                    </td>
                    <td>
                        @php
                            $badgeMap = [
                                'Sangat Baik'   => 'badge-green',
                                'Baik'          => 'badge-blue',
                                'Cukup'         => 'badge-amber',
                                'Kurang'        => 'badge-red',
                                'Sangat Kurang' => 'badge-red',
                            ];
                        @endphp
                        <span class="badge {{ $badgeMap[$assessment->score_label] ?? 'badge-gray' }}">
                            <span class="badge-dot"></span>{{ $assessment->score_label }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.assessments.show', $assessment) }}"
                            style="display:inline-flex; align-items:center; gap:4px; padding:6px 14px; background:#4f7cff; color:white; text-decoration:none; border-radius:8px; font-size:0.8rem; font-weight:600;">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <p>Belum ada data penilaian untuk periode ini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($assessments->hasPages())
    <div style="padding:16px 20px;">
        {{ $assessments->links() }}
    </div>
    @endif
</div>

@endsection