

<?php $__env->startSection('title', 'Rapor Penilaian Saya'); ?>

<?php $__env->startSection('content'); ?>

    
    <div style="margin-bottom:20px;">
        <div style="font-size:20px; font-weight:800; letter-spacing:-0.5px;">
            📊 Rapor Penilaian Saya
        </div>
        <div style="font-size:12px; color:var(--mid); font-family:var(--mono); margin-top:4px;">
            Periode: <?php echo e($period); ?>

        </div>
    </div>

    
    <form method="GET" style="margin-bottom:16px;">
        <input type="month" name="period_month" class="form-control" value="<?php echo e(now()->format('Y-m')); ?>"
            onchange="this.form.submit()">
    </form>

    
    <?php if($currentAssessment): ?>
        <div class="card fade-in" style="margin-bottom:16px; text-align:center;">
            <div class="card-body" style="padding:24px;">
                <div style="font-size:12px; color:var(--mid); margin-bottom:8px;">Rata-rata Nilai – <?php echo e($period); ?>

                </div>
                <div
                    style="font-size:48px; font-weight:800; font-family:var(--mono); color:#4f7cff; letter-spacing:-2px; line-height:1;">
                    <?php echo e(number_format($currentAssessment->average_score, 1)); ?>

                    <span style="font-size:20px; color:var(--mid);">/5</span>
                </div>
                <div style="margin-top:12px;">
                    <span class="badge badge-<?php echo e($currentAssessment->score_badge); ?>"
                        style="font-size:0.9rem; padding:6px 16px;">
                        <span class="badge-dot"></span><?php echo e($currentAssessment->score_label); ?>

                    </span>
                </div>
                <div style="margin-top:12px; font-size:0.8rem; color:var(--mid);">
                    Dinilai oleh: <strong><?php echo e($currentAssessment->evaluator->adminProfile->nama_admin ?? '-'); ?></strong>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="card fade-in" style="margin-bottom:16px;">
            <div class="card-body" style="text-align:center; padding:32px;">
                <div style="font-size:32px; margin-bottom:8px;">⏳</div>
                <div style="font-weight:600; margin-bottom:4px;">Belum ada penilaian</div>
                <div style="font-size:0.8rem; color:var(--mid);">Tunggu admin memberikan evaluasi untuk periode ini</div>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if(!empty($radarLabels)): ?>
        <div class="card fade-in" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-dot"></div>
                    Grafik Performa
                </div>
            </div>
            <div style="padding:20px;">
                <canvas id="radarChart" height="300"></canvas>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if($currentAssessment && $currentAssessment->details->count() > 0): ?>
        <div class="card fade-in" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-title-dot"></div>
                    Detail Nilai
                </div>
            </div>
            <div style="padding:12px 16px; display:flex; flex-direction:column; gap:8px;">
                <?php $__currentLoopData = $currentAssessment->details->groupBy('statement.category.name'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryName => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $avgCat = round($details->avg('score'), 1); ?>
                    <div class="accordion-item" style="border:1px solid #e5e7eb; border-radius:12px; overflow:hidden;">
                        <div class="accordion-header" onclick="toggleAccordion(<?php echo e($loop->index); ?>)"
                            style="display:flex; justify-content:space-between; align-items:center; padding:14px 16px; cursor:pointer; background:#1e1e2e; color:white;">
                            <div style="font-weight:700;"><?php echo e($categoryName); ?></div>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div>
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <span style="color:<?php echo e($i <= $avgCat ? '#fbbf24' : '#555'); ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                                <span
                                    style="font-weight:700; color:#4f7cff; font-size:0.9rem;"><?php echo e($avgCat); ?>/5</span>
                                <span id="arrow-<?php echo e($loop->index); ?>"
                                    style="color:white; transition:transform 0.3s;">▼</span>
                            </div>
                        </div>
                        <div id="accordion-<?php echo e($loop->index); ?>"
                            style="display:none; padding:12px 16px; flex-direction:column; gap:12px;">
                            <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div>
                                    <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                                        <span style="font-size:0.85rem;"><?php echo e($detail->statement->statement ?? '-'); ?></span>
                                        <span
                                            style="font-weight:700; color:#4f7cff; font-size:0.85rem; white-space:nowrap; margin-left:8px;"><?php echo e(number_format($detail->score, 1)); ?>/5</span>
                                    </div>
                                    <div>
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <span
                                                style="font-size:0.9rem; color:<?php echo e($i <= $detail->score ? '#fbbf24' : '#e5e7eb'); ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                    <?php
                                        $pct = ($detail->score / 5) * 100;
                                        $color =
                                            $detail->score >= 4
                                                ? '#22c55e'
                                                : ($detail->score >= 3
                                                    ? '#4f7cff'
                                                    : ($detail->score >= 2
                                                        ? '#fbbf24'
                                                        : '#ef4444'));
                                    ?>
                                    <div style="background:#f0f0f0; border-radius:99px; height:6px; margin-top:4px;">
                                        <div
                                            style="width:<?php echo e($pct); ?>%; background:<?php echo e($color); ?>; height:100%; border-radius:99px;">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($currentAssessment->general_notes): ?>
                    <div
                        style="background:linear-gradient(135deg,#1e1e2e,#2d2d44); border-radius:12px; padding:16px; margin-top:4px; border:1px solid rgba(255,255,255,0.08);">
                        <div style="font-weight:600; margin-bottom:8px; color:white;">💬 Catatan dari Penilai</div>
                        <p style="margin:0; line-height:1.7; font-size:0.9rem; color:rgba(255,255,255,0.7);">
                            <?php echo e($currentAssessment->general_notes); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    
    <div class="card fade-in">
        <div class="card-header">
            <div class="card-title">
                <div class="card-title-dot"></div>
                Riwayat Penilaian
            </div>
            <span style="font-size:0.8rem; color:var(--mid);"><?php echo e($history->count()); ?> periode</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Tanggal</th>
                        <th>Rata-rata</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td style="font-weight:600;"><?php echo e($item->period); ?></td>
                            <td style="font-family:var(--mono); font-size:0.8rem;">
                                <?php echo e($item->assessment_date->format('d M Y')); ?>

                            </td>
                            <td>
                                <span style="font-weight:700; color:#4f7cff; font-family:var(--mono);">
                                    <?php echo e(number_format($item->average_score, 1)); ?>

                                </span>
                                <span style="color:var(--mid); font-size:0.8rem;">/5</span>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo e($item->score_badge); ?>">
                                    <span class="badge-dot"></span><?php echo e($item->score_label); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('karyawan.assessments.show', $item)); ?>"
                                    style="color:#4f7cff; text-decoration:none; font-size:0.85rem;">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <p>Belum ada riwayat penilaian</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
                        label: 'Nilai Saya',
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
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        r: {
                            min: 0,
                            max: 5,
                            grid: {
                                color: 'rgba(255,255,255,0.5)'
                            },
                            angleLines: {
                                color: 'rgba(255,255,255,0.5)'
                            },
                            ticks: {
                                stepSize: 1,
                                callback: v => v + '★',
                                font: {
                                    size: 10
                                },
                                color: 'white',
                                backdropColor: 'transparent'
                            },
                            pointLabels: {
                                font: {
                                    size: 11,
                                    weight: 'bold'
                                },
                                color: 'white'
                            },
                        }
                    }
                }
            });
        <?php endif; ?>

        function toggleAccordion(index) {
            const content = document.getElementById(`accordion-${index}`);
            const arrow = document.getElementById(`arrow-${index}`);
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

<?php echo $__env->make('layouts.karyawan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/karyawan/assessments/my_report.blade.php ENDPATH**/ ?>