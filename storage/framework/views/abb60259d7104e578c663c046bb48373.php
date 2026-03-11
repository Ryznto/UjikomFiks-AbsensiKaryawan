

<?php $__env->startSection('title', 'Kategori Penilaian'); ?>
<?php $__env->startSection('page-title', 'Kategori Penilaian'); ?>
<?php $__env->startSection('page-sub', 'Kelola indikator penilaian karyawan'); ?>

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
                <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td><strong><?php echo e($cat->name); ?></strong></td>
                    <td style="color: var(--mid)"><?php echo e($cat->description ?? '-'); ?></td>
                    <td><?php echo e($cat->max_score); ?></td>
                    <td>
                        <?php if($cat->is_active): ?>
                            <span class="badge badge-green"><span class="badge-dot"></span>Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-red"><span class="badge-dot"></span>Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            
                            <button onclick="openEdit(<?php echo e($cat->id); ?>, '<?php echo e($cat->name); ?>', '<?php echo e(addslashes($cat->description)); ?>', <?php echo e($cat->max_score); ?>)"
                                class="btn-icon" title="Edit">✏️</button>

                            
                            <form action="<?php echo e(route('admin.assessment-categories.toggle', $cat)); ?>" method="POST" style="display:inline;">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn-icon" title="<?php echo e($cat->is_active ? 'Nonaktifkan' : 'Aktifkan'); ?>">
                                    <?php echo e($cat->is_active ? '🔴' : '🟢'); ?>

                                </button>
                            </form>

                            
                            <form action="<?php echo e(route('admin.assessment-categories.destroy', $cat)); ?>" method="POST" style="display:inline;"
                                onsubmit="return confirm('Yakin hapus kategori ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-icon" title="Hapus">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <p>Belum ada kategori penilaian</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


<div id="modalTambah" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:16px; padding:32px; width:100%; max-width:480px;">
        <h3 style="margin-bottom:20px;">+ Tambah Indikator Penilaian</h3>
        <form action="<?php echo e(route('admin.assessment-categories.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
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


<div id="modalEdit" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
    <div style="background:#1e1e2e; border-radius:16px; padding:32px; width:100%; max-width:480px;">
        <h3 style="margin-bottom:20px;">✏️ Edit Indikator Penilaian</h3>
        <form id="formEdit" method="POST">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function openEdit(id, name, description, maxScore) {
        document.getElementById('formEdit').action = `/admin/assessment-categories/${id}`;
        document.getElementById('editName').value        = name;
        document.getElementById('editDescription').value = description;
        document.getElementById('editMaxScore').value    = maxScore;
        document.getElementById('modalEdit').style.display = 'flex';
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/assessment_categories/index.blade.php ENDPATH**/ ?>