@extends('layouts.app')

@section('title', 'Detail Karyawan')
@section('page-title', 'Detail Karyawan')
@section('page-sub', $karyawan->nama)

@section('page-actions')
<a href="{{ route('admin.karyawan.edit', $karyawan) }}" class="btn btn-secondary">Edit</a>
<a href="{{ route('admin.karyawan.index') }}" class="btn btn-secondary">← Kembali</a>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success">✅ {{ session('success') }}</div>
@endif

@if(session('generated_password'))
<div class="alert alert-success" style="flex-direction: column; align-items: flex-start; gap: 6px;">
    <strong>🔑 Password Baru</strong>
    <span>NIP: <strong>{{ session('generated_nip') }}</strong></span>
    <span>Password: <strong style="font-family: var(--mono); font-size: 15px;">{{ session('generated_password') }}</strong></span>
    <small style="color: var(--mid);">Catat password ini sekarang, tidak akan ditampilkan lagi.</small>
</div>
@endif

<div class="card">
    <div class="card-header">
        <div class="card-title">Info Karyawan</div>
        <form method="POST" action="{{ route('admin.karyawan.reset-password', $karyawan) }}"
            onsubmit="return confirm('Reset password karyawan ini?')">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm">🔑 Reset Password</button>
        </form>
    </div>
    <div class="card-body">
        <div class="form-grid-2" style="gap: 20px;">
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">NAMA</div>
                <div style="font-size: 15px; font-weight: 600;">{{ $karyawan->nama }}</div>
            </div>
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">NIP</div>
                <div style="font-size: 15px; font-weight: 600; font-family: var(--mono);">{{ $karyawan->user->nip }}</div>
            </div>
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">DIVISI</div>
                <div style="font-size: 15px; font-weight: 600;">{{ $karyawan->divisi->nama_divisi }}</div>
            </div>
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">JABATAN</div>
                <div style="font-size: 15px; font-weight: 600;">{{ $karyawan->jabatan->nama_jabatan }}</div>
            </div>
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">SHIFT</div>
                <div style="font-size: 15px; font-weight: 600;">{{ $karyawan->shift->nama_shift }} ({{ $karyawan->shift->jam_masuk }} - {{ $karyawan->shift->jam_pulang }})</div>
            </div>
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">NO. HP</div>
                <div style="font-size: 15px; font-weight: 600;">{{ $karyawan->no_hp ?? '-' }}</div>
            </div>
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">STATUS</div>
                @if($karyawan->status_aktif)
                    <span class="badge badge-green"><span class="badge-dot"></span>Aktif</span>
                @else
                    <span class="badge badge-red"><span class="badge-dot"></span>Nonaktif</span>
                @endif
            </div>
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">TERDAFTAR</div>
                <div style="font-size: 15px; font-weight: 600;">{{ $karyawan->created_at->format('d M Y') }}</div>
            </div>
        </div>
    </div>
</div>
@endsection