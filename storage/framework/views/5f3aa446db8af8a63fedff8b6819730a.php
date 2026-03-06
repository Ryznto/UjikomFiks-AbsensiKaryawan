

<?php $__env->startSection('title', 'Edit Karyawan'); ?>
<?php $__env->startSection('page-title', 'Edit Karyawan'); ?>
<?php $__env->startSection('page-sub', 'Perbarui data karyawan'); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('admin.karyawan.index')); ?>" class="btn btn-secondary">← Kembali</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <div class="card-title">Edit Data — <?php echo e($karyawan->nama); ?></div>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.karyawan.update', $karyawan)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">NIP <span style="color:var(--red)">*</span></label>
                    <input type="text" name="nip" class="form-control" value="<?php echo e(old('nip', $karyawan->user->nip)); ?>">
                    <?php $__errorArgs = ['nip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:var(--red)">*</span></label>
                    <input type="text" name="nama" class="form-control" value="<?php echo e(old('nama', $karyawan->nama)); ?>">
                    <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-grid-3">
                <div class="form-group">
                    <label class="form-label">Divisi <span style="color:var(--red)">*</span></label>
                    <select name="divisi_id" class="form-control">
                        <?php $__currentLoopData = $divisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($d->id); ?>" <?php echo e(old('divisi_id', $karyawan->divisi_id) == $d->id ? 'selected' : ''); ?>>
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
                <div class="form-group">
                    <label class="form-label">Jabatan <span style="color:var(--red)">*</span></label>
                    <select name="jabatan_id" class="form-control">
                        <?php $__currentLoopData = $jabatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($j->id); ?>" <?php echo e(old('jabatan_id', $karyawan->jabatan_id) == $j->id ? 'selected' : ''); ?>>
                            <?php echo e($j->nama_jabatan); ?> (<?php echo e($j->divisi->nama_divisi); ?>)
                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['jabatan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label class="form-label">Shift <span style="color:var(--red)">*</span></label>
                    <select name="shift_id" class="form-control">
                        <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>" <?php echo e(old('shift_id', $karyawan->shift_id) == $s->id ? 'selected' : ''); ?>>
                            <?php echo e($s->nama_shift); ?> (<?php echo e($s->jam_masuk); ?> - <?php echo e($s->jam_pulang); ?>)
                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['shift_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control" value="<?php echo e(old('no_hp', $karyawan->no_hp)); ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status_aktif" class="form-control">
                        <option value="1" <?php echo e($karyawan->status_aktif ? 'selected' : ''); ?>>Aktif</option>
                        <option value="0" <?php echo e(!$karyawan->status_aktif ? 'selected' : ''); ?>>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="<?php echo e(route('admin.karyawan.index')); ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/karyawan/edit.blade.php ENDPATH**/ ?>