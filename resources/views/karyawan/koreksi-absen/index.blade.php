@extends('layouts.karyawan')

@section('title', 'Koreksi Absen')

@section('content')

@if(session('success'))
<div class="alert alert-success">✅ {{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">❌ {{ session('error') }}</div>
@endif

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
    <div class="section-title" style="margin-bottom: 0;">Koreksi Absen</div>
    <a href="{{ route('karyawan.koreksi-absen.create') }}" class="btn btn-primary btn-inline">+ Ajukan</a>
</div>

<div class="card fade-in">
    @forelse($koreksis as $k)
    <div style="padding: 14px 18px; border-bottom: 1px solid rgba(42,47,66,0.5);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <div style="font-size: 13px; font-weight: 600; color: var(--white); font-family: var(--mono);">
                {{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}
            </div>
            @if($k->status === 'pending')
                <span class="badge badge-amber"><span class="badge-dot"></span>Pending</span>
            @elseif($k->status === 'approved')
                <span class="badge badge-green"><span class="badge-dot"></span>Approved</span>
            @else
                <span class="badge badge-red"><span class="badge-dot"></span>Rejected</span>
            @endif
        </div>

        <div style="display: flex; gap: 12px; margin-bottom: 8px;">
            <div>
                <div style="font-size: 10px; color: var(--mid); font-family: var(--mono); margin-bottom: 2px;">JAM MASUK</div>
                <div style="font-size: 13px; font-family: var(--mono); color: var(--green);">{{ $k->jam_masuk }}</div>
            </div>
            <div>
                <div style="font-size: 10px; color: var(--mid); font-family: var(--mono); margin-bottom: 2px;">JAM PULANG</div>
                <div style="font-size: 13px; font-family: var(--mono); color: var(--amber);">{{ $k->jam_pulang ?? '—' }}</div>
            </div>
        </div>

        <div style="font-size: 12px; color: var(--muted);">{{ $k->alasan }}</div>

        @if($k->catatan_admin)
        <div style="font-size: 11px; color: var(--mid); margin-top: 6px; padding: 8px 10px; background: var(--surface-2); border-radius: var(--radius-sm);">
            💬 Admin: {{ $k->catatan_admin }}
        </div>
        @endif

        @if($k->status !== 'pending')
        <div style="font-size: 11px; color: var(--mid); margin-top: 6px; font-family: var(--mono);">
            Diproses: {{ $k->approved_at ? \Carbon\Carbon::parse($k->approved_at)->format('d M Y H:i') : '-' }}
        </div>
        @endif
    </div>
    @empty
    <div class="empty-state">
        <p>Belum ada pengajuan koreksi absen</p>
    </div>
    @endforelse

    @if($koreksis->hasPages())
    <div style="padding: 14px 18px; border-top: 1px solid var(--border);">
        {{ $koreksis->links() }}
    </div>
    @endif
</div>

@endsection