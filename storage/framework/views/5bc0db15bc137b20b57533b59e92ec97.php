

<?php $__env->startSection('title', 'Laporan Penilaian'); ?>
<?php $__env->startSection('page-title', 'Laporan Penilaian'); ?>
<?php $__env->startSection('page-sub', 'Periode: ' . $period); ?>

<?php $__env->startSection('content'); ?>


<div class="card" style="margin-bottom:20px;">
    <div style="padding:20px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <h3 style="margin:0;">📊 Laporan Penilaian Karyawan</h3>
        <form method="GET" style="display:flex; gap:8px;">
            <input type="month" name="period_month" class="form-control"
                value="<?php echo e(now()->format('Y-m')); ?>"
                onchange="this.form.submit()"
                style="width:auto;">
        </form>
    </div>
</div>


<?php if($stats && $stats->total > 0): ?>
<div class="stats-grid" style="margin-bottom:20px;">
    <div class="stat-card blue">
        <div class="stat-icon">📋</div>
        <div class="stat-value"><?php echo e($stats->total); ?></div>
        <div class="stat-label">Total Penilaian</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">⭐</div>
        <div class="stat-value"><?php echo e(number_format($stats->overall_avg, 2)); ?></div>
        <div class="stat-label">Rata-rata Keseluruhan</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon">🏆</div>
        <div class="stat-value"><?php echo e(number_format($stats->highest, 2)); ?></div>
        <div class="stat-label">Nilai Tertinggi</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon">📉</div>
        <div class="stat-value"><?php echo e(number_format($stats->lowest, 2)); ?></div>
        <div class="stat-label">Nilai Terendah</div>
    </div>
</div>
<?php endif; ?>


<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Detail Penilaian
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Karyawan</th>
                    <th>Jabatan</th>
                    <th>Penilai</th>
                    <th>Tanggal</th>
                    <th>Rata-rata</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $assessments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assessment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($assessments->firstItem() + $loop->index); ?></td>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background:linear-gradient(135deg,#4f7cff,#a855f7)">
                                <?php echo e(strtoupper(substr($assessment->evaluatee->nama, 0, 2))); ?>

                            </div>
                            <div>
                                <div class="emp-name"><?php echo e($assessment->evaluatee->nama); ?></div>
                                <div class="emp-sub"><?php echo e($assessment->evaluatee->user->nip); ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?php echo e($assessment->evaluatee->jabatan->nama_jabatan ?? '-'); ?></td>
                    <td><?php echo e($assessment->evaluator->adminProfile->nama_admin ?? '-'); ?></td>
                    <td style="font-size:0.85rem;"><?php echo e($assessment->assessment_date->format('d M Y')); ?></td>
                    <td>
                        <div style="display:flex; align-items:center; gap:6px;">
                            <span style="font-size:1.2rem; font-weight:700; color:#4f7cff;">
                                <?php echo e(number_format($assessment->average_score, 1)); ?>

                            </span>
                            <span style="color:var(--mid); font-size:0.8rem;">/5</span>
                        </div>
                        <div style="font-size:0.75rem; line-height:1;">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <span style="color: <?php echo e($i <= round($assessment->average_score) ? '#fbbf24' : '#e5e7eb'); ?>">★</span>
                            <?php endfor; ?>
                        </div>
                    </td>
                    <td>
                        <?php
                            $badgeMap = [
                                'Sangat Baik'   => 'badge-green',
                                'Baik'          => 'badge-blue',
                                'Cukup'         => 'badge-amber',
                                'Kurang'        => 'badge-red',
                                'Sangat Kurang' => 'badge-red',
                            ];
                        ?>
                        <span class="badge <?php echo e($badgeMap[$assessment->score_label] ?? 'badge-gray'); ?>">
                            <span class="badge-dot"></span><?php echo e($assessment->score_label); ?>

                        </span>
                    </td>
                    <td>
                        <a href="<?php echo e(route('admin.assessments.show', $assessment)); ?>"
                            style="display:inline-flex; align-items:center; gap:4px; padding:6px 14px; background:#4f7cff; color:white; text-decoration:none; border-radius:8px; font-size:0.8rem; font-weight:600;">
                            Detail
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <p>Belum ada data penilaian untuk periode ini</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($assessments->hasPages()): ?>
    <div style="padding:16px 20px;">
        <?php echo e($assessments->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/assessments/report.blade.php ENDPATH**/ ?>