@extends('layouts.app')

@section('title', 'Data Jabatan')
@section('page-title', 'Data Jabatan')
@section('page-sub', 'Kelola jabatan per divisi')

@section('page-actions')
<a href="{{ route('admin.jabatan.create') }}" class="btn btn-primary">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
    Tambah Jabatan
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
            Daftar Jabatan
        </div>
        <span style="font-size: 12px; color: var(--mid); font-family: var(--mono);">
            Total: {{ $jabatans->total() }} jabatan
        </span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Jabatan</th>
                    <th>Divisi</th>
                    <th>Jumlah Karyawan</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jabatans as $j)
                <tr>
                    <td style="font-family: var(--mono); color: var(--mid);">{{ $loop->iteration }}</td>
                    <td style="font-weight: 600; color: var(--white);">{{ $j->nama_jabatan }}</td>
                    <td><span class="badge badge-blue">{{ $j->divisi->nama_divisi }}</span></td>
                    <td><span class="badge badge-green">{{ $j->karyawans_count }} karyawan</span></td>
                    <td style="font-family: var(--mono); font-size: 12px; color: var(--mid);">
                        {{ $j->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('admin.jabatan.edit', $j) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.jabatan.destroy', $j) }}"
                                onsubmit="return confirm('Hapus jabatan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <p>Belum ada data jabatan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($jabatans->hasPages())
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        {{ $jabatans->links() }}
    </div>
    @endif
</div>

@endsection