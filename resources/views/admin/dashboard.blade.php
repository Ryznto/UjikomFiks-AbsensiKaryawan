@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-sub', 'Selamat datang, ' . (auth()->user()->adminProfile->nama_admin ?? 'Admin'))

@section('content')

@if(session('success'))
<div class="alert alert-success">✅ {{ session('success') }}</div>
@endif

<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon">👥</div>
        <div class="stat-value">{{ $totalKaryawan }}</div>
        <div class="stat-label">Total Karyawan</div>
        <div class="stat-sub">Aktif terdaftar</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">✅</div>
        <div class="stat-value">{{ $hadirHariIni }}</div>
        <div class="stat-label">Hadir Hari Ini</div>
        <div class="stat-sub">{{ $totalKaryawan > 0 ? round($hadirHariIni / $totalKaryawan * 100) : 0 }}% kehadiran</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon">⏰</div>
        <div class="stat-value">{{ $terlambatHariIni }}</div>
        <div class="stat-label">Terlambat</div>
        <div class="stat-sub">Hari ini</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon">❌</div>
        <div class="stat-value">{{ $alfaHariIni }}</div>
        <div class="stat-label">Tidak Hadir</div>
        <div class="stat-sub">Alfa hari ini</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Log Presensi Hari Ini
        </div>
        <a href="{{ route('admin.presensi.index') }}" class="card-action">Lihat Semua →</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Divisi</th>
                    <th>Shift</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presensiHariIni as $p)
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background: linear-gradient(135deg, #4f7cff, #a855f7)">
                                {{ strtoupper(substr($p->karyawan->nama, 0, 2)) }}
                            </div>
                            <div>
                                <div class="emp-name">{{ $p->karyawan->nama }}</div>
                                <div class="emp-sub">{{ $p->karyawan->user->nip }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $p->karyawan->divisi->nama_divisi }}</td>
                    <td><span class="badge badge-blue">{{ $p->karyawan->shift->nama_shift }}</span></td>
                    <td style="font-family: var(--mono); color: var(--green)">{{ $p->jam_masuk ?? '—' }}</td>
                    <td style="font-family: var(--mono); color: var(--mid)">{{ $p->jam_pulang ?? '—' }}</td>
                    <td>
                        @php
                            $badgeMap = [
                                'tepat_waktu'  => ['class' => 'badge-green', 'label' => 'Tepat Waktu'],
                                'terlambat'    => ['class' => 'badge-amber', 'label' => 'Terlambat'],
                                'pulang_cepat' => ['class' => 'badge-amber', 'label' => 'Pulang Cepat'],
                                'alfa'         => ['class' => 'badge-red',   'label' => 'Alfa'],
                            ];
                            $b = $badgeMap[$p->status_absen] ?? ['class' => 'badge-gray', 'label' => '-'];
                        @endphp
                        <span class="badge {{ $b['class'] }}">
                            <span class="badge-dot"></span>
                            {{ $b['label'] }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <p>Belum ada data presensi hari ini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection