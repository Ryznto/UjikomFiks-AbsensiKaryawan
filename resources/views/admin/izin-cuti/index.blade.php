@extends('layouts.app')

@section('title', 'Izin & Cuti')
@section('page-title', 'Izin & Cuti')
@section('page-sub', 'Kelola pengajuan izin dan cuti karyawan')

@section('content')

<div class="stats-grid" style="grid-template-columns: repeat(3,1fr); margin-bottom: 24px;">
    <div class="stat-card amber">
        <div class="stat-icon">⏳</div>
        <div class="stat-value">{{ $totalPending }}</div>
        <div class="stat-label">Menunggu</div>
        <div class="stat-sub">Perlu diproses</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">✅</div>
        <div class="stat-value">{{ $totalApproved }}</div>
        <div class="stat-label">Disetujui</div>
        <div class="stat-sub">Total approved</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon">❌</div>
        <div class="stat-value">{{ $totalRejected }}</div>
        <div class="stat-label">Ditolak</div>
        <div class="stat-sub">Total rejected</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Daftar Pengajuan
        </div>
        @if($totalPending > 0)
        <span class="badge badge-amber">{{ $totalPending }} pending</span>
        @endif
    </div>

    {{-- Filter --}}
    <div style="padding: 16px 22px; border-bottom: 1px solid var(--border);">
        <form method="GET" action="{{ route('admin.izin-cuti.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-control" style="width: 160px;">
                    <option value="">Semua Status</option>
                    <option value="pending"  {{ request('status') == 'pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div>
                <label class="form-label">Jenis</label>
                <select name="jenis" class="form-control" style="width: 160px;">
                    <option value="">Semua Jenis</option>
                    <option value="izin"  {{ request('jenis') == 'izin'  ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('jenis') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="cuti"  {{ request('jenis') == 'cuti'  ? 'selected' : '' }}>Cuti</option>
                </select>
            </div>
            <div>
                <label class="form-label">Divisi</label>
                <select name="divisi_id" class="form-control" style="width: 160px;">
                    <option value="">Semua Divisi</option>
                    @foreach($divisis as $d)
                    <option value="{{ $d->id }}" {{ request('divisi_id') == $d->id ? 'selected' : '' }}>
                        {{ $d->nama_divisi }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control"
                    value="{{ request('tanggal') }}" style="width: 170px;">
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.izin-cuti.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Divisi</th>
                    <th>Jenis</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Durasi</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($izinCutis as $ic)
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background: linear-gradient(135deg, #4f7cff, #a855f7)">
                                {{ strtoupper(substr($ic->karyawan->nama, 0, 2)) }}
                            </div>
                            <div>
                                <div class="emp-name">{{ $ic->karyawan->nama }}</div>
                                <div class="emp-sub">{{ $ic->karyawan->user->nip }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $ic->karyawan->divisi->nama_divisi }}</td>
                    <td>
                        @php
                            $jenisMap = [
                                'izin'  => ['class' => 'badge-blue',  'label' => '📋 Izin'],
                                'sakit' => ['class' => 'badge-red',   'label' => '🏥 Sakit'],
                                'cuti'  => ['class' => 'badge-green', 'label' => '🏖️ Cuti'],
                            ];
                            $j = $jenisMap[$ic->jenis] ?? ['class' => 'badge-gray', 'label' => $ic->jenis];
                        @endphp
                        <span class="badge {{ $j['class'] }}">{{ $j['label'] }}</span>
                    </td>
                    <td style="font-family: var(--mono); font-size: 12px;">
                        {{ \Carbon\Carbon::parse($ic->tanggal_mulai)->format('d M Y') }}
                    </td>
                    <td style="font-family: var(--mono); font-size: 12px;">
                        {{ \Carbon\Carbon::parse($ic->tanggal_selesai)->format('d M Y') }}
                    </td>
                    <td>
                        @php
                            $durasi = \Carbon\Carbon::parse($ic->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($ic->tanggal_selesai)) + 1;
                        @endphp
                        <span class="badge badge-gray">{{ $durasi }} hari</span>
                    </td>
                    <td style="max-width: 200px; font-size: 12px; color: var(--muted);">
                        {{ $ic->keterangan ? Str::limit($ic->keterangan, 40) : '—' }}
                    </td>
                    <td>
                        @if($ic->status === 'pending')
                            <span class="badge badge-amber"><span class="badge-dot"></span>Pending</span>
                        @elseif($ic->status === 'approved')
                            <span class="badge badge-green"><span class="badge-dot"></span>Approved</span>
                        @else
                            <span class="badge badge-red"><span class="badge-dot"></span>Rejected</span>
                        @endif
                    </td>
                    <td>
                        @if($ic->status === 'pending')
                        <div style="display: flex; gap: 6px;">
                            <form method="POST" action="{{ route('admin.izin-cuti.approve', $ic) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">✓ Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.izin-cuti.reject', $ic) }}"
                                onsubmit="return confirm('Tolak pengajuan ini?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-sm">✕ Reject</button>
                            </form>
                        </div>
                        @else
                            <div style="font-size: 11px; color: var(--mid); font-family: var(--mono);">
                                {{ $ic->approvedBy->adminProfile->nama_admin ?? '-' }}<br>
                                {{ $ic->approved_at ? \Carbon\Carbon::parse($ic->approved_at)->format('d M Y') : '' }}
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <p>Belum ada pengajuan izin atau cuti</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($izinCutis->hasPages())
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        {{ $izinCutis->links() }}
    </div>
    @endif
</div>

@endsection