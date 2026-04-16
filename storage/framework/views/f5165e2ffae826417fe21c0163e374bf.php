

<?php $__env->startSection('title', 'Leaderboard Integritas'); ?>
<?php $__env->startSection('page-title', 'Leaderboard Integritas'); ?>
<?php $__env->startSection('page-sub', 'Peringkat poin integritas karyawan'); ?>

<?php $__env->startSection('content'); ?>

<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="stat-card blue">
        <div class="stat-icon">🏆</div>
        <div class="stat-value"><?php echo e($topPoints->first()->point_balance ?? 0); ?></div>
        <div class="stat-label">Poin Tertinggi</div>
        <div class="stat-sub"><?php echo e($topPoints->first()->karyawan->nama ?? '-'); ?></div>
    </div>
    <div class="stat-card purple">
        <div class="stat-icon">📊</div>
        <div class="stat-value"><?php echo e(round($averagePoint)); ?></div>
        <div class="stat-label">Rata-rata Poin</div>
        <div class="stat-sub">Semua karyawan</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon">⚠️</div>
        <div class="stat-value"><?php echo e($bottomPoints->first()->point_balance ?? 0); ?></div>
        <div class="stat-label">Poin Terendah</div>
        <div class="stat-sub"><?php echo e($bottomPoints->first()->karyawan->nama ?? '-'); ?></div>
    </div>
</div>

<div class="grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-title-dot" style="background: #10b981;"></div>
                🏆 Top 5 Point Tertinggi
            </div>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Karyawan</th>
                        <th>Divisi</th>
                        <th>Poin</th>
                        <th>Level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $topPoints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <?php if($index == 0): ?> 🥇
                            <?php elseif($index == 1): ?> 🥈
                            <?php elseif($index == 2): ?> 🥉
                            <?php else: ?> <?php echo e($index + 1); ?>

                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar" style="background: linear-gradient(135deg, #4f7cff, #a855f7)">
                                    <?php echo e(strtoupper(substr($user->karyawan->nama ?? 'U', 0, 2))); ?>

                                </div>
                                <div>
                                    <div class="emp-name"><?php echo e($user->karyawan->nama ?? '-'); ?></div>
                                    <div class="emp-sub"><?php echo e($user->nip ?? '-'); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($user->karyawan->divisi->nama_divisi ?? '-'); ?></td>
                        <td style="font-weight: 700; color: #10b981;"><?php echo e(number_format($user->point_balance)); ?></td>
                        <td>
                            <?php
                                $level = $user->point_balance >= 200 ? 'Elite' : ($user->point_balance >= 100 ? 'Andal' : ($user->point_balance >= 50 ? 'Disiplin' : 'Pemula'));
                            ?>
                            <span class="badge badge-green"><?php echo e($level); ?></span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data karyawan</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-title-dot" style="background: #ef4444;"></div>
                ⚠️ Bottom 5 Point Terendah
            </div>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Karyawan</th>
                        <th>Divisi</th>
                        <th>Poin</th>
                        <th>Level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $bottomPoints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td>
                            <div class="emp-cell">
                                <div class="emp-avatar" style="background: linear-gradient(135deg, #ef4444, #a855f7)">
                                    <?php echo e(strtoupper(substr($user->karyawan->nama ?? 'U', 0, 2))); ?>

                                </div>
                                <div>
                                    <div class="emp-name"><?php echo e($user->karyawan->nama ?? '-'); ?></div>
                                    <div class="emp-sub"><?php echo e($user->nip ?? '-'); ?></div>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($user->karyawan->divisi->nama_divisi ?? '-'); ?></td>
                        <td style="font-weight: 700; color: #ef4444;"><?php echo e(number_format($user->point_balance)); ?></td>
                        <td>
                            <?php
                                $level = $user->point_balance >= 200 ? 'Elite' : ($user->point_balance >= 100 ? 'Andal' : ($user->point_balance >= 50 ? 'Disiplin' : 'Pemula'));
                            ?>
                            <span class="badge badge-red"><?php echo e($level); ?></span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data karyawan</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/leaderboard/index.blade.php ENDPATH**/ ?>