

<?php $__env->startSection('title', 'Penilaian Karyawan'); ?>
<?php $__env->startSection('page-title', 'Penilaian Karyawan'); ?>
<?php $__env->startSection('page-sub', 'Periode: ' . $period); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
<?php endif; ?>


<div class="card" style="margin-bottom: 20px;">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Progress Penilaian Bulan Ini
        </div>
        <span style="font-weight:600; color: var(--blue)"><?php echo e($progressPercent); ?>%</span>
    </div>
    <div style="padding: 0 20px 20px;">
        <div style="background:#f0f0f0; border-radius:99px; height:12px; overflow:hidden;">
            <div style="width:<?php echo e($progressPercent); ?>%; background: linear-gradient(135deg, #4f7cff, #a855f7); height:100%; border-radius:99px; transition: width 1s ease;"></div>
        </div>
        <p style="margin-top:10px; color:var(--mid); font-size:0.875rem;">
            Anda telah menilai <strong><?php echo e($totalAssessed); ?></strong> dari <strong><?php echo e($totalKaryawan); ?></strong> karyawan
        </p>
    </div>
</div>


<div class="stats-grid" style="margin-bottom:20px;">
    <div class="stat-card blue">
        <div class="stat-icon">👥</div>
        <div class="stat-value"><?php echo e($totalKaryawan); ?></div>
        <div class="stat-label">Total Karyawan</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?php echo e($totalAssessed); ?></div>
        <div class="stat-label">Sudah Dinilai</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon">⏳</div>
        <div class="stat-value"><?php echo e($totalKaryawan - $totalAssessed); ?></div>
        <div class="stat-label">Belum Dinilai</div>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Daftar Karyawan
        </div>
        <form method="GET" style="display:flex; gap:8px;">
            <input type="month" name="period_month" class="form-control"
                value="<?php echo e(now()->format('Y-m')); ?>"
                onchange="this.form.submit()"
                style="width:auto;">
        </form>
    </div>

    
    <div style="padding:20px; display:grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap:16px;">
        <?php $__empty_1 = true; $__currentLoopData = $karyawans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php $isDone = in_array($kar->id, $assessedIds); ?>
        
        <div style="border:1px solid <?php echo e($isDone ? '#22c55e' : '#fbbf24'); ?>; border-radius:12px; padding:20px; text-align:center; background:<?php echo e($isDone ? '#f0fff4' : '#ffffff'); ?>;">

            
            <div style="width:56px; height:56px; border-radius:50%; background: linear-gradient(135deg, #4f7cff, #a855f7); display:flex; align-items:center; justify-content:center; margin:0 auto 12px; font-size:20px; color:white; font-weight:700;">
                <?php echo e(strtoupper(substr($kar->nama, 0, 2))); ?>

            </div>
            
            <div style="font-weight:600; margin-bottom:4px; color:#131328;"><?php echo e($kar->nama); ?></div>
            <div style="font-size:0.8rem; color:#1a0b0b; margin-bottom:12px;"><?php echo e($kar->jabatan->nama_jabatan ?? '-'); ?></div>

            <?php if($isDone): ?>
                <span class="badge badge-green" style="display:block; margin-bottom:10px;">
                    <span class="badge-dot"></span>Sudah Dinilai
                </span>
                <a href="<?php echo e(route('admin.assessments.create', [$kar->id, 'period' => $period])); ?>"
                    style="display:block; padding:8px; border:1px solid #22c55e; border-radius:8px; color:#22c55e; text-decoration:none; font-size:0.85rem;">
                    ✏️ Edit Nilai
                </a>
            <?php else: ?>
                <span class="badge badge-amber" style="display:block; margin-bottom:10px;">
                    <span class="badge-dot"></span>Belum Dinilai
                </span>
                <a href="<?php echo e(route('admin.assessments.create', [$kar->id, 'period' => $period])); ?>"
                    style="display:block; padding:8px; background: linear-gradient(135deg, #4f7cff, #a855f7); border-radius:8px; color:white; text-decoration:none; font-size:0.85rem;">
                    ⭐ Nilai Sekarang
                </a>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="empty-state" style="grid-column: 1/-1;">
            <p>Belum ada karyawan aktif</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/assessments/index.blade.php ENDPATH**/ ?>