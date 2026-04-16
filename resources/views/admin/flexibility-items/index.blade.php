@extends('layouts.app')

@section('title', 'Flexibility Marketplace')
@section('page-title', 'Flexibility Marketplace')
@section('page-sub', 'Kelola katalog token kelonggaran karyawan')

@section('page-actions')
<button onclick="document.getElementById('modalTambah').style.display='flex'" class="btn btn-primary">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
    Tambah Item
</button>
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
            Katalog Item Kelonggaran
        </div>
        <span style="font-size: 12px; color: var(--mid); font-family: var(--mono);">
            Total: {{ $items->total() }} item
        </span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Item</th>
                    <th>Tipe Token</th>
                    <th>Harga Poin</th>
                    <th>Toleransi</th>
                    <th>Stok/Bulan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td style="font-family: var(--mono); color: var(--mid);">{{ $loop->iteration }}</td>
                    <td style="font-weight: 600; color: var(--white);">
                        {{ $item->item_name }}
                        @if($item->description)
                        <div style="font-size:11px; color:var(--mid); font-weight:400;">{{ $item->description }}</div>
                        @endif
                    </td>
                    <td>
                        @if($item->token_type === 'late_tolerance')
                            <span class="badge badge-blue">Toleransi Telat</span>
                        @elseif($item->token_type === 'izin_tanpa_surat')
                            <span class="badge badge-orange">Izin Tanpa Surat</span>
                        @else
                            <span class="badge badge-green">WFH</span>
                        @endif
                    </td>
                    <td><span class="badge badge-blue">{{ $item->point_cost }} poin</span></td>
                    <td style="font-family: var(--mono);">
                        {{ $item->tolerance_minutes ? $item->tolerance_minutes . ' menit' : '-' }}
                    </td>
                    <td style="font-family: var(--mono);">
                        {{ $item->stock_limit ? $item->stock_limit . 'x/bulan' : 'Unlimited' }}
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.flexibility-items.toggle', $item) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="badge {{ $item->is_active ? 'badge-green' : 'badge-red' }}" style="border:none;cursor:pointer;">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <button onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->item_name) }}', '{{ addslashes($item->description) }}', {{ $item->point_cost }}, '{{ $item->token_type }}', {{ $item->tolerance_minutes ?? 'null' }}, {{ $item->stock_limit ?? 'null' }})"
                                class="btn btn-secondary btn-sm">Edit</button>
                            <form method="POST" action="{{ route('admin.flexibility-items.destroy', $item) }}"
                                onsubmit="return confirm('Hapus item ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state"><p>Belum ada item marketplace</p></div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        {{ $items->links() }}
    </div>
    @endif
</div>

{{-- Modal Tambah --}}
<div id="modalTambah" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:999; align-items:center; justify-content:center;">
    <div class="card" style="width:100%; max-width:480px; margin:0 16px; max-height:90vh; overflow-y:auto;">
        <div class="card-header">
            <div class="card-title"><div class="card-title-dot"></div>Tambah Item Baru</div>
            <button onclick="document.getElementById('modalTambah').style.display='none'" style="background:none;border:none;color:var(--mid);cursor:pointer;font-size:20px;">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.flexibility-items.store') }}" style="padding: 20px; display:flex; flex-direction:column; gap:14px;">
            @csrf
            <div>
                <label style="font-size:12px; color:var(--mid);">Nama Item</label>
                <input type="text" name="item_name" class="form-control" placeholder="cth: Kompensasi Telat 30 Menit" required>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Deskripsi (opsional)</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi singkat item..."></textarea>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Tipe Token</label>
                <select name="token_type" class="form-control" required>
                    <option value="late_tolerance">Toleransi Telat</option>
                    <option value="izin_tanpa_surat">Izin Tanpa Surat</option>
                    <option value="wfh">WFH 1 Hari</option>
                </select>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Harga Poin</label>
                <input type="number" name="point_cost" class="form-control" placeholder="cth: 20" min="1" required>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Toleransi Menit (khusus token telat)</label>
                <input type="number" name="tolerance_minutes" class="form-control" placeholder="cth: 30">
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Batas Pembelian per Bulan (kosong = unlimited)</label>
                <input type="number" name="stock_limit" class="form-control" placeholder="cth: 2">
            </div>
            <button type="submit" class="btn btn-primary">Simpan Item</button>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modalEdit" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:999; align-items:center; justify-content:center;">
    <div class="card" style="width:100%; max-width:480px; margin:0 16px; max-height:90vh; overflow-y:auto;">
        <div class="card-header">
            <div class="card-title"><div class="card-title-dot"></div>Edit Item</div>
            <button onclick="document.getElementById('modalEdit').style.display='none'" style="background:none;border:none;color:var(--mid);cursor:pointer;font-size:20px;">✕</button>
        </div>
        <form method="POST" id="formEdit" style="padding: 20px; display:flex; flex-direction:column; gap:14px;">
            @csrf @method('PUT')
            <div>
                <label style="font-size:12px; color:var(--mid);">Nama Item</label>
                <input type="text" name="item_name" id="editItemName" class="form-control" required>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Deskripsi</label>
                <textarea name="description" id="editDescription" class="form-control" rows="2"></textarea>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Tipe Token</label>
                <select name="token_type" id="editTokenType" class="form-control" required>
                    <option value="late_tolerance">Toleransi Telat</option>
                    <option value="izin_tanpa_surat">Izin Tanpa Surat</option>
                    <option value="wfh">WFH 1 Hari</option>
                </select>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Harga Poin</label>
                <input type="number" name="point_cost" id="editPointCost" class="form-control" required>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Toleransi Menit</label>
                <input type="number" name="tolerance_minutes" id="editToleranceMinutes" class="form-control">
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Batas Pembelian per Bulan</label>
                <input type="number" name="stock_limit" id="editStockLimit" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Update Item</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openEditModal(id, name, desc, cost, type, tolerance, stock) {
    const base = "{{ url('admin/flexibility-items') }}/" + id;
    document.getElementById('formEdit').action = base;
    document.getElementById('editItemName').value = name;
    document.getElementById('editDescription').value = desc;
    document.getElementById('editTokenType').value = type;
    document.getElementById('editPointCost').value = cost;
    document.getElementById('editToleranceMinutes').value = tolerance || '';
    document.getElementById('editStockLimit').value = stock || '';
    document.getElementById('modalEdit').style.display = 'flex';
}
</script>
@endpush