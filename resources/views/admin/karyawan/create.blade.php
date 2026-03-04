@extends('layouts.app')

@section('title', 'Tambah Karyawan')
@section('page-title', 'Tambah Karyawan')
@section('page-sub', 'Buat akun dan data karyawan baru')

@section('page-actions')
<a href="{{ route('admin.karyawan.index') }}" class="btn btn-secondary">← Kembali</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">Form Tambah Karyawan</div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.karyawan.store') }}">
            @csrf

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">NIP <span style="color:var(--red)">*</span></label>
                    <input type="text" name="nip" class="form-control" value="{{ old('nip') }}" placeholder="Contoh: EMP001">
                    @error('nip')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:var(--red)">*</span></label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Nama lengkap karyawan">
                    @error('nama')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-grid-3">
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
                <div class="form-group">
                    <label class="form-label">Jabatan <span style="color:var(--red)">*</span></label>
                    <select name="jabatan_id" class="form-control">
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach($jabatans as $j)
                        <option value="{{ $j->id }}" {{ old('jabatan_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_jabatan }} ({{ $j->divisi->nama_divisi }})
                        </option>
                        @endforeach
                    </select>
                    @error('jabatan_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Shift <span style="color:var(--red)">*</span></label>
                    <select name="shift_id" class="form-control">
                        <option value="">-- Pilih Shift --</option>
                        @foreach($shifts as $s)
                        <option value="{{ $s->id }}" {{ old('shift_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->nama_shift }} ({{ $s->jam_masuk }} - {{ $s->jam_pulang }})
                        </option>
                        @endforeach
                    </select>
                    @error('shift_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="Opsional">
                @error('no_hp')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div style="background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 14px 16px; margin-bottom: 20px;">
                <div style="font-size: 12px; color: var(--amber);">⚠️ Password akan di-generate otomatis oleh sistem dan ditampilkan sekali setelah karyawan berhasil ditambahkan.</div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Simpan Karyawan</button>
                <a href="{{ route('admin.karyawan.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection