

<?php $__env->startSection('title', 'Koreksi Absen'); ?>
<?php $__env->startSection('page-title', 'Koreksi Absen'); ?>
<?php $__env->startSection('page-sub', 'Verifikasi pengajuan koreksi absen karyawan'); ?>

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
            Daftar Pengajuan Koreksi
        </div>
        <?php if($totalPending > 0): ?>
        <span class="badge badge-amber"><?php echo e($totalPending); ?> pending</span>
        <?php endif; ?>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Divisi</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Alasan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $koreksis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background: linear-gradient(135deg, #4f7cff, #a855f7)">
                                <?php echo e(strtoupper(substr($k->karyawan->nama, 0, 2))); ?>

                            </div>
                            <div>
                                <div class="emp-name"><?php echo e($k->karyawan->nama); ?></div>
                                <div class="emp-sub"><?php echo e($k->karyawan->user->nip); ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?php echo e($k->karyawan->divisi->nama_divisi); ?></td>
                    <td style="font-family: var(--mono); font-size: 12px;">
                        <?php echo e(\Carbon\Carbon::parse($k->tanggal)->format('d M Y')); ?>

                    </td>
                    <td style="font-family: var(--mono); color: var(--green);"><?php echo e($k->jam_masuk); ?></td>
                    <td style="font-family: var(--mono); color: var(--amber);"><?php echo e($k->jam_pulang ?? '—'); ?></td>
                    <td style="max-width: 200px; font-size: 12px; color: var(--muted);">
                        <?php echo e(Str::limit($k->alasan, 50)); ?>

                    </td>
                    <td>
                        <?php if($k->status === 'pending'): ?>
                            <span class="badge badge-amber"><span class="badge-dot"></span>Pending</span>
                        <?php elseif($k->status === 'approved'): ?>
                            <span class="badge badge-green"><span class="badge-dot"></span>Approved</span>
                        <?php else: ?>
                            <span class="badge badge-red"><span class="badge-dot"></span>Rejected</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($k->status === 'pending'): ?>
                        <div style="display: flex; gap: 6px;">
                            <form method="POST" action="<?php echo e(route('admin.koreksi-absen.approve', $k)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-success btn-sm">✓ Approve</button>
                            </form>
                            <form method="POST" action="<?php echo e(route('admin.koreksi-absen.reject', $k)); ?>"
                                onsubmit="return confirm('Tolak koreksi ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">✕ Reject</button>
                            </form>
                        </div>
                        <?php else: ?>
                        <div style="font-size: 11px; color: var(--mid); font-family: var(--mono);">
                            <?php echo e($k->approvedBy->adminProfile->nama_admin ?? '-'); ?><br>
                            <?php echo e($k->approved_at ? \Carbon\Carbon::parse($k->approved_at)->format('d M Y') : ''); ?>

                        </div>
                        <?php if($k->catatan_admin): ?>
                        <div style="font-size: 11px; color: var(--muted); margin-top: 4px;">
                            <?php echo e($k->catatan_admin); ?>

                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <p>Belum ada pengajuan koreksi absen</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($koreksis->hasPages()): ?>
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        <?php echo e($koreksis->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/koreksi-absen/index.blade.php ENDPATH**/ ?>