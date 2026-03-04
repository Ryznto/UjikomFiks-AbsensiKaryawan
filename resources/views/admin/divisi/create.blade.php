@extends('layouts.app')

@section('title', 'Tambah Divisi')
@section('page-title', 'Tambah Divisi')
@section('page-sub', 'Buat divisi baru')

@section('page-actions')
<a href="{{ route('admin.divisi.index') }}" class="btn btn-secondary">← Kembali</a>
@endsection

@section('content')
<div class="card" style="max-width: 520px;">
    <div class="card-header">
        <div class="card-title">Form Tambah Divisi</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.divisi.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Divisi <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama_divisi" class="form-control"
                    value="{{ old('nama_divisi') }}" placeholder="Contoh: Engineering" autofocus>
                @error('nama_divisi')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.divisi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection