@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')
@section('page-sub', 'Download laporan presensi, rekap karyawan, dan izin cuti')

@section('content')

{{-- FILTER --}}
<div class="card" style="margin-bottom: 24px;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.laporan.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
            <div>
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-control" style="width: 160px;">
                    @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-control" style="width: 120px;">
                    @foreach(range(date('Y'), date('Y') - 3) as $y)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Divisi</label>
                <select name="divisi_id" class="form-control" style="width: 180px;">
                    <option value="">Semua Divisi</option>
                    @foreach($divisis as $d)
                    <option value="{{ $d->id }}" {{ $divisiId == $d->id ? 'selected' : '' }}>{{ $d->nama_divisi }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>
    </div>
</div>

{{-- SUMMARY --}}
<div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card green">
        <div class="stat-icon">✅</div>
        <div class="stat-value">{{ $totalHadir }}</div>
        <div class="stat-label">Total Hadir</div>
        <div class="stat-sub">{{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon">⏰</div>
        <div class="stat-value">{{ $totalTerlambat }}</div>
        <div class="stat-label">Total Terlambat</div>
        <div class="stat-sub">{{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon">❌</div>
        <div class="stat-value">{{ $totalAlfa }}</div>
        <div class="stat-label">Total Alfa</div>
        <div class="stat-sub">{{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon">📋</div>
        <div class="stat-value">{{ $totalIzinCuti }}</div>
        <div class="stat-label">Izin & Cuti</div>
        <div class="stat-sub">{{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</div>
    </div>
</div>

@php
    $queryString = http_build_query(['bulan' => $bulan, 'tahun' => $tahun, 'divisi_id' => $divisiId]);
@endphp

{{-- LAPORAN CARDS --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">

    {{-- Presensi --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">📅 Laporan Presensi</div>
        </div>
        <div class="card-body">
            <p style="font-size: 12px; color: var(--mid); margin-bottom: 16px;">
                Data presensi lengkap per karyawan termasuk jam masuk, jam pulang, dan status kehadiran.
            </p>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="{{ route('admin.laporan.presensi.excel') }}?{{ $queryString }}" class="btn btn-success">
                    📊 Download Excel
                </a>
                <a href="{{ route('admin.laporan.presensi.pdf') }}?{{ $queryString }}" class="btn btn-secondary">
                    📄 Download PDF
                </a>
            </div>
        </div>
    </div>

    {{-- Rekap Karyawan --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">👥 Rekap Karyawan</div>
        </div>
        <div class="card-body">
            <p style="font-size: 12px; color: var(--mid); margin-bottom: 16px;">
                Rekap kehadiran per karyawan: total hadir, terlambat, alfa, dan pulang cepat dalam sebulan.
            </p>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="{{ route('admin.laporan.karyawan.excel') }}?{{ $queryString }}" class="btn btn-success">
                    📊 Download Excel
                </a>
                <a href="{{ route('admin.laporan.karyawan.pdf') }}?{{ $queryString }}" class="btn btn-secondary">
                    📄 Download PDF
                </a>
            </div>
        </div>
    </div>

    {{-- Izin Cuti --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">🏖️ Laporan Izin & Cuti</div>
        </div>
        <div class="card-body">
            <p style="font-size: 12px; color: var(--mid); margin-bottom: 16px;">
                Data pengajuan izin, sakit, dan cuti karyawan beserta status persetujuan.
            </p>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="{{ route('admin.laporan.izin-cuti.excel') }}?{{ $queryString }}" class="btn btn-success">
                    📊 Download Excel
                </a>
                <a href="{{ route('admin.laporan.izin-cuti.pdf') }}?{{ $queryString }}" class="btn btn-secondary">
                    📄 Download PDF
                </a>
            </div>
        </div>
    </div>

</div>

@endsection