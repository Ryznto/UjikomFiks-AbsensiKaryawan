

<?php $__env->startSection('title', 'Data Shift'); ?>
<?php $__env->startSection('page-title', 'Data Shift'); ?>
<?php $__env->startSection('page-sub', 'Kelola jadwal shift karyawan'); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('admin.shift.create')); ?>" class="btn btn-primary">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
    Tambah Shift
</a>
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
            Daftar Shift
        </div>
        <span style="font-size: 12px; color: var(--mid); font-family: var(--mono);">
            Total: <?php echo e($shifts->total()); ?> shift
        </span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Shift</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Toleransi</th>
                    <th>Karyawan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $shifts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-family: var(--mono); color: var(--mid);"><?php echo e($loop->iteration); ?></td>
                    <td style="font-weight: 600; color: var(--white);"><?php echo e($s->nama_shift); ?></td>
                    <td style="font-family: var(--mono); color: var(--green);"><?php echo e($s->jam_masuk); ?></td>
                    <td style="font-family: var(--mono); color: var(--amber);"><?php echo e($s->jam_pulang); ?></td>
                    <td><span class="badge badge-gray"><?php echo e($s->toleransi_terlambat); ?> menit</span></td>
                    <td><span class="badge badge-blue"><?php echo e($s->karyawans_count); ?> karyawan</span></td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="<?php echo e(route('admin.shift.edit', $s)); ?>" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.shift.destroy', $s)); ?>"
                                onsubmit="return confirm('Hapus shift ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <p>Belum ada data shift</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($shifts->hasPages()): ?>
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        <?php echo e($shifts->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/shift/index.blade.php ENDPATH**/ ?>