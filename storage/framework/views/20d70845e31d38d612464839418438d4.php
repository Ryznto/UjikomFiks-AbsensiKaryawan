

<?php $__env->startSection('title', 'Detail Penilaian'); ?>
<?php $__env->startSection('page-title', 'Detail Penilaian'); ?>
<?php $__env->startSection('page-sub', 'Periode: ' . $assessment->period); ?>

<?php $__env->startSection('content'); ?>

<a href="<?php echo e(route('admin.assessments.report')); ?>"
    style="display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:linear-gradient(135deg,#1e1e2e,#2d2d44); color:white; text-decoration:none; border-radius:12px; font-size:0.85rem; font-weight:600; box-shadow:0 4px 12px rgba(0,0,0,0.3); border:1px solid rgba(255,255,255,0.1); margin-bottom:20px;">
    <span>←</span>
    <span>Kembali ke Laporan</span>
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
                    <div style="background:linear-gradient(135deg,#1e1e2e,#2d2d44); padding:12px; border-radius:8px; border:1px solid rgba(255,255,255,0.08);">
                        <div style="font-size:0.75rem; color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing:0.5px;">Periode</div>
                        <div style="font-weight:600; color:white;"><?php echo e($assessment->period); ?></div>
                    </div>
                    <div style="background:linear-gradient(135deg,#1e1e2e,#2d2d44); padding:12px; border-radius:8px; border:1px solid rgba(255,255,255,0.08);">
                        <div style="font-size:0.75rem; color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing:0.5px;">Tanggal</div>
                        <div style="font-weight:600; color:white;"><?php echo e($assessment->assessment_date->format('d M Y')); ?></div>
                    </div>
                    <div style="background:linear-gradient(135deg,#1e1e2e,#2d2d44); padding:12px; border-radius:8px; border:1px solid rgba(255,255,255,0.08);">
                        <div style="font-size:0.75rem; color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing:0.5px;">Penilai</div>
                        <div style="font-weight:600; color:white;"><?php echo e($assessment->evaluator->adminProfile->nama_admin ?? '-'); ?></div>
                    </div>
                    <div style="background:linear-gradient(135deg,#1e1e2e,#2d2d44); padding:12px; border-radius:8px; border:1px solid rgba(255,255,255,0.08);">
                        <div style="font-size:0.75rem; color:rgba(255,255,255,0.5); text-transform:uppercase; letter-spacing:0.5px;">Rata-rata</div>
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
            <div style="padding:12px 16px; display:flex; flex-direction:column; gap:8px;">
                <?php $__currentLoopData = $assessment->details->groupBy('statement.category.name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryName => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $avgCat = round($details->avg('score'), 1); ?>
                <div style="border:1px solid #e5e7eb; border-radius:12px; overflow:hidden;">
                    <div onclick="toggleAccordion(<?php echo e($loop->index); ?>)"
                        style="display:flex; justify-content:space-between; align-items:center; padding:14px 16px; cursor:pointer; background:#1e1e2e; color:white;">
                        <div style="font-weight:700; color:white;"><?php echo e($categoryName); ?></div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div>
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <span style="color:<?php echo e($i <= $avgCat ? '#fbbf24' : '#555'); ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <span style="font-weight:700; color:#4f7cff; font-size:0.9rem;"><?php echo e($avgCat); ?>/5</span>
                            <span id="arrow-<?php echo e($loop->index); ?>" style="color:white; transition:transform 0.3s;">▼</span>
                        </div>
                    </div>
                    <div id="accordion-<?php echo e($loop->index); ?>" style="display:none; padding:12px 16px; flex-direction:column; gap:12px;">
                        <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                                <span style="font-size:0.85rem;"><?php echo e($detail->statement->statement ?? '-'); ?></span>
                                <span style="font-weight:700; color:#4f7cff; font-size:0.85rem; white-space:nowrap; margin-left:8px;"><?php echo e(number_format($detail->score, 1)); ?>/5</span>
                            </div>
                            <div>
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <span style="font-size:0.9rem; color:<?php echo e($i <= $detail->score ? '#fbbf24' : '#e5e7eb'); ?>">★</span>
                                <?php endfor; ?>
                            </div>
                            <?php
                                $pct = ($detail->score / 5) * 100;
                                $color = $detail->score >= 4 ? '#22c55e' : ($detail->score >= 3 ? '#4f7cff' : ($detail->score >= 2 ? '#fbbf24' : '#ef4444'));
                            ?>
                            <div style="background:#f0f0f0; border-radius:99px; height:6px; margin-top:4px;">
                                <div style="width:<?php echo e($pct); ?>%; background:<?php echo e($color); ?>; height:100%; border-radius:99px;"></div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
            <div style="background:linear-gradient(135deg,#1e1e2e,#2d2d44); border-radius:12px; padding:16px; margin:16px; border:1px solid rgba(255,255,255,0.08);">
                <p style="line-height:1.7; color:rgba(255,255,255,0.8); margin:0;"><?php echo e($assessment->general_notes); ?></p>
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
            backgroundColor: 'rgba(79,124,255,0.25)',
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
                grid: { color: 'rgba(255,255,255,0.5)' },
                angleLines: { color: 'rgba(255,255,255,0.5)' },
                ticks: {
                    stepSize: 1,
                    callback: v => v+'★',
                    font: { size:10 },
                    color: 'white',
                    backdropColor: 'transparent'
                },
                pointLabels: {
                    font: { size:11, weight:'bold' },
                    color: 'white'
                },
            }
        }
    }
});
<?php endif; ?>

function toggleAccordion(index) {
    const content = document.getElementById(`accordion-${index}`);
    const arrow   = document.getElementById(`arrow-${index}`);
    if (content.style.display === 'none' || content.style.display === '') {
        content.style.display = 'flex';
        arrow.style.transform = 'rotate(180deg)';
    } else {
        content.style.display = 'none';
        arrow.style.transform = 'rotate(0deg)';
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/assessments/show.blade.php ENDPATH**/ ?>