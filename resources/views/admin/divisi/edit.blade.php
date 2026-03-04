@extends('layouts.app')

@section('title', 'Edit Divisi')
@section('page-title', 'Edit Divisi')
@section('page-sub', $divisi->nama_divisi)

@section('page-actions')
<a href="{{ route('admin.divisi.index') }}" class="btn btn-secondary">← Kembali</a>
@endsection

@section('content')
<div class="card" style="max-width: 520px;">
    <div class="card-header">
        <div class="card-title">Edit — {{ $divisi->nama_divisi }}</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.divisi.update', $divisi) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Divisi <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama_divisi" class="form-control"
                    value="{{ old('nama_divisi', $divisi->nama_divisi) }}">
                @error('nama_divisi')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.divisi.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection