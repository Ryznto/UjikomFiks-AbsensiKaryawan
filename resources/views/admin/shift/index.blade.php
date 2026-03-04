@extends('layouts.app')

@section('title', 'Data Shift')
@section('page-title', 'Data Shift')
@section('page-sub', 'Kelola jadwal shift karyawan')

@section('page-actions')
<a href="{{ route('admin.shift.create') }}" class="btn btn-primary">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
    Tambah Shift
</a>
@endsection

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
            Daftar Shift
        </div>
        <span style="font-size: 12px; color: var(--mid); font-family: var(--mono);">
            Total: {{ $shifts->total() }} shift
        </span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Shift</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Toleransi</th>
                    <th>Karyawan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shifts as $s)
                <tr>
                    <td style="font-family: var(--mono); color: var(--mid);">{{ $loop->iteration }}</td>
                    <td style="font-weight: 600; color: var(--white);">{{ $s->nama_shift }}</td>
                    <td style="font-family: var(--mono); color: var(--green);">{{ $s->jam_masuk }}</td>
                    <td style="font-family: var(--mono); color: var(--amber);">{{ $s->jam_pulang }}</td>
                    <td><span class="badge badge-gray">{{ $s->toleransi_terlambat }} menit</span></td>
                    <td><span class="badge badge-blue">{{ $s->karyawans_count }} karyawan</span></td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('admin.shift.edit', $s) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.shift.destroy', $s) }}"
                                onsubmit="return confirm('Hapus shift ini?')">
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
                            <p>Belum ada data shift</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($shifts->hasPages())
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        {{ $shifts->links() }}
    </div>
    @endif
</div>

@endsection