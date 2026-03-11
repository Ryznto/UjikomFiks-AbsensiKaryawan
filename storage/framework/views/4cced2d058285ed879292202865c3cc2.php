

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('page-sub', 'Selamat datang, ' . (auth()->user()->adminProfile->nama_admin ?? 'Admin')); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
<?php endif; ?>

<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon">👥</div>
        <div class="stat-value"><?php echo e($totalKaryawan); ?></div>
        <div class="stat-label">Total Karyawan</div>
        <div class="stat-sub">Aktif terdaftar</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?php echo e($hadirHariIni); ?></div>
        <div class="stat-label">Hadir Hari Ini</div>
        <div class="stat-sub"><?php echo e($totalKaryawan > 0 ? round($hadirHariIni / $totalKaryawan * 100) : 0); ?>% kehadiran</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon">⏰</div>
        <div class="stat-value"><?php echo e($terlambatHariIni); ?></div>
        <div class="stat-label">Terlambat</div>
        <div class="stat-sub">Hari ini</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon">❌</div>
        <div class="stat-value"><?php echo e($alfaHariIni); ?></div>
        <div class="stat-label">Tidak Hadir</div>
        <div class="stat-sub">Alfa hari ini</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Log Presensi Hari Ini
        </div>
        <a href="<?php echo e(route('admin.presensi.index')); ?>" class="card-action">Lihat Semua →</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Divisi</th>
                    <th>Shift</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $presensiHariIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background: linear-gradient(135deg, #4f7cff, #a855f7)">
                                <?php echo e(strtoupper(substr($p->karyawan->nama, 0, 2))); ?>

                            </div>
                            <div>
                                <div class="emp-name"><?php echo e($p->karyawan->nama); ?></div>
                                <div class="emp-sub"><?php echo e($p->karyawan->user->nip); ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?php echo e($p->karyawan->divisi->nama_divisi); ?></td>
                    <td><span class="badge badge-blue"><?php echo e($p->shift->nama_shift ?? $p->karyawan->shift->nama_shift); ?></span></td>
                    <td style="font-family: var(--mono); color: var(--green)"><?php echo e($p->jam_masuk ?? '—'); ?></td>
                    <td style="font-family: var(--mono); color: var(--mid)"><?php echo e($p->jam_pulang ?? '—'); ?></td>
                    <td>
                        <?php
                            $badgeMap = [
                                'tepat_waktu'  => ['class' => 'badge-green', 'label' => 'Tepat Waktu'],
                                'terlambat'    => ['class' => 'badge-amber', 'label' => 'Terlambat'],
                                'pulang_cepat' => ['class' => 'badge-amber', 'label' => 'Pulang Cepat'],
                                'alfa'         => ['class' => 'badge-red',   'label' => 'Alfa'],
                            ];
                            $b = $badgeMap[$p->status_absen] ?? ['class' => 'badge-gray', 'label' => '-'];
                        ?>
                        <span class="badge <?php echo e($b['class']); ?>">
                            <span class="badge-dot"></span>
                            <?php echo e($b['label']); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <p>Belum ada data presensi hari ini</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>