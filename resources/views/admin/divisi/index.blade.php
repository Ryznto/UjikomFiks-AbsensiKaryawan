@extends('layouts.app')

@section('title', 'Data Divisi')
@section('page-title', 'Data Divisi')
@section('page-sub', 'Kelola divisi perusahaan')

@section('page-actions')
<a href="{{ route('admin.divisi.create') }}" class="btn btn-primary">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
    Tambah Divisi
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
            Daftar Divisi
        </div>
        <span style="font-size: 12px; color: var(--mid); font-family: var(--mono);">
            Total: {{ $divisis->total() }} divisi
        </span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Divisi</th>
                    <th>Jumlah Jabatan</th>
                    <th>Jumlah Karyawan</th>
                    <th>Dibuat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($divisis as $d)
                <tr>
                    <td style="font-family: var(--mono); color: var(--mid);">{{ $loop->iteration }}</td>
                    <td style="font-weight: 600; color: var(--white);">{{ $d->nama_divisi }}</td>
                    <td><span class="badge badge-blue">{{ $d->jabatans_count }} jabatan</span></td>
                    <td><span class="badge badge-green">{{ $d->karyawans_count }} karyawan</span></td>
                    <td style="font-family: var(--mono); font-size: 12px; color: var(--mid);">
                        {{ $d->created_at->format('d M Y') }}
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="{{ route('admin.divisi.edit', $d) }}" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="{{ route('admin.divisi.destroy', $d) }}"
                                onsubmit="return confirm('Hapus divisi ini?')">
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
                            <p>Belum ada data divisi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($divisis->hasPages())
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        {{ $divisis->links() }}
    </div>
    @endif
</div>

@endsection