@extends('layouts.karyawan')

@section('title', 'Ajukan Koreksi Absen')

@section('content')

@if(session('error'))
<div class="alert alert-danger">❌ {{ session('error') }}</div>
@endif

<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
    <a href="{{ route('karyawan.koreksi-absen.index') }}" class="btn btn-secondary btn-inline">← Kembali</a>
    <div class="section-title" style="margin-bottom: 0;">Ajukan Koreksi Absen</div>
</div>

<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">Form Koreksi</div>
    </div>
    <div class="card-body">

        <div class="alert alert-warning" style="margin-bottom: 20px;">
            ⚠️ Koreksi absen hanya bisa diajukan untuk hari yang sudah lewat. Admin akan memverifikasi pengajuan kamu.
        </div>
@if($alfas->isEmpty())
<div class="alert alert-warning">
    ⚠️ Tidak ada presensi alfa yang bisa dikoreksi.
</div>
@endif
        <form method="POST" action="{{ route('karyawan.koreksi-absen.store') }}">
            @csrf

            <div class="form-group">
    <label class="form-label">Pilih Tanggal Alfa <span style="color:var(--red)">*</span></label>
    <select name="tanggal" id="input-tanggal" class="form-control">
        <option value="">-- Pilih Tanggal --</option>
        @forelse($alfas as $a)
        <option value="{{ $a->tanggal }}" {{ old('tanggal') == $a->tanggal ? 'selected' : '' }}>
            {{ \Carbon\Carbon::parse($a->tanggal)->translatedFormat('l, d F Y') }}
        </option>
        @empty
        <option value="" disabled>Tidak ada presensi alfa</option>
        @endforelse
    </select>
    <div id="nama-hari" style="font-size: 12px; color: var(--mid); margin-top: 6px; font-family: var(--mono);"></div>
    @error('tanggal')<div class="form-error">{{ $message }}</div>@enderror
</div>

            <div class="form-group">
                <label class="form-label">Jam Masuk <span style="color:var(--red)">*</span></label>
                <input type="time" name="jam_masuk" class="form-control" value="{{ old('jam_masuk') }}">
                @error('jam_masuk')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Jam Pulang</label>
                <input type="time" name="jam_pulang" class="form-control" value="{{ old('jam_pulang') }}">
                <div style="font-size: 11px; color: var(--mid); margin-top: 4px;">Opsional — isi jika juga lupa absen pulang</div>
                @error('jam_pulang')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Alasan <span style="color:var(--red)">*</span></label>
                <textarea name="alasan" class="form-control" rows="3"
                    placeholder="Jelaskan alasan koreksi absen...">{{ old('alasan') }}</textarea>
                @error('alasan')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
        </form>
    </div>
</div>
@push('scripts')
<script>
    const inputTanggal = document.getElementById('input-tanggal');
    const namaHari     = document.getElementById('nama-hari');

    const hariMap = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const hariWarna = {
        0: 'var(--red)',   // Minggu
        6: 'var(--red)',   // Sabtu
    };

    function updateNamaHari() {
        const val = inputTanggal.value;
        if (!val) { namaHari.textContent = ''; return; }

        const date  = new Date(val + 'T00:00:00');
        const day   = date.getDay();
        const nama  = hariMap[day];
        const color = hariWarna[day] ?? 'var(--green)';
        const extra = (day === 0 || day === 6) ? ' — ⚠️ Hari libur, tidak bisa diajukan' : '';

        namaHari.style.color   = color;
        namaHari.textContent   = `📅 ${nama}${extra}`;
    }

    inputTanggal.addEventListener('change', updateNamaHari);

    // Trigger kalau ada old value
    if (inputTanggal.value) updateNamaHari();
</script>
@endpush
@endsection