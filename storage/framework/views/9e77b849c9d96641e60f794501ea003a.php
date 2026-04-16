

<?php $__env->startSection('title', 'Flexibility Marketplace'); ?>
<?php $__env->startSection('page-title', 'Flexibility Marketplace'); ?>
<?php $__env->startSection('page-sub', 'Kelola katalog token kelonggaran karyawan'); ?>

<?php $__env->startSection('page-actions'); ?>
<button onclick="document.getElementById('modalTambah').style.display='flex'" class="btn btn-primary">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
    Tambah Item
</button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
<?php endif; ?>
<?php if(session('error')): ?>
<div class="alert alert-danger">❌ <?php echo e(session('error')); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Katalog Item Kelonggaran
        </div>
        <span style="font-size: 12px; color: var(--mid); font-family: var(--mono);">
            Total: <?php echo e($items->total()); ?> item
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
                <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-family: var(--mono); color: var(--mid);"><?php echo e($loop->iteration); ?></td>
                    <td style="font-weight: 600; color: var(--white);">
                        <?php echo e($item->item_name); ?>

                        <?php if($item->description): ?>
                        <div style="font-size:11px; color:var(--mid); font-weight:400;"><?php echo e($item->description); ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($item->token_type === 'late_tolerance'): ?>
                            <span class="badge badge-blue">Toleransi Telat</span>
                        <?php elseif($item->token_type === 'izin_tanpa_surat'): ?>
                            <span class="badge badge-orange">Izin Tanpa Surat</span>
                        <?php else: ?>
                            <span class="badge badge-green">WFH</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge badge-blue"><?php echo e($item->point_cost); ?> poin</span></td>
                    <td style="font-family: var(--mono);">
                        <?php echo e($item->tolerance_minutes ? $item->tolerance_minutes . ' menit' : '-'); ?>

                    </td>
                    <td style="font-family: var(--mono);">
                        <?php echo e($item->stock_limit ? $item->stock_limit . 'x/bulan' : 'Unlimited'); ?>

                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('admin.flexibility-items.toggle', $item)); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="badge <?php echo e($item->is_active ? 'badge-green' : 'badge-red'); ?>" style="border:none;cursor:pointer;">
                                <?php echo e($item->is_active ? 'Aktif' : 'Nonaktif'); ?>

                            </button>
                        </form>
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <button onclick="openEditModal(<?php echo e($item->id); ?>, '<?php echo e(addslashes($item->item_name)); ?>', '<?php echo e(addslashes($item->description)); ?>', <?php echo e($item->point_cost); ?>, '<?php echo e($item->token_type); ?>', <?php echo e($item->tolerance_minutes ?? 'null'); ?>, <?php echo e($item->stock_limit ?? 'null'); ?>)"
                                class="btn btn-secondary btn-sm">Edit</button>
                            <form method="POST" action="<?php echo e(route('admin.flexibility-items.destroy', $item)); ?>"
                                onsubmit="return confirm('Hapus item ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state"><p>Belum ada item marketplace</p></div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($items->hasPages()): ?>
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        <?php echo e($items->links()); ?>

    </div>
    <?php endif; ?>
</div>


<div id="modalTambah" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:999; align-items:center; justify-content:center;">
    <div class="card" style="width:100%; max-width:480px; margin:0 16px; max-height:90vh; overflow-y:auto;">
        <div class="card-header">
            <div class="card-title"><div class="card-title-dot"></div>Tambah Item Baru</div>
            <button onclick="document.getElementById('modalTambah').style.display='none'" style="background:none;border:none;color:var(--mid);cursor:pointer;font-size:20px;">✕</button>
        </div>
        <form method="POST" action="<?php echo e(route('admin.flexibility-items.store')); ?>" style="padding: 20px; display:flex; flex-direction:column; gap:14px;">
            <?php echo csrf_field(); ?>
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


<div id="modalEdit" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:999; align-items:center; justify-content:center;">
    <div class="card" style="width:100%; max-width:480px; margin:0 16px; max-height:90vh; overflow-y:auto;">
        <div class="card-header">
            <div class="card-title"><div class="card-title-dot"></div>Edit Item</div>
            <button onclick="document.getElementById('modalEdit').style.display='none'" style="background:none;border:none;color:var(--mid);cursor:pointer;font-size:20px;">✕</button>
        </div>
        <form method="POST" id="formEdit" style="padding: 20px; display:flex; flex-direction:column; gap:14px;">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function openEditModal(id, name, desc, cost, type, tolerance, stock) {
    const base = "<?php echo e(url('admin/flexibility-items')); ?>/" + id;
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/flexibility-items/index.blade.php ENDPATH**/ ?>