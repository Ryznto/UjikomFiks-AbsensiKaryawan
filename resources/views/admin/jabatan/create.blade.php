@extends('layouts.app')

@section('title', 'Tambah Jabatan')
@section('page-title', 'Tambah Jabatan')
@section('page-sub', 'Buat jabatan baru')

@section('page-actions')
<a href="{{ route('admin.jabatan.index') }}" class="btn btn-secondary">← Kembali</a>
@endsection

@section('content')
<div class="card" style="max-width: 520px;">
    <div class="card-header">
        <div class="card-title">Form Tambah Jabatan</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.jabatan.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Jabatan <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama_jabatan" class="form-control"
                    value="{{ old('nama_jabatan') }}" placeholder="Contoh: Backend Developer" autofocus>
                @error('nama_jabatan')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Divisi <span style="color:var(--red)">*</span></label>
                <select name="divisi_id" class="form-control">
                    <option value="">-- Pilih Divisi --</option>
                    @foreach($divisis as $d)
                    <option value="{{ $d->id }}" {{ old('divisi_id') == $d->id ? 'selected' : '' }}>
                        {{ $d->nama_divisi }}
                    </option>
                    @endforeach
                </select>
                @error('divisi_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.jabatan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection