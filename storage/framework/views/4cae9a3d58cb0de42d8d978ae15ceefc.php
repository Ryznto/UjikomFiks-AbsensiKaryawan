

<?php $__env->startSection('title', 'Edit Jabatan'); ?>
<?php $__env->startSection('page-title', 'Edit Jabatan'); ?>
<?php $__env->startSection('page-sub', $jabatan->nama_jabatan); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('admin.jabatan.index')); ?>" class="btn btn-secondary">← Kembali</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card" style="max-width: 520px;">
    <div class="card-header">
        <div class="card-title">Edit — <?php echo e($jabatan->nama_jabatan); ?></div>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.jabatan.update', $jabatan)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="form-group">
                <label class="form-label">Nama Jabatan <span style="color:var(--red)">*</span></label>
                <input type="text" name="nama_jabatan" class="form-control"
                    value="<?php echo e(old('nama_jabatan', $jabatan->nama_jabatan)); ?>">
                <?php $__errorArgs = ['nama_jabatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div class="form-group">
                <label class="form-label">Divisi <span style="color:var(--red)">*</span></label>
                <select name="divisi_id" class="form-control">
                    <?php $__currentLoopData = $divisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($d->id); ?>" <?php echo e(old('divisi_id', $jabatan->divisi_id) == $d->id ? 'selected' : ''); ?>>
                        <?php echo e($d->nama_divisi); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['divisi_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="<?php echo e(route('admin.jabatan.index')); ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/jabatan/edit.blade.php ENDPATH**/ ?>