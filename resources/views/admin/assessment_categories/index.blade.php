@extends('layouts.app')

@section('title', 'Kategori Penilaian')
@section('page-title', 'Kategori Penilaian')
@section('page-sub', 'Kelola indikator penilaian karyawan')

@section('content')

{{-- Alert --}}
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
            Daftar Kategori Penilaian
        </div>
        <button onclick="document.getElementById('modalTambah').style.display='flex'" class="btn-primary">
            + Tambah Indikator
        </button>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Indikator</th>
                    <th>Deskripsi</th>
                    <th>Maks Skor</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $cat)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $cat->name }}</strong></td>
                    <td style="color: var(--mid)">{{ $cat->description ?? '-' }}</td>
                    <td>{{ $cat->max_score }}</td>
                    <td>
                        @if($cat->is_active)
                            <span class="badge badge-green"><span class="badge-dot"></span>Aktif</span>
                        @else
                            <span class="badge badge-red"><span class="badge-dot"></span>Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            {{-- Edit --}}
                            <button onclick="openEdit({{ $cat->id }}, '{{ $cat->name }}', '{{ addslashes($cat->description) }}', {{ $cat->max_score }})"
                                class="btn-icon" title="Edit">✏️</button>

                            {{-- Toggle Status --}}
                            <form action="{{ route('admin.assessment-categories.toggle', $cat) }}" method="POST" style="display:inline;">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-icon" title="{{ $cat->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                    {{ $cat->is_active ? '🔴' : '🟢' }}
                                </button>
                            </form>

                            {{-- Hapus --}}
                            <form action="{{ route('admin.assessment-categories.destroy', $cat) }}" method="POST" style="display:inline;"
                                onsubmit="return confirm('Yakin hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon" title="Hapus">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <p>Belum ada kategori penilaian</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah --}}
<div id="modalTambah" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:32px; width:100%; max-width:480px;">
        <h3 style="margin-bottom:20px;">+ Tambah Indikator Penilaian</h3>
        <form action="{{ route('admin.assessment-categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Indikator</label>
                <input type="text" name="name" class="form-control" placeholder="Cth: Kedisiplinan" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat..."></textarea>
            </div>
            <div class="form-group">
                <label>Skor Maksimal</label>
                <input type="number" name="max_score" class="form-control" value="5" min="1" max="10" required>
            </div>
            <input type="hidden" name="type" value="Employee">
            <div style="display:flex; gap:12px; margin-top:20px;">
                <button type="button" onclick="document.getElementById('modalTambah').style.display='none'"
                    style="flex:1; padding:12px; border:1px solid #ddd; border-radius:8px; cursor:pointer; background:white;">
                    Batal
                </button>
                <button type="submit" class="btn-primary" style="flex:1;">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modalEdit" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:32px; width:100%; max-width:480px;">
        <h3 style="margin-bottom:20px;">✏️ Edit Indikator Penilaian</h3>
        <form id="formEdit" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Nama Indikator</label>
                <input type="text" name="name" id="editName" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Skor Maksimal</label>
                <input type="number" name="max_score" id="editMaxScore" class="form-control" min="1" max="10">
            </div>
            <div style="display:flex; gap:12px; margin-top:20px;">
                <button type="button" onclick="document.getElementById('modalEdit').style.display='none'"
                    style="flex:1; padding:12px; border:1px solid #ddd; border-radius:8px; cursor:pointer; background:white;">
                    Batal
                </button>
                <button type="submit" class="btn-primary" style="flex:1;">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openEdit(id, name, description, maxScore) {
        document.getElementById('formEdit').action = `/admin/assessment-categories/${id}`;
        document.getElementById('editName').value        = name;
        document.getElementById('editDescription').value = description;
        document.getElementById('editMaxScore').value    = maxScore;
        document.getElementById('modalEdit').style.display = 'flex';
    }
</script>
@endpush