@extends('layouts.app')

@section('title', 'Data Karyawan')
@section('page-title', 'Data Karyawan')
@section('page-sub', 'Kelola data seluruh karyawan')

@section('page-actions')
<a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
    Tambah Karyawan
</a>
@endsection

@section('content')

@if(session('success'))
<div class="alert alert-success">✅ {{ session('success') }}</div>
@endif

@if(session('show_password'))
<div class="alert alert-success" style="flex-direction: column; align-items: flex-start; gap: 6px;">
    <strong>🔑 Kredensial Login Karyawan</strong>
    <span>NIP: <strong>{{ session('generated_nip') }}</strong></span>
    <span>Password: <strong style="font-family: var(--mono); font-size: 15px;">{{ session('generated_password') }}</strong></span>
    <small style="color: var(--mid);">Catat password ini sekarang, tidak akan ditampilkan lagi.</small>
</div>
@endif

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Daftar Karyawan
        </div>
        <span style="font-size: 12px; color: var(--mid); font-family: var(--mono);">
            Total: {{ $karyawans->total() }} karyawan
        </span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>NIP</th>
                    <th>Divisi</th>
                    <th>Jabatan</th>
                    <th>Shift</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawans as $k)
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background: linear-gradient(135deg, #4f7cff, #a855f7)">
                                {{ strtoupper(substr($k->nama, 0, 2)) }}
                            </div>
                            <div>
                                <div class="emp-name">{{ $k->nama }}</div>
                                <div class="emp-sub">{{ $k->no_hp ?? '-' }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-family: var(--mono); font-size: 12px;">{{ $k->user->nip }}</td>
                    <td>{{ $k->divisi->nama_divisi }}</td>
                    <td>{{ $k->jabatan->nama_jabatan }}</td>
                    <td><span class="badge badge-blue">{{ $k->shift->nama_shift }}</span></td>
                    <td>
                        @if($k->status_aktif)
                            <span class="badge badge-green"><span class="badge-dot"></span>Aktif</span>
                        @else
                            <span class="badge badge-red"><span class="badge-dot"></span>Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('admin.karyawan.show', $k) }}" class="btn btn-secondary btn-sm">Detail</a>
                            <a href="{{ route('admin.karyawan.edit', $k) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.karyawan.destroy', $k) }}"
                                onsubmit="return confirm('Hapus karyawan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <p>Belum ada data karyawan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($karyawans->hasPages())
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        {{ $karyawans->links() }}
    </div>
    @endif
</div>

@endsection