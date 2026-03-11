

<?php $__env->startSection('title', 'Detail Penilaian'); ?>
<?php $__env->startSection('page-title', 'Detail Penilaian'); ?>
<?php $__env->startSection('page-sub', 'Periode: ' . $assessment->period); ?>

<?php $__env->startSection('content'); ?>

<a href="<?php echo e(route('admin.assessments.report')); ?>"
    style="display:inline-flex; align-items:center; gap:6px; margin-bottom:20px; color:var(--mid); text-decoration:none;">
    ← Kembali ke Laporan
</a>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">

    
    <div style="display:flex; flex-direction:column; gap:20px;">

        
        <div class="card">
            <div style="padding:24px; text-align:center;">
                <div style="width:80px; height:80px; border-radius:50%; background:linear-gradient(135deg,#4f7cff,#a855f7); display:flex; align-items:center; justify-content:center; font-size:32px; color:white; font-weight:700; margin:0 auto 16px;">
                    <?php echo e(strtoupper(substr($assessment->evaluatee->nama, 0, 2))); ?>

                </div>
                <h4 style="margin:0 0 4px;"><?php echo e($assessment->evaluatee->nama); ?></h4>
                <div style="color:var(--mid); font-size:0.875rem; margin-bottom:16px;">
                    <?php echo e($assessment->evaluatee->jabatan->nama_jabatan ?? '-'); ?> •
                    <?php echo e($assessment->evaluatee->divisi->nama_divisi ?? '-'); ?>

                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; text-align:left;">
                    <div style="background:#f9fafb; padding:12px; border-radius:8px;">
                        <div style="font-size:0.75rem; color:var(--mid);">Periode</div>
                        <div style="font-weight:600;"><?php echo e($assessment->period); ?></div>
                    </div>
                    <div style="background:#f9fafb; padding:12px; border-radius:8px;">
                        <div style="font-size:0.75rem; color:var(--mid);">Tanggal</div>
                        <div style="font-weight:600;"><?php echo e($assessment->assessment_date->format('d M Y')); ?></div>
                    </div>
                    <div style="background:#f9fafb; padding:12px; border-radius:8px;">
                        <div style="font-size:0.75rem; color:var(--mid);">Penilai</div>
                        <div style="font-weight:600;"><?php echo e($assessment->evaluator->adminProfile->nama_admin ?? '-'); ?></div>
                    </div>
                    <div style="background:#f9fafb; padding:12px; border-radius:8px;">
                        <div style="font-size:0.75rem; color:var(--mid);">Rata-rata</div>
                        <div style="font-weight:700; color:#4f7cff; font-size:1.2rem;">
                            <?php echo e(number_format($assessment->average_score, 2)); ?>/5
                        </div>
                    </div>
                </div>
                <div style="margin-top:16px;">
                    <span class="badge badge-<?php echo e($assessment->score_badge); ?>" style="font-size:0.9rem; padding:6px 16px;">
                        <span class="badge-dot"></span><?php echo e($assessment->score_label); ?>

                    </span>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-dot"></div>
                    Grafik Spider Web
                </div>
            </div>
            <div style="padding:20px;">
                <?php if(!empty($radarLabels)): ?>
                    <canvas id="radarChart" height="280"></canvas>
                <?php else: ?>
                    <div class="empty-state"><p>Tidak ada data</p></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <div style="display:flex; flex-direction:column; gap:20px;">

        
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-dot"></div>
                    Detail Nilai Per Indikator
                </div>
            </div>
            <div style="padding:20px; display:flex; flex-direction:column; gap:16px;">
                <?php $__currentLoopData = $assessment->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                    <span style="font-weight:600;"><?php echo e($detail->statement->statement ?? '-'); ?></span>
                        <span style="font-weight:700; color:#4f7cff;"><?php echo e(number_format($detail->score, 1)); ?>/5</span>
                    </div>
                    
                    <div style="margin-bottom:6px;">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <span style="font-size:1.2rem; color:<?php echo e($i <= $detail->score ? '#fbbf24' : '#e5e7eb'); ?>">★</span>
                        <?php endfor; ?>
                    </div>
                    
                    <div style="background:#f0f0f0; border-radius:99px; height:8px;">
                        <?php
                            $pct = ($detail->score / 5) * 100;
                            $color = $detail->score >= 4 ? '#22c55e' : ($detail->score >= 3 ? '#4f7cff' : ($detail->score >= 2 ? '#fbbf24' : '#ef4444'));
                        ?>
                        <div style="width:<?php echo e($pct); ?>%; background:<?php echo e($color); ?>; height:100%; border-radius:99px; transition:width 1s ease;"></div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <?php if($assessment->general_notes): ?>
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-dot"></div>
                    Catatan Penilai
                </div>
            </div>
            <div style="padding:20px;">
                <p style="line-height:1.7; color:var(--dark); margin:0;"><?php echo e($assessment->general_notes); ?></p>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
<?php if(!empty($radarLabels)): ?>
new Chart(document.getElementById('radarChart').getContext('2d'), {
    type: 'radar',
    data: {
        labels: <?php echo json_encode($radarLabels, 15, 512) ?>,
        datasets: [{
            label: 'Nilai',
            data: <?php echo json_encode($radarScores, 15, 512) ?>,
            backgroundColor: 'rgba(79,124,255,0.15)',
            borderColor: '#4f7cff',
            borderWidth: 2,
            pointBackgroundColor: '#4f7cff',
            pointBorderColor: '#fff',
            pointRadius: 5,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            r: {
                min: 0, max: 5,
                ticks: { stepSize: 1, callback: v => v + ' ★', font: { size: 10 } },
                pointLabels: { font: { size: 11, weight: 'bold' } },
            }
        }
    }
});
<?php endif; ?>
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/assessments/show.blade.php ENDPATH**/ ?>