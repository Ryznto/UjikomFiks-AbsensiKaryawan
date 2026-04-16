@extends('layouts.app')

@section('title', 'Leaderboard Integritas')
@section('page-title', 'Leaderboard Integritas')
@section('page-sub', 'Peringkat poin integritas karyawan')

@section('content')

<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="stat-card blue">
        <div class="stat-icon">🏆</div>
        <div class="stat-value">{{ $topPoints->first()->point_balance ?? 0 }}</div>
        <div class="stat-label">Poin Tertinggi</div>
        <div class="stat-sub">{{ $topPoints->first()->karyawan->nama ?? '-' }}</div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon">📊</div>
        <div class="stat-value">{{ round($averagePoint) }}</div>
        <div class="stat-label">Rata-rata Poin</div>
        <div class="stat-sub">Semua karyawan</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon">⚠️</div>
        <div class="stat-value">{{ $bottomPoints->first()->point_balance ?? 0 }}</div>
        <div class="stat-label">Poin Terendah</div>
        <div class="stat-sub">{{ $bottomPoints->first()->karyawan->nama ?? '-' }}</div>
    </div>
</div>

<div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    {{-- Top Points --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-title-dot" style="background: #10b981;"></div>
                🏆 Top 5 Point Tertinggi
            </div>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Karyawan</th>
                        <th>Divisi</th>
                        <th>Poin</th>
                        <th>Level</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topPoints as $index => $user)
                    <tr>
                        <td>
                            @if($index == 0) 🥇
                            @elseif($index == 1) 🥈
                            @elseif($index == 2) 🥉
                            @else {{ $index + 1 }}
                            @endif
                        </td>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar" style="background: linear-gradient(135deg, #4f7cff, #a855f7)">
                                    {{ strtoupper(substr($user->karyawan->nama ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="emp-name">{{ $user->karyawan->nama ?? '-' }}</div>
                                    <div class="emp-sub">{{ $user->nip ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->karyawan->divisi->nama_divisi ?? '-' }}</td>
                        <td style="font-weight: 700; color: #10b981;">{{ number_format($user->point_balance) }}</td>
                        <td>
                            @php
                                $level = $user->point_balance >= 200 ? 'Elite' : ($user->point_balance >= 100 ? 'Andal' : ($user->point_balance >= 50 ? 'Disiplin' : 'Pemula'));
                            @endphp
                            <span class="badge badge-green">{{ $level }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data karyawan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bottom Points --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-title-dot" style="background: #ef4444;"></div>
                ⚠️ Bottom 5 Point Terendah
            </div>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Karyawan</th>
                        <th>Divisi</th>
                        <th>Poin</th>
                        <th>Level</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bottomPoints as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar" style="background: linear-gradient(135deg, #ef4444, #a855f7)">
                                    {{ strtoupper(substr($user->karyawan->nama ?? 'U', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="emp-name">{{ $user->karyawan->nama ?? '-' }}</div>
                                    <div class="emp-sub">{{ $user->nip ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->karyawan->divisi->nama_divisi ?? '-' }}</td>
                        <td style="font-weight: 700; color: #ef4444;">{{ number_format($user->point_balance) }}</td>
                        <td>
                            @php
                                $level = $user->point_balance >= 200 ? 'Elite' : ($user->point_balance >= 100 ? 'Andal' : ($user->point_balance >= 50 ? 'Disiplin' : 'Pemula'));
                            @endphp
                            <span class="badge badge-red">{{ $level }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data karyawan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection