@extends('layouts.app')

@section('title', 'Profil Admin')
@section('page-title', 'Profil Saya')
@section('page-sub', 'Kelola informasi akun admin')

@section('content')

@if(session('success'))
<div class="alert alert-success">✅ {{ session('success') }}</div>
@endif

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

    {{-- INFO PROFIL --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Informasi Profil</div>
        </div>
        <div class="card-body">

            {{-- FOTO --}}
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                <div style="width: 72px; height: 72px; border-radius: 16px; overflow: hidden; flex-shrink: 0; background: linear-gradient(135deg, #4f7cff, #a855f7); display: flex; align-items: center; justify-content: center;">
                    @if($profil->foto)
                        <img src="{{ asset('storage/' . $profil->foto) }}"
                            style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <span style="font-size: 28px; font-weight: 800; color: white;">
                            {{ strtoupper(substr($profil->nama_admin, 0, 2)) }}
                        </span>
                    @endif
                </div>
                <div>
                    <div style="font-size: 18px; font-weight: 700;">{{ $profil->nama_admin }}</div>
                    <div style="font-size: 12px; color: var(--mid); font-family: var(--mono); margin-top: 3px;">{{ $user->nip }}</div>
                    <span class="badge badge-blue" style="margin-top: 6px;">Admin</span>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.profil.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nama Admin <span style="color:var(--red)">*</span></label>
                    <input type="text" name="nama_admin" class="form-control"
                        value="{{ old('nama_admin', $profil->nama_admin) }}">
                    @error('nama_admin')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="{{ old('email', $profil->email) }}" placeholder="Opsional">
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control"
                        value="{{ old('no_hp', $profil->no_hp) }}" placeholder="Opsional">
                    @error('no_hp')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="foto" class="form-control" accept="image/*"
                        onchange="previewFoto(this)">
                    @error('foto')<div class="form-error">{{ $message }}</div>@enderror
                    <div id="foto-preview" style="margin-top: 10px; display: none;">
                        <img id="preview-img" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover; border: 1px solid var(--border);">
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- GANTI PASSWORD --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Ganti Password</div>
        </div>
        <div class="card-body">

            <div style="background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 14px 16px; margin-bottom: 20px;">
                <div style="font-size: 12px; color: var(--mid);">
                    🔐 NIP Login: <strong style="color: var(--white); font-family: var(--mono);">{{ $user->nip }}</strong>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.profil.password') }}">
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

</div>

@endsection

@push('scripts')
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('foto-preview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush