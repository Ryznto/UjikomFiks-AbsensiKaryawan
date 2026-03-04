@extends('layouts.app')

@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Karyawan')
@section('page-sub', 'Perbarui data karyawan')

@section('page-actions')
<a href="{{ route('admin.karyawan.index') }}" class="btn btn-secondary">← Kembali</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Edit Data — {{ $karyawan->nama }}</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.karyawan.update', $karyawan) }}">
            @csrf
            @method('PUT')

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">NIP <span style="color:var(--red)">*</span></label>
                    <input type="text" name="nip" class="form-control" value="{{ old('nip', $karyawan->user->nip) }}">
                    @error('nip')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:var(--red)">*</span></label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $karyawan->nama) }}">
                    @error('nama')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">Divisi <span style="color:var(--red)">*</span></label>
                    <select name="divisi_id" class="form-control">
                        @foreach($divisis as $d)
                        <option value="{{ $d->id }}" {{ old('divisi_id', $karyawan->divisi_id) == $d->id ? 'selected' : '' }}>
                            {{ $d->nama_divisi }}
                        </option>
                        @endforeach
                    </select>
                    @error('divisi_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Jabatan <span style="color:var(--red)">*</span></label>
                    <select name="jabatan_id" class="form-control">
                        @foreach($jabatans as $j)
                        <option value="{{ $j->id }}" {{ old('jabatan_id', $karyawan->jabatan_id) == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_jabatan }} ({{ $j->divisi->nama_divisi }})
                        </option>
                        @endforeach
                    </select>
                    @error('jabatan_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Shift <span style="color:var(--red)">*</span></label>
                    <select name="shift_id" class="form-control">
                        @foreach($shifts as $s)
                        <option value="{{ $s->id }}" {{ old('shift_id', $karyawan->shift_id) == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_shift }} ({{ $s->jam_masuk }} - {{ $s->jam_pulang }})
                        </option>
                        @endforeach
                    </select>
                    @error('shift_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $karyawan->no_hp) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status_aktif" class="form-control">
                        <option value="1" {{ $karyawan->status_aktif ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$karyawan->status_aktif ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.karyawan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection