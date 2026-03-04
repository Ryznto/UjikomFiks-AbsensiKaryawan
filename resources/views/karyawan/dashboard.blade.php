@extends('layouts.karyawan')

@section('title', 'Dashboard')

@section('content')

{{-- GREETING --}}
<div style="margin-bottom: 20px;">
    <div style="font-size: 20px; font-weight: 800; letter-spacing: -0.5px;">
        👋 Halo, {{ explode(' ', $karyawan->nama)[0] }}!
    </div>
    <div style="font-size: 12px; color: var(--mid); font-family: var(--mono); margin-top: 4px;">
        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>
</div>

{{-- STATUS ABSEN HARI INI --}}
<div class="card fade-in" style="margin-bottom: 16px;">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Absensi Hari Ini
        </div>
        @if($presensiHariIni)
            @php
                $badgeMap = [
                    'tepat_waktu'  => ['class' => 'badge-green', 'label' => 'Tepat Waktu'],
                    'terlambat'    => ['class' => 'badge-amber', 'label' => 'Terlambat'],
                    'pulang_cepat' => ['class' => 'badge-amber', 'label' => 'Pulang Cepat'],
                    'alfa'         => ['class' => 'badge-red',   'label' => 'Alfa'],
                ];
                $b = $badgeMap[$presensiHariIni->status_absen] ?? ['class' => 'badge-gray', 'label' => '-'];
            @endphp
            <span class="badge {{ $b['class'] }}"><span class="badge-dot"></span>{{ $b['label'] }}</span>
        @else
            <span class="badge badge-gray">Belum Absen</span>
        @endif
    </div>
    <div class="card-body">
        <div class="info-row">
            <span class="info-label">🌅 Jam Masuk</span>
            <span class="info-value mono" style="color: {{ $presensiHariIni?->jam_masuk ? 'var(--green)' : 'var(--mid)' }}">
                {{ $presensiHariIni?->jam_masuk ?? '—' }}
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">🌆 Jam Pulang</span>
            <span class="info-value mono" style="color: {{ $presensiHariIni?->jam_pulang ? 'var(--amber)' : 'var(--mid)' }}">
                {{ $presensiHariIni?->jam_pulang ?? '—' }}
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">⏱️ Shift</span>
            <span class="info-value">
                {{ $karyawan->shift->nama_shift }}
                <span style="color: var(--mid); font-size: 11px; font-family: var(--mono);">
                    {{ $karyawan->shift->jam_masuk }} - {{ $karyawan->shift->jam_pulang }}
                </span>
            </span>
        </div>

        <div style="margin-top: 16px; display: flex; gap: 10px;">
            @if(!$presensiHariIni || !$presensiHariIni->jam_masuk)
                <a href="{{ route('karyawan.presensi.index') }}" class="btn btn-primary">
                    📷 Absen Masuk
                </a>
            @elseif(!$presensiHariIni->jam_pulang)
                <a href="{{ route('karyawan.presensi.index') }}" class="btn btn-success">
                    🏠 Absen Pulang
                </a>
            @else
                <div class="btn btn-secondary" style="cursor: default;">
                    ✅ Absensi Selesai
                </div>
            @endif
        </div>
    </div>
</div>

{{-- STATISTIK BULAN INI --}}
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
    <div class="card fade-in" style="margin-bottom: 0;">
        <div class="card-body" style="text-align: center; padding: 20px 16px;">
            <div style="font-size: 32px; font-weight: 800; font-family: var(--mono); color: var(--green); letter-spacing: -1px;">
                {{ $hadirBulanIni }}
            </div>
            <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">Hadir Bulan Ini</div>
        </div>
    </div>
    <div class="card fade-in" style="margin-bottom: 0;">
        <div class="card-body" style="text-align: center; padding: 20px 16px;">
            <div style="font-size: 32px; font-weight: 800; font-family: var(--mono); color: var(--amber); letter-spacing: -1px;">
                {{ $terlambatBulanIni }}
            </div>
            <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">Terlambat Bulan Ini</div>
        </div>
    </div>
</div>

{{-- INFO KARYAWAN --}}
<div class="card fade-in" style="margin-bottom: 16px;">
    <div class="card-header">
        <div class="card-title">Info Akun</div>
    </div>
    <div class="card-body">
        <div class="info-row">
            <span class="info-label">NIP</span>
            <span class="info-value mono">{{ Auth::user()->nip }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Divisi</span>
            <span class="info-value">{{ $karyawan->divisi->nama_divisi }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jabatan</span>
            <span class="info-value">{{ $karyawan->jabatan->nama_jabatan }}</span>
        </div>
    </div>
</div>

{{-- RIWAYAT TERAKHIR --}}
<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">Riwayat Terakhir</div>
        <a href="{{ route('karyawan.presensi.index') }}" class="card-action">Semua →</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Masuk</th>
                    <th>Pulang</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatPresensi as $p)
                <tr>
                    <td style="font-family: var(--mono); font-size: 11px;">
                        {{ \Carbon\Carbon::parse($p->tanggal)->format('d M') }}
                    </td>
                    <td style="font-family: var(--mono); color: var(--green); font-size: 12px;">
                        {{ $p->jam_masuk ?? '—' }}
                    </td>
                    <td style="font-family: var(--mono); color: var(--amber); font-size: 12px;">
                        {{ $p->jam_pulang ?? '—' }}
                    </td>
                    <td>
                        @php
                            $badgeMap = [
                                'tepat_waktu'  => ['class' => 'badge-green', 'label' => 'Tepat'],
                                'terlambat'    => ['class' => 'badge-amber', 'label' => 'Lambat'],
                                'pulang_cepat' => ['class' => 'badge-amber', 'label' => 'P.Cepat'],
                                'alfa'         => ['class' => 'badge-red',   'label' => 'Alfa'],
                            ];
                            $b = $badgeMap[$p->status_absen] ?? ['class' => 'badge-gray', 'label' => '-'];
                        @endphp
                        <span class="badge {{ $b['class'] }}" style="font-size: 10px; padding: 2px 7px;">
                            {{ $b['label'] }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <p>Belum ada riwayat presensi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection