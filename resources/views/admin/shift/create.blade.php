@extends('layouts.app')

@section('title', 'Tambah Shift')
@section('page-title', 'Tambah Shift')
@section('page-sub', 'Buat jadwal shift baru')

@section('page-actions')
<a href="{{ route('admin.shift.index') }}" class="btn btn-secondary">← Kembali</a>
@endsection

@section('content')
<div class="card" style="max-width: 520px;">
    <div class="card-header">
        <div class="card-title">Form Tambah Shift</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.shift.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Shift <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama_shift" class="form-control"
                    value="{{ old('nama_shift') }}" placeholder="Contoh: Shift Pagi" autofocus>
                @error('nama_shift')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Jam Masuk <span style="color:var(--red)">*</span></label>
                    <input type="time" name="jam_masuk" class="form-control" value="{{ old('jam_masuk') }}">
                    @error('jam_masuk')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Jam Pulang <span style="color:var(--red)">*</span></label>
                    <input type="time" name="jam_pulang" class="form-control" value="{{ old('jam_pulang') }}">
                    @error('jam_pulang')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Toleransi Terlambat (menit) <span style="color:var(--red)">*</span></label>
                <input type="number" name="toleransi_terlambat" class="form-control"
                    value="{{ old('toleransi_terlambat', 15) }}" min="0" placeholder="15">
                @error('toleransi_terlambat')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.shift.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection