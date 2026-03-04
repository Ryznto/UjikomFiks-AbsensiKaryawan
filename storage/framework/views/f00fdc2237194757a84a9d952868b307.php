

<?php $__env->startSection('title', 'Tambah Karyawan'); ?>
<?php $__env->startSection('page-title', 'Tambah Karyawan'); ?>
<?php $__env->startSection('page-sub', 'Buat akun dan data karyawan baru'); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('admin.karyawan.index')); ?>" class="btn btn-secondary">← Kembali</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <div class="card-title">Form Tambah Karyawan</div>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.karyawan.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-grid-2">
                <div class="form-group">
                    <label class="form-label">NIP <span style="color:var(--red)">*</span></label>
                    <input type="text" name="nip" class="form-control" value="<?php echo e(old('nip')); ?>" placeholder="Contoh: EMP001">
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
                    <input type="text" name="nama" class="form-control" value="<?php echo e(old('nama')); ?>" placeholder="Nama lengkap karyawan">
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
                        <option value="">-- Pilih Divisi --</option>
                        <?php $__currentLoopData = $divisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($d->id); ?>" <?php echo e(old('divisi_id') == $d->id ? 'selected' : ''); ?>>
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
                        <option value="">-- Pilih Jabatan --</option>
                        <?php $__currentLoopData = $jabatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($j->id); ?>" <?php echo e(old('jabatan_id') == $j->id ? 'selected' : ''); ?>>
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
                        <option value="">-- Pilih Shift --</option>
                        <?php $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>" <?php echo e(old('shift_id') == $s->id ? 'selected' : ''); ?>>
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

            <div class="form-group">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" class="form-control" value="<?php echo e(old('no_hp')); ?>" placeholder="Opsional">
                <?php $__errorArgs = ['no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div style="background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 14px 16px; margin-bottom: 20px;">
                <div style="font-size: 12px; color: var(--amber);">⚠️ Password akan di-generate otomatis oleh sistem dan ditampilkan sekali setelah karyawan berhasil ditambahkan.</div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Simpan Karyawan</button>
                <a href="<?php echo e(route('admin.karyawan.index')); ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/karyawan/create.blade.php ENDPATH**/ ?>