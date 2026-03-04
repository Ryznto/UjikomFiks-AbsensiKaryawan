

<?php $__env->startSection('title', 'Detail Karyawan'); ?>
<?php $__env->startSection('page-title', 'Detail Karyawan'); ?>
<?php $__env->startSection('page-sub', $karyawan->nama); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('admin.karyawan.edit', $karyawan)); ?>" class="btn btn-secondary">Edit</a>
<a href="<?php echo e(route('admin.karyawan.index')); ?>" class="btn btn-secondary">← Kembali</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if(session('generated_password')): ?>
<div class="alert alert-success" style="flex-direction: column; align-items: flex-start; gap: 6px;">
    <strong>🔑 Password Baru</strong>
    <span>NIP: <strong><?php echo e(session('generated_nip')); ?></strong></span>
    <span>Password: <strong style="font-family: var(--mono); font-size: 15px;"><?php echo e(session('generated_password')); ?></strong></span>
    <small style="color: var(--mid);">Catat password ini sekarang, tidak akan ditampilkan lagi.</small>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">Info Karyawan</div>
        <form method="POST" action="<?php echo e(route('admin.karyawan.reset-password', $karyawan)); ?>"
            onsubmit="return confirm('Reset password karyawan ini?')">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn btn-secondary btn-sm">🔑 Reset Password</button>
        </form>
    </div>
    <div class="card-body">
        <div class="form-grid-2" style="gap: 20px;">
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">NAMA</div>
                <div style="font-size: 15px; font-weight: 600;"><?php echo e($karyawan->nama); ?></div>
            </div>
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">NIP</div>
                <div style="font-size: 15px; font-weight: 600; font-family: var(--mono);"><?php echo e($karyawan->user->nip); ?></div>
            </div>
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">DIVISI</div>
                <div style="font-size: 15px; font-weight: 600;"><?php echo e($karyawan->divisi->nama_divisi); ?></div>
            </div>
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">JABATAN</div>
                <div style="font-size: 15px; font-weight: 600;"><?php echo e($karyawan->jabatan->nama_jabatan); ?></div>
            </div>
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">SHIFT</div>
                <div style="font-size: 15px; font-weight: 600;"><?php echo e($karyawan->shift->nama_shift); ?> (<?php echo e($karyawan->shift->jam_masuk); ?> - <?php echo e($karyawan->shift->jam_pulang); ?>)</div>
            </div>
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">NO. HP</div>
                <div style="font-size: 15px; font-weight: 600;"><?php echo e($karyawan->no_hp ?? '-'); ?></div>
            </div>
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">STATUS</div>
                <?php if($karyawan->status_aktif): ?>
                    <span class="badge badge-green"><span class="badge-dot"></span>Aktif</span>
                <?php else: ?>
                    <span class="badge badge-red"><span class="badge-dot"></span>Nonaktif</span>
                <?php endif; ?>
            </div>
            <div>
                <div style="font-size: 11px; color: var(--mid); font-family: var(--mono); margin-bottom: 4px;">TERDAFTAR</div>
                <div style="font-size: 15px; font-weight: 600;"><?php echo e($karyawan->created_at->format('d M Y')); ?></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/karyawan/show.blade.php ENDPATH**/ ?>