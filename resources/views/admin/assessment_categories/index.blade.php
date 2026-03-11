@extends('layouts.app')

@section('title', 'Kategori Penilaian')
@section('page-title', 'Kategori Penilaian')
@section('page-sub', 'Kelola indikator penilaian karyawan')

@section('content')

    @if (session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">❌ {{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-title-dot"></div>
                Daftar Kategori Penilaian
            </div>
            <button onclick="document.getElementById('modalTambah').style.display='flex'"
                style="display:inline-flex; align-items:center; gap:6px; padding:10px 20px; background:linear-gradient(135deg,#4f7cff,#a855f7); color:white; border:none; border-radius:10px; font-weight:600; cursor:pointer; font-size:0.9rem;">
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
                        <th>Pernyataan</th>
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
                            <td>{{ $cat->description ?? '-' }}</td>
                            <td>
                            <td>
                            <td style="font-weight:600; font-size:0.9rem;">
                                {{ $cat->statements->count() }} pernyataan
                            </td>
                            </td>
                            </td>
                            <td>{{ $cat->max_score }}</td>
                            <td>
                                @if ($cat->is_active)
                                    <span class="badge badge-green"><span class="badge-dot"></span>Aktif</span>
                                @else
                                    <span class="badge badge-red"><span class="badge-dot"></span>Nonaktif</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:6px; align-items:center; flex-wrap:wrap;">
                                    <button
                                        onclick="openStatements({{ $cat->id }}, '{{ $cat->name }}', {{ $cat->statements->toJson() }})"
                                        style="padding:6px 14px; background:#8b5cf6; color:white; border:none; border-radius:8px; cursor:pointer; font-size:0.8rem; font-weight:600;">
                                        Pernyataan
                                    </button>
                                    <button
                                        onclick="openEdit({{ $cat->id }}, '{{ $cat->name }}', '{{ addslashes($cat->description) }}', {{ $cat->max_score }})"
                                        style="padding:6px 14px; background:#4f7cff; color:white; border:none; border-radius:8px; cursor:pointer; font-size:0.8rem; font-weight:600;">
                                        Edit
                                    </button>
                                    <form action="{{ route('admin.assessment-categories.toggle', $cat) }}" method="POST"
                                        style="display:inline;">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            style="padding:6px 14px; background:{{ $cat->is_active ? '#f59e0b' : '#22c55e' }}; color:white; border:none; border-radius:8px; cursor:pointer; font-size:0.8rem; font-weight:600;">
                                            {{ $cat->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.assessment-categories.destroy', $cat) }}" method="POST"
                                        style="display:inline;" onsubmit="return confirm('Yakin hapus kategori ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            style="padding:6px 14px; background:#ef4444; color:white; border:none; border-radius:8px; cursor:pointer; font-size:0.8rem; font-weight:600;">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
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

    {{-- Modal Tambah Kategori --}}
    <div id="modalTambah"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
        <div style="background:#1e1e2e; border-radius:16px; padding:32px; width:100%; max-width:480px;">
            <h3
                style="margin-bottom:20px; font-size:1.2rem; font-weight:800; background:linear-gradient(135deg,#4f7cff,#a855f7); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">
                Tambah Indikator Penilaian
            </h3>
            <form action="{{ route('admin.assessment-categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label style="color:white;">Nama Indikator</label>
                    <input type="text" name="name" class="form-control" placeholder="Cth: Kedisiplinan" required>
                </div>
                <div class="form-group">
                    <label style="color:white;">Deskripsi</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat..."></textarea>
                </div>
                <div class="form-group">
                    <label style="color:white;">Skor Maksimal</label>
                    <input type="number" name="max_score" class="form-control" value="5" min="1" max="10"
                        required>
                </div>
                <input type="hidden" name="type" value="Employee">
                <div style="display:flex; gap:12px; margin-top:20px;">
                    <button type="button" onclick="document.getElementById('modalTambah').style.display='none'"
                        style="flex:1; padding:12px; border:1px solid #555; border-radius:8px; cursor:pointer; background:transparent; color:white;">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary" style="flex:1;">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Kategori --}}
    <div id="modalEdit"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
        <div style="background:#1e1e2e; border-radius:16px; padding:32px; width:100%; max-width:480px;">
            <h3
                style="margin-bottom:20px; font-size:1.2rem; font-weight:800; background:linear-gradient(135deg,#4f7cff,#a855f7); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">
                Edit Indikator Penilaian
            </h3>
            <form id="formEdit" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label style="color:white;">Nama Indikator</label>
                    <input type="text" name="name" id="editName" class="form-control" required>
                </div>
                <div class="form-group">
                    <label style="color:white;">Deskripsi</label>
                    <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label style="color:white;">Skor Maksimal</label>
                    <input type="number" name="max_score" id="editMaxScore" class="form-control" min="1"
                        max="10">
                </div>
                <div style="display:flex; gap:12px; margin-top:20px;">
                    <button type="button" onclick="document.getElementById('modalEdit').style.display='none'"
                        style="flex:1; padding:12px; border:1px solid #555; border-radius:8px; cursor:pointer; background:transparent; color:white;">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary" style="flex:1;">Update</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Kelola Pernyataan --}}
    <div id="modalStatements"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
        <div
            style="background:#1e1e2e; border-radius:16px; padding:32px; width:100%; max-width:600px; max-height:90vh; overflow-y:auto;">
            <h3
                style="margin-bottom:4px; font-size:1.2rem; font-weight:800; background:linear-gradient(135deg,#4f7cff,#a855f7); -webkit-background-clip:text; -webkit-text-fill-color:transparent;">
                Kelola Pernyataan
            </h3>
            <p id="statementCategoryName" style="color:rgba(255,255,255,0.5); font-size:0.85rem; margin-bottom:20px;"></p>

            <form id="formAddStatement" method="POST">
                @csrf
                <div style="display:flex; gap:8px; margin-bottom:20px;">
                    <input type="text" name="statement" id="newStatement" class="form-control"
                        placeholder="Tulis pernyataan baru..." required style="flex:1;">
                    <button type="submit"
                        style="padding:10px 20px; background:linear-gradient(135deg,#4f7cff,#a855f7); color:white; border:none; border-radius:8px; cursor:pointer; font-weight:600; white-space:nowrap;">
                        + Tambah
                    </button>
                </div>
            </form>

            <div id="statementsList" style="display:flex; flex-direction:column; gap:8px;"></div>

            <div style="margin-top:20px; text-align:right;">
                <button type="button" onclick="document.getElementById('modalStatements').style.display='none'"
                    style="padding:10px 24px; border:1px solid #555; border-radius:8px; cursor:pointer; background:transparent; color:white;">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Edit Pernyataan --}}
    <div id="modalEditStatement"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:1000; align-items:center; justify-content:center;">
        <div style="background:#1e1e2e; border-radius:16px; padding:32px; width:100%; max-width:480px;">
            <h3 style="margin-bottom:20px; font-size:1.1rem; font-weight:800; color:white;">Edit Pernyataan</h3>
            <form id="formEditStatement" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label style="color:white;">Pernyataan</label>
                    <textarea name="statement" id="editStatementText" class="form-control" rows="3" required></textarea>
                </div>
                <div style="display:flex; gap:12px; margin-top:20px;">
                    <button type="button"
                        onclick="document.getElementById('modalEditStatement').style.display='none'; document.getElementById('modalStatements').style.display='flex';"
                        style="flex:1; padding:12px; border:1px solid #555; border-radius:8px; cursor:pointer; background:transparent; color:white;">
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
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
            document.getElementById('editMaxScore').value = maxScore;
            document.getElementById('modalEdit').style.display = 'flex';
        }

        function openStatements(categoryId, categoryName, statements) {
            document.getElementById('statementCategoryName').textContent = 'Kategori: ' + categoryName;
            document.getElementById('formAddStatement').action = `/admin/assessment-categories/${categoryId}/statements`;
            document.getElementById('newStatement').value = '';

            const list = document.getElementById('statementsList');
            list.innerHTML = '';

            if (statements.length === 0) {
                list.innerHTML =
                    '<div style="text-align:center; color:rgba(255,255,255,0.4); padding:20px;">Belum ada pernyataan</div>';
            } else {
                statements.forEach((stmt, index) => {
                    list.innerHTML += `
                <div style="display:flex; align-items:center; gap:8px; background:rgba(255,255,255,0.05); border-radius:8px; padding:12px 16px; border:1px solid rgba(255,255,255,0.08);">
                    <span style="color:rgba(255,255,255,0.4); font-size:0.8rem; min-width:20px;">${index + 1}.</span>
                    <span style="flex:1; color:white; font-size:0.9rem;">${stmt.statement}</span>
                    <div style="display:flex; gap:6px;">
                        <button onclick="openEditStatement(${stmt.id}, '${stmt.statement.replace(/'/g, "\\'")}')"
                            style="padding:4px 10px; background:#4f7cff; color:white; border:none; border-radius:6px; cursor:pointer; font-size:0.75rem; font-weight:600;">
                            Edit
                        </button>
                        <button onclick="deleteStatement(${stmt.id})"
                            style="padding:4px 10px; background:#ef4444; color:white; border:none; border-radius:6px; cursor:pointer; font-size:0.75rem; font-weight:600;">
                            Hapus
                        </button>
                    </div>
                </div>`;
                });
            }

            document.getElementById('modalStatements').style.display = 'flex';
        }

        function openEditStatement(id, statement) {
            document.getElementById('formEditStatement').action = `/admin/assessment-statements/${id}`;
            document.getElementById('editStatementText').value = statement;
            document.getElementById('modalStatements').style.display = 'none';
            document.getElementById('modalEditStatement').style.display = 'flex';
        }

        function deleteStatement(id) {
            if (!confirm('Yakin hapus pernyataan ini?')) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/assessment-statements/${id}`;
            form.innerHTML =
                `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endpush
