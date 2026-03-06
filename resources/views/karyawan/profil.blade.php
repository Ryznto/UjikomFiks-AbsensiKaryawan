@extends('layouts.karyawan')

@section('title', 'Profil Saya')

@section('content')

@if(session('success'))
<div class="alert alert-success">✅ {{ session('success') }}</div>
@endif

{{-- INFO KARYAWAN --}}
<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Informasi Akun
        </div>
    </div>
    <div class="card-body">

        {{-- AVATAR --}}
        <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--border);">
            <div style="width: 60px; height: 60px; border-radius: 14px; background: linear-gradient(135deg, #4f7cff, #a855f7); display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800; color: white; flex-shrink: 0;">
                {{ strtoupper(substr($karyawan->nama, 0, 2)) }}
            </div>
            <div>
                <div style="font-size: 17px; font-weight: 700;">{{ $karyawan->nama }}</div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-top: 3px;">{{ $user->nip }}</div>
                <span class="badge badge-blue" style="margin-top: 6px;">Karyawan</span>
            </div>
        </div>

        <div class="info-row">
            <span class="info-label">NIP</span>
            <span class="info-value mono">{{ $user->nip }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Nama</span>
            <span class="info-value">{{ $karyawan->nama }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Divisi</span>
            <span class="info-value">{{ $karyawan->divisi->nama_divisi }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jabatan</span>
            <span class="info-value">{{ $karyawan->jabatan->nama_jabatan }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Shift</span>
            <span class="info-value">
                {{ $karyawan->shift->nama_shift }}
                <span style="color: var(--mid); font-size: 11px; font-family: var(--mono);">
                    {{ $karyawan->shift->jam_masuk }} - {{ $karyawan->shift->jam_pulang }}
                </span>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">No. HP</span>
            <span class="info-value">{{ $karyawan->no_hp ?? '—' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status</span>
            <span class="info-value">
                @if($karyawan->status_aktif)
                    <span class="badge badge-green"><span class="badge-dot"></span>Aktif</span>
                @else
                    <span class="badge badge-red"><span class="badge-dot"></span>Nonaktif</span>
                @endif
            </span>
        </div>
    </div>
</div>

{{-- GANTI PASSWORD --}}
<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">Ganti Password</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('karyawan.profil.password') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Password Lama <span style="color:var(--red)">*</span></label>
                <input type="password" name="password_lama" class="form-control"
                    placeholder="Masukkan password saat ini">
                @error('password_lama')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password Baru <span style="color:var(--red)">*</span></label>
                <input type="password" name="password_baru" class="form-control"
                    placeholder="Minimal 8 karakter">
                @error('password_baru')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru <span style="color:var(--red)">*</span></label>
                <input type="password" name="password_baru_confirmation" class="form-control"
                    placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div>
</div>

@endsection