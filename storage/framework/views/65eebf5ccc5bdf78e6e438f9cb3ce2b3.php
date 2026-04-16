

<?php $__env->startSection('title', 'Point Rules'); ?>
<?php $__env->startSection('page-title', 'Point Rules'); ?>
<?php $__env->startSection('page-sub', 'Kelola aturan poin dinamis karyawan'); ?>

<?php $__env->startSection('page-actions'); ?>
<button onclick="document.getElementById('modalTambah').style.display='flex'" class="btn btn-primary">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
    Tambah Rule
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
            Daftar Rule Poin
        </div>
        <span style="font-size: 12px; color: var(--mid); font-family: var(--mono);">
            Total: <?php echo e($rules->total()); ?> rule
        </span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Rule</th>
                    <th>Tipe Kondisi</th>
                    <th>Operator</th>
                    <th>Nilai</th>
                    <th>Poin</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $rules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-family: var(--mono); color: var(--mid);"><?php echo e($loop->iteration); ?></td>
                    <td style="font-weight: 600; color: var(--white);"><?php echo e($rule->rule_name); ?></td>
                    <td>
                        <?php if($rule->condition_type === 'check_in'): ?>
                            <span class="badge badge-blue">Jam Masuk</span>
                        <?php elseif($rule->condition_type === 'late_minutes'): ?>
                            <span class="badge badge-orange">Menit Telat</span>
                        <?php else: ?>
                            <span class="badge badge-red">Alfa</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-family: var(--mono);"><?php echo e($rule->condition_operator); ?></td>
                    <td style="font-family: var(--mono);">
                        <?php echo e($rule->condition_value); ?>

                        <?php if($rule->condition_operator === 'BETWEEN'): ?>
                            — <?php echo e($rule->condition_value_max); ?>

                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($rule->point_modifier > 0): ?>
                            <span class="badge badge-green">+<?php echo e($rule->point_modifier); ?> poin</span>
                        <?php else: ?>
                            <span class="badge badge-red"><?php echo e($rule->point_modifier); ?> poin</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('admin.point-rules.toggle', $rule)); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <button type="submit" class="badge <?php echo e($rule->is_active ? 'badge-green' : 'badge-red'); ?>" style="border:none;cursor:pointer;">
                                <?php echo e($rule->is_active ? 'Aktif' : 'Nonaktif'); ?>

                            </button>
                        </form>
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <button onclick="openEditModal(<?php echo e($rule->id); ?>, '<?php echo e($rule->rule_name); ?>', '<?php echo e($rule->condition_type); ?>', '<?php echo e($rule->condition_operator); ?>', '<?php echo e($rule->condition_value); ?>', '<?php echo e($rule->condition_value_max); ?>', <?php echo e($rule->point_modifier); ?>)"
                                class="btn btn-secondary btn-sm">Edit</button>
                            <form method="POST" action="<?php echo e(route('admin.point-rules.destroy', $rule)); ?>"
                                onsubmit="return confirm('Hapus rule ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state"><p>Belum ada rule poin</p></div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($rules->hasPages()): ?>
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        <?php echo e($rules->links()); ?>

    </div>
    <?php endif; ?>
</div>


<div id="modalTambah" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:999; align-items:center; justify-content:center;">
    <div class="card" style="width:100%; max-width:480px; margin:0 16px;">
        <div class="card-header">
            <div class="card-title"><div class="card-title-dot"></div>Tambah Rule Baru</div>
            <button onclick="document.getElementById('modalTambah').style.display='none'" style="background:none;border:none;color:var(--mid);cursor:pointer;font-size:20px;">✕</button>
        </div>
        <form method="POST" action="<?php echo e(route('admin.point-rules.store')); ?>" style="padding: 20px; display:flex; flex-direction:column; gap:14px;">
            <?php echo csrf_field(); ?>
            <div>
                <label style="font-size:12px; color:var(--mid);">Nama Rule</label>
                <input type="text" name="rule_name" class="form-control" placeholder="cth: Datang Pagi Banget" required>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Tipe Kondisi</label>
                <select name="condition_type" class="form-control" required>
                    <option value="check_in">Jam Masuk (check_in)</option>
                    <option value="late_minutes">Menit Terlambat (late_minutes)</option>
                    <option value="alfa">Alfa / Tidak Hadir</option>
                </select>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Operator</label>
                <select name="condition_operator" class="form-control" required>
                    <option value="<">Kurang dari ( < )</option>
                    <option value=">">Lebih dari ( > )</option>
                    <option value="BETWEEN">Di antara (BETWEEN)</option>
                </select>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Nilai Kondisi (jam: 07:00:00 / menit: 15)</label>
                <input type="text" name="condition_value" class="form-control" placeholder="cth: 07:00:00 atau 15" required>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Nilai Maks (khusus BETWEEN)</label>
                <input type="text" name="condition_value_max" class="form-control" placeholder="cth: 08:00:00 atau 30">
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Poin Modifier (+5 atau -3)</label>
                <input type="number" name="point_modifier" class="form-control" placeholder="cth: 5 atau -3" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Rule</button>
        </form>
    </div>
</div>


<div id="modalEdit" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:999; align-items:center; justify-content:center;">
    <div class="card" style="width:100%; max-width:480px; margin:0 16px;">
        <div class="card-header">
            <div class="card-title"><div class="card-title-dot"></div>Edit Rule</div>
            <button onclick="document.getElementById('modalEdit').style.display='none'" style="background:none;border:none;color:var(--mid);cursor:pointer;font-size:20px;">✕</button>
        </div>
        <form method="POST" id="formEdit" style="padding: 20px; display:flex; flex-direction:column; gap:14px;">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <div>
                <label style="font-size:12px; color:var(--mid);">Nama Rule</label>
                <input type="text" name="rule_name" id="editRuleName" class="form-control" required>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Tipe Kondisi</label>
                <select name="condition_type" id="editConditionType" class="form-control" required>
                    <option value="check_in">Jam Masuk (check_in)</option>
                    <option value="late_minutes">Menit Terlambat (late_minutes)</option>
                    <option value="alfa">Alfa / Tidak Hadir</option>
                </select>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Operator</label>
                <select name="condition_operator" id="editOperator" class="form-control" required>
                    <option value="<">Kurang dari ( < )</option>
                    <option value=">">Lebih dari ( > )</option>
                    <option value="BETWEEN">Di antara (BETWEEN)</option>
                </select>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Nilai Kondisi</label>
                <input type="text" name="condition_value" id="editConditionValue" class="form-control" required>
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Nilai Maks (khusus BETWEEN)</label>
                <input type="text" name="condition_value_max" id="editConditionValueMax" class="form-control">
            </div>
            <div>
                <label style="font-size:12px; color:var(--mid);">Poin Modifier</label>
                <input type="number" name="point_modifier" id="editPointModifier" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Rule</button>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function openEditModal(id, name, type, operator, value, valueMax, modifier) {
    const base = "<?php echo e(url('admin/point-rules')); ?>/" + id;
    document.getElementById('formEdit').action = base;
    document.getElementById('editRuleName').value = name;
    document.getElementById('editConditionType').value = type;
    document.getElementById('editOperator').value = operator;
    document.getElementById('editConditionValue').value = value;
    document.getElementById('editConditionValueMax').value = valueMax || '';
    document.getElementById('editPointModifier').value = modifier;
    document.getElementById('modalEdit').style.display = 'flex';
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/point-rules/index.blade.php ENDPATH**/ ?>