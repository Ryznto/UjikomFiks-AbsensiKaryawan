

<?php $__env->startSection('title', 'Tambah Divisi'); ?>
<?php $__env->startSection('page-title', 'Tambah Divisi'); ?>
<?php $__env->startSection('page-sub', 'Buat divisi baru'); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('admin.divisi.index')); ?>" class="btn btn-secondary">← Kembali</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="max-width: 520px;">
    <div class="card-header">
        <div class="card-title">Form Tambah Divisi</div>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.divisi.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label class="form-label">Nama Divisi <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama_divisi" class="form-control"
                    value="<?php echo e(old('nama_divisi')); ?>" placeholder="Contoh: Engineering" autofocus>
                <?php $__errorArgs = ['nama_divisi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="<?php echo e(route('admin.divisi.index')); ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/divisi/create.blade.php ENDPATH**/ ?>