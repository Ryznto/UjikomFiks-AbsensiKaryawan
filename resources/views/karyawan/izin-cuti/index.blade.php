@extends('layouts.karyawan')

@section('title', 'Izin & Cuti')

@section('content')

@if(session('success'))
<div class="alert alert-success">✅ {{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">❌ {{ session('error') }}</div>
@endif

{{-- FORM PENGAJUAN --}}
<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Ajukan Izin / Cuti
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('karyawan.izin-cuti.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Jenis <span style="color:var(--red)">*</span></label>
                <select name="jenis" class="form-control">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="izin"  {{ old('jenis') == 'izin'  ? 'selected' : '' }}>📋 Izin</option>
                    <option value="sakit" {{ old('jenis') == 'sakit' ? 'selected' : '' }}>🏥 Sakit</option>
                    <option value="cuti"  {{ old('jenis') == 'cuti'  ? 'selected' : '' }}>🏖️ Cuti</option>
                </select>
                @error('jenis')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Mulai <span style="color:var(--red)">*</span></label>
                <input type="date" name="tanggal_mulai" class="form-control"
                    value="{{ old('tanggal_mulai') }}"
                    min="{{ today()->toDateString() }}">
                @error('tanggal_mulai')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Selesai <span style="color:var(--red)">*</span></label>
                <input type="date" name="tanggal_selesai" class="form-control"
                    value="{{ old('tanggal_selesai') }}"
                    min="{{ today()->toDateString() }}">
                @error('tanggal_selesai')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3"
                    placeholder="Opsional — tulis keterangan jika perlu">{{ old('keterangan') }}</textarea>
                @error('keterangan')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
        </form>
    </div>
</div>

 {{-- RIWAYAT --}}
<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">Riwayat Pengajuan</div>
    </div>

    {{-- FILTER --}}
    <div style="padding: 12px 18px; border-bottom: 1px solid var(--border);">
        <form method="GET" action="{{ route('karyawan.izin-cuti.index') }}" style="display: flex; gap: 8px; flex-wrap: wrap;">
            <select name="bulan" class="form-control" style="flex: 1; min-width: 100px;">
                <option value="">Semua Bulan</option>
                @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
                @endforeach
            </select>
            <select name="tahun" class="form-control" style="flex: 1; min-width: 80px;">
                <option value="">Semua Tahun</option>
                @foreach(range(date('Y'), date('Y') - 2) as $y)
                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <select name="jenis" class="form-control" style="flex: 1; min-width: 90px;">
                <option value="">Semua Jenis</option>
                <option value="izin"  {{ request('jenis') == 'izin'  ? 'selected' : '' }}>📋 Izin</option>
                <option value="sakit" {{ request('jenis') == 'sakit' ? 'selected' : '' }}>🏥 Sakit</option>
                <option value="cuti"  {{ request('jenis') == 'cuti'  ? 'selected' : '' }}>🏖️ Cuti</option>
            </select>
            <select name="status" class="form-control" style="flex: 1; min-width: 100px;">
                <option value="">Semua Status</option>
                <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            <div style="display: flex; gap: 6px; width: 100%;">
                <button type="submit" class="btn btn-primary btn-inline" style="flex: 1;">Filter</button>
                <a href="{{ route('karyawan.izin-cuti.index') }}" class="btn btn-secondary btn-inline" style="flex: 1; text-align: center;">Reset</a>
            </div>
        </form>
    </div>

    @forelse($izinCutis as $ic)
    <div style="padding: 14px 18px; border-bottom: 1px solid rgba(42,47,66,0.5);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                @php
                    $jenisMap = [
                        'izin'  => ['class' => 'badge-blue',  'label' => '📋 Izin'],
                        'sakit' => ['class' => 'badge-red',   'label' => '🏥 Sakit'],
                        'cuti'  => ['class' => 'badge-green', 'label' => '🏖️ Cuti'],
                    ];
                    $j = $jenisMap[$ic->jenis] ?? ['class' => 'badge-gray', 'label' => $ic->jenis];
                @endphp
                <span class="badge {{ $j['class'] }}">{{ $j['label'] }}</span>
                @php
                    $durasi = \Carbon\Carbon::parse($ic->tanggal_mulai)
                        ->diffInDays(\Carbon\Carbon::parse($ic->tanggal_selesai)) + 1;
                @endphp
                <span class="badge badge-gray">{{ $durasi }} hari</span>
            </div>
            @if($ic->status === 'pending')
                <span class="badge badge-amber"><span class="badge-dot"></span>Pending</span>
            @elseif($ic->status === 'approved')
                <span class="badge badge-green"><span class="badge-dot"></span>Approved</span>
            @else
                <span class="badge badge-red"><span class="badge-dot"></span>Rejected</span>
            @endif
        </div>
        <div style="font-size: 12px; color: var(--mid); font-family: var(--mono);">
            {{ \Carbon\Carbon::parse($ic->tanggal_mulai)->format('d M Y') }}
            @if($ic->tanggal_mulai != $ic->tanggal_selesai)
                → {{ \Carbon\Carbon::parse($ic->tanggal_selesai)->format('d M Y') }}
            @endif
        </div>
        @if($ic->keterangan)
        <div style="font-size: 12px; color: var(--muted); margin-top: 6px;">
            {{ $ic->keterangan }}
        </div>
        @endif
        @if($ic->status !== 'pending')
        <div style="font-size: 11px; color: var(--mid); margin-top: 6px; font-family: var(--mono);">
            Diproses: {{ $ic->approved_at ? \Carbon\Carbon::parse($ic->approved_at)->format('d M Y') : '-' }}
        </div>
        @endif
    </div>
    @empty
    <div class="empty-state">
        <p>Belum ada riwayat pengajuan</p>
    </div>
    @endforelse

    @if($izinCutis->hasPages())
    <div style="padding: 14px 18px; border-top: 1px solid var(--border);">
        {{ $izinCutis->links() }}
    </div>
    @endif
</div>

@endsection