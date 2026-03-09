@extends('layouts.app')

@section('title', 'Monitoring Presensi')
@section('page-title', 'Monitoring Presensi')
@section('page-sub', 'Pantau kehadiran karyawan secara realtime')

@section('content')

<div class="stats-grid" style="grid-template-columns: repeat(4,1fr); margin-bottom: 24px;">
    <div class="stat-card green">
        <div class="stat-icon">✅</div>
        <div class="stat-value">{{ $totalHadir }}</div>
        <div class="stat-label">Hadir</div>
        <div class="stat-sub">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon">⏰</div>
        <div class="stat-value">{{ $totalTerlambat }}</div>
        <div class="stat-label">Terlambat</div>
        <div class="stat-sub">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon">❌</div>
        <div class="stat-value">{{ $totalAlfa }}</div>
        <div class="stat-label">Alfa</div>
        <div class="stat-sub">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon">🏃</div>
        <div class="stat-value">{{ $totalPulangCepat }}</div>
        <div class="stat-label">Pulang Cepat</div>
        <div class="stat-sub">{{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Log Presensi
        </div>
    </div>

    {{-- Filter --}}
    <div style="padding: 16px 22px; border-bottom: 1px solid var(--border);">
        <form method="GET" action="{{ route('admin.presensi.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
            <div>
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control"
                    value="{{ request('tanggal', today()->toDateString()) }}"
                    style="width: 180px;">
            </div>
            <div>
                <label class="form-label">Divisi</label>
                <select name="divisi_id" class="form-control" style="width: 180px;">
                    <option value="">Semua Divisi</option>
                    @foreach($divisis as $d)
                    <option value="{{ $d->id }}" {{ request('divisi_id') == $d->id ? 'selected' : '' }}>
                        {{ $d->nama_divisi }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status_absen" class="form-control" style="width: 180px;">
                    <option value="">Semua Status</option>
                    <option value="tepat_waktu"  {{ request('status_absen') == 'tepat_waktu'  ? 'selected' : '' }}>Tepat Waktu</option>
                    <option value="terlambat"    {{ request('status_absen') == 'terlambat'    ? 'selected' : '' }}>Terlambat</option>
                    <option value="alfa"         {{ request('status_absen') == 'alfa'         ? 'selected' : '' }}>Alfa</option>
                </select>
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.presensi.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Divisi</th>
                    <th>Shift</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Lokasi</th>
                    <th>Foto</th>
                    <th>Status Masuk</th>
                    <th>Status Pulang</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presensis as $p)
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
                    <td><span class="badge badge-blue">{{ $p->shift->nama_shift ?? $p->karyawan->shift->nama_shift }}</span></td>
                    <td style="font-family: var(--mono); font-size: 12px;">
                        {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                    </td>
                    <td style="font-family: var(--mono); color: var(--green);">
                        {{ $p->jam_masuk ?? '—' }}
                    </td>
                    <td style="font-family: var(--mono); color: var(--amber);">
                        {{ $p->jam_pulang ?? '—' }}
                    </td>
                    <td>
                        @if($p->latitude && $p->longitude)
                            <a href="https://maps.google.com/?q={{ $p->latitude }},{{ $p->longitude }}"
                                target="_blank" class="btn btn-secondary btn-sm">📍 Maps</a>
                        @else
                            <span style="color: var(--mid); font-size: 12px;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($p->foto_masuk)
                            <a href="{{ asset('storage/' . $p->foto_masuk) }}" target="_blank" class="btn btn-secondary btn-sm">📸 Lihat</a>
                        @else
                            <span style="color: var(--mid); font-size: 12px;">—</span>
                        @endif
                    </td>
                   <td>
                        @php
                            $badgeMasuk = [
                                'tepat_waktu' => ['class' => 'badge-green', 'label' => 'Tepat Waktu'],
                                'terlambat'   => ['class' => 'badge-amber', 'label' => 'Terlambat'],
                                'alfa'        => ['class' => 'badge-red',   'label' => 'Alfa'],
                            ];
                            $bm = $badgeMasuk[$p->status_absen] ?? ['class' => 'badge-gray', 'label' => '-'];
                        @endphp
                        <span class="badge {{ $bm['class'] }}">
                            <span class="badge-dot"></span>{{ $bm['label'] }}
                        </span>
                    </td>
                    <td>
                        @if($p->jam_pulang)
                            @php
                                $badgePulang = [
                                    'tepat_waktu'  => ['class' => 'badge-green', 'label' => 'Tepat Waktu'],
                                    'pulang_cepat' => ['class' => 'badge-amber', 'label' => 'Pulang Cepat'],
                                ];
                                $bp = $badgePulang[$p->status_pulang] ?? ['class' => 'badge-gray', 'label' => '-'];
                            @endphp
                            <span class="badge {{ $bp['class'] }}">
                                <span class="badge-dot"></span>{{ $bp['label'] }}
                            </span>
                        @else
                            <span style="color:var(--mid);font-size:12px;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <p>Belum ada data presensi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($presensis->hasPages())
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        {{ $presensis->links() }}
    </div>
    @endif
</div>

@endsection