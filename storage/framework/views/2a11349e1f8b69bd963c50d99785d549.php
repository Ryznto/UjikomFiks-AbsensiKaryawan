

<?php $__env->startSection('title', 'Tambah Shift'); ?>
<?php $__env->startSection('page-title', 'Tambah Shift'); ?>
<?php $__env->startSection('page-sub', 'Buat jadwal shift baru'); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('admin.shift.index')); ?>" class="btn btn-secondary">← Kembali</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="max-width: 520px;">
    <div class="card-header">
        <div class="card-title">Form Tambah Shift</div>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.shift.store')); ?>">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <label class="form-label">Nama Shift <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama_shift" class="form-control"
                    value="<?php echo e(old('nama_shift')); ?>" placeholder="Contoh: Shift Pagi" autofocus>
                <?php $__errorArgs = ['nama_shift'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">Jam Masuk <span style="color:var(--red)">*</span></label>
                    <input type="time" name="jam_masuk" class="form-control" value="<?php echo e(old('jam_masuk')); ?>">
                    <?php $__errorArgs = ['jam_masuk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Jam Pulang <span style="color:var(--red)">*</span></label>
                    <input type="time" name="jam_pulang" class="form-control" value="<?php echo e(old('jam_pulang')); ?>">
                    <?php $__errorArgs = ['jam_pulang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Toleransi Terlambat (menit) <span style="color:var(--red)">*</span></label>
                <input type="number" name="toleransi_terlambat" class="form-control"
                    value="<?php echo e(old('toleransi_terlambat', 15)); ?>" min="0" placeholder="15">
                <?php $__errorArgs = ['toleransi_terlambat'];
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
                <a href="<?php echo e(route('admin.shift.index')); ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/shift/create.blade.php ENDPATH**/ ?>