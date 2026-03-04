

<?php $__env->startSection('title', 'Data Karyawan'); ?>
<?php $__env->startSection('page-title', 'Data Karyawan'); ?>
<?php $__env->startSection('page-sub', 'Kelola data seluruh karyawan'); ?>

<?php $__env->startSection('page-actions'); ?>
<a href="<?php echo e(route('admin.karyawan.create')); ?>" class="btn btn-primary">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 4v16m8-8H4"/></svg>
    Tambah Karyawan
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if(session('show_password')): ?>
<div class="alert alert-success" style="flex-direction: column; align-items: flex-start; gap: 6px;">
    <strong>🔑 Kredensial Login Karyawan</strong>
    <span>NIP: <strong><?php echo e(session('generated_nip')); ?></strong></span>
    <span>Password: <strong style="font-family: var(--mono); font-size: 15px;"><?php echo e(session('generated_password')); ?></strong></span>
    <small style="color: var(--mid);">Catat password ini sekarang, tidak akan ditampilkan lagi.</small>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Daftar Karyawan
        </div>
        <span style="font-size: 12px; color: var(--mid); font-family: var(--mono);">
            Total: <?php echo e($karyawans->total()); ?> karyawan
        </span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>NIP</th>
                    <th>Divisi</th>
                    <th>Jabatan</th>
                    <th>Shift</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $karyawans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background: linear-gradient(135deg, #4f7cff, #a855f7)">
                                <?php echo e(strtoupper(substr($k->nama, 0, 2))); ?>

                            </div>
                            <div>
                                <div class="emp-name"><?php echo e($k->nama); ?></div>
                                <div class="emp-sub"><?php echo e($k->no_hp ?? '-'); ?></div>
                            </div>
                        </div>
                    </td>
                    <td style="font-family: var(--mono); font-size: 12px;"><?php echo e($k->user->nip); ?></td>
                    <td><?php echo e($k->divisi->nama_divisi); ?></td>
                    <td><?php echo e($k->jabatan->nama_jabatan); ?></td>
                    <td><span class="badge badge-blue"><?php echo e($k->shift->nama_shift); ?></span></td>
                    <td>
                        <?php if($k->status_aktif): ?>
                            <span class="badge badge-green"><span class="badge-dot"></span>Aktif</span>
                        <?php else: ?>
                            <span class="badge badge-red"><span class="badge-dot"></span>Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px;">
                            <a href="<?php echo e(route('admin.karyawan.show', $k)); ?>" class="btn btn-secondary btn-sm">Detail</a>
                            <a href="<?php echo e(route('admin.karyawan.edit', $k)); ?>" class="btn btn-secondary btn-sm">Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.karyawan.destroy', $k)); ?>"
                                onsubmit="return confirm('Hapus karyawan ini?')">
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
                            <p>Belum ada data karyawan</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($karyawans->hasPages()): ?>
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        <?php echo e($karyawans->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/karyawan/index.blade.php ENDPATH**/ ?>