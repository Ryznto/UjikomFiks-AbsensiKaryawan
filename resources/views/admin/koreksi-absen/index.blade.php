@extends('layouts.app')

@section('title', 'Koreksi Absen')
@section('page-title', 'Koreksi Absen')
@section('page-sub', 'Verifikasi pengajuan koreksi absen karyawan')

@section('content')

@if(session('success'))
<div class="alert alert-success">✅ {{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">❌ {{ session('error') }}</div>
@endif

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Daftar Pengajuan Koreksi
        </div>
        @if($totalPending > 0)
        <span class="badge badge-amber">{{ $totalPending }} pending</span>
        @endif
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Divisi</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Alasan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($koreksis as $k)
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background: linear-gradient(135deg, #4f7cff, #a855f7)">
                                {{ strtoupper(substr($k->karyawan->nama, 0, 2)) }}
                            </div>
                            <div>
                                <div class="emp-name">{{ $k->karyawan->nama }}</div>
                                <div class="emp-sub">{{ $k->karyawan->user->nip }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $k->karyawan->divisi->nama_divisi }}</td>
                    <td style="font-family: var(--mono); font-size: 12px;">
                        {{ \Carbon\Carbon::parse($k->tanggal)->format('d M Y') }}
                    </td>
                    <td style="font-family: var(--mono); color: var(--green);">{{ $k->jam_masuk }}</td>
                    <td style="font-family: var(--mono); color: var(--amber);">{{ $k->jam_pulang ?? '—' }}</td>
                    <td style="max-width: 200px; font-size: 12px; color: var(--muted);">
                        {{ Str::limit($k->alasan, 50) }}
                    </td>
                    <td>
                        @if($k->status === 'pending')
                            <span class="badge badge-amber"><span class="badge-dot"></span>Pending</span>
                        @elseif($k->status === 'approved')
                            <span class="badge badge-green"><span class="badge-dot"></span>Approved</span>
                        @else
                            <span class="badge badge-red"><span class="badge-dot"></span>Rejected</span>
                        @endif
                    </td>
                    <td>
                        @if($k->status === 'pending')
                        <div style="display: flex; gap: 6px;">
                            <form method="POST" action="{{ route('admin.koreksi-absen.approve', $k) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">✓ Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.koreksi-absen.reject', $k) }}"
                                onsubmit="return confirm('Tolak koreksi ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-sm">✕ Reject</button>
                            </form>
                        </div>
                        @else
                        <div style="font-size: 11px; color: var(--mid); font-family: var(--mono);">
                            {{ $k->approvedBy->adminProfile->nama_admin ?? '-' }}<br>
                            {{ $k->approved_at ? \Carbon\Carbon::parse($k->approved_at)->format('d M Y') : '' }}
                        </div>
                        @if($k->catatan_admin)
                        <div style="font-size: 11px; color: var(--muted); margin-top: 4px;">
                            {{ $k->catatan_admin }}
                        </div>
                        @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <p>Belum ada pengajuan koreksi absen</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($koreksis->hasPages())
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        {{ $koreksis->links() }}
    </div>
    @endif
</div>

@endsection