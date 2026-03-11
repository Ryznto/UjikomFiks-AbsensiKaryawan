

<?php $__env->startSection('title', 'Profil Saya'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
<?php endif; ?>


<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Informasi Akun
        </div>
    </div>
    <div class="card-body">

        
        <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--border);">
            <div style="width: 60px; height: 60px; border-radius: 14px; background: linear-gradient(135deg, #4f7cff, #a855f7); display: flex; align-items: center; justify-content: center; font-size: 22px; font-weight: 800; color: white; flex-shrink: 0;">
                <?php echo e(strtoupper(substr($karyawan->nama, 0, 2))); ?>

            </div>
            <div>
                <div style="font-size: 17px; font-weight: 700;"><?php echo e($karyawan->nama); ?></div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-top: 3px;"><?php echo e($user->nip); ?></div>
                <span class="badge badge-blue" style="margin-top: 6px;">Karyawan</span>
            </div>
        </div>

        <div class="info-row">
            <span class="info-label">NIP</span>
            <span class="info-value mono"><?php echo e($user->nip); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Nama</span>
            <span class="info-value"><?php echo e($karyawan->nama); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Divisi</span>
            <span class="info-value"><?php echo e($karyawan->divisi->nama_divisi); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Jabatan</span>
            <span class="info-value"><?php echo e($karyawan->jabatan->nama_jabatan); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Shift</span>
            <span class="info-value">
                <?php echo e($karyawan->shift->nama_shift); ?>

                <span style="color: var(--mid); font-size: 11px; font-family: var(--mono);">
                    <?php echo e($karyawan->shift->jam_masuk); ?> - <?php echo e($karyawan->shift->jam_pulang); ?>

                </span>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">No. HP</span>
            <span class="info-value"><?php echo e($karyawan->no_hp ?? '—'); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Status</span>
            <span class="info-value">
                <?php if($karyawan->status_aktif): ?>
                    <span class="badge badge-green"><span class="badge-dot"></span>Aktif</span>
                <?php else: ?>
                    <span class="badge badge-red"><span class="badge-dot"></span>Nonaktif</span>
                <?php endif; ?>
            </span>
        </div>
    </div>
</div>


<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">Ganti Password</div>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('karyawan.profil.password')); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="form-group">
                <label class="form-label">Password Lama <span style="color:var(--red)">*</span></label>
                <input type="password" name="password_lama" class="form-control"
                    placeholder="Masukkan password saat ini">
                <?php $__errorArgs = ['password_lama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Password Baru <span style="color:var(--red)">*</span></label>
                <input type="password" name="password_baru" class="form-control"
                    placeholder="Minimal 8 karakter">
                <?php $__errorArgs = ['password_baru'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru <span style="color:var(--red)">*</span></label>
                <input type="password" name="password_baru_confirmation" class="form-control"
                    placeholder="Ulangi password baru">
            </div>

            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.karyawan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/karyawan/profil.blade.php ENDPATH**/ ?>