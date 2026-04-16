

<?php $__env->startSection('title', 'Koreksi Absen'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger">❌ <?php echo e(session('error')); ?></div>
<?php endif; ?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
    <div class="section-title" style="margin-bottom: 0;">Koreksi Absen</div>
    <a href="<?php echo e(route('karyawan.koreksi-absen.create')); ?>" class="btn btn-primary btn-inline">+ Ajukan</a>
</div>

<div class="card fade-in">
    <?php $__empty_1 = true; $__currentLoopData = $koreksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div style="padding: 14px 18px; border-bottom: 1px solid rgba(42,47,66,0.5);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <div style="font-size: 13px; font-weight: 600; color: var(--white); font-family: var(--mono);">
                <?php echo e(\Carbon\Carbon::parse($k->tanggal)->format('d M Y')); ?>

            </div>
            <?php if($k->status === 'pending'): ?>
                <span class="badge badge-amber"><span class="badge-dot"></span>Pending</span>
            <?php elseif($k->status === 'approved'): ?>
                <span class="badge badge-green"><span class="badge-dot"></span>Approved</span>
            <?php else: ?>
                <span class="badge badge-red"><span class="badge-dot"></span>Rejected</span>
            <?php endif; ?>
        </div>

        <div style="display: flex; gap: 12px; margin-bottom: 8px;">
            <div>
                <div style="font-size: 10px; color: var(--mid); font-family: var(--mono); margin-bottom: 2px;">JAM MASUK</div>
                <div style="font-size: 13px; font-family: var(--mono); color: var(--green);"><?php echo e($k->jam_masuk); ?></div>
            </div>
            <div>
                <div style="font-size: 10px; color: var(--mid); font-family: var(--mono); margin-bottom: 2px;">JAM PULANG</div>
                <div style="font-size: 13px; font-family: var(--mono); color: var(--amber);"><?php echo e($k->jam_pulang ?? '—'); ?></div>
            </div>
        </div>

        <div style="font-size: 12px; color: var(--muted);"><?php echo e($k->alasan); ?></div>

        <?php if($k->catatan_admin): ?>
        <div style="font-size: 11px; color: var(--mid); margin-top: 6px; padding: 8px 10px; background: var(--surface-2); border-radius: var(--radius-sm);">
            💬 Admin: <?php echo e($k->catatan_admin); ?>

        </div>
        <?php endif; ?>

        <?php if($k->status !== 'pending'): ?>
        <div style="font-size: 11px; color: var(--mid); margin-top: 6px; font-family: var(--mono);">
            Diproses: <?php echo e($k->approved_at ? \Carbon\Carbon::parse($k->approved_at)->format('d M Y H:i') : '-'); ?>

        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="empty-state">
        <p>Belum ada pengajuan koreksi absen</p>
    </div>
    <?php endif; ?>

    <?php if($koreksis->hasPages()): ?>
    <div style="padding: 14px 18px; border-top: 1px solid var(--border);">
        <?php echo e($koreksis->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.karyawan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/karyawan/koreksi-absen/index.blade.php ENDPATH**/ ?>