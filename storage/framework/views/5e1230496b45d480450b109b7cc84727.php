

<?php $__env->startSection('title', 'Form Penilaian'); ?>
<?php $__env->startSection('page-title', 'Form Penilaian'); ?>
<?php $__env->startSection('page-sub', 'Penilaian untuk: ' . $karyawan->nama); ?>

<?php $__env->startSection('content'); ?>

<a href="<?php echo e(route('admin.assessments.index', ['period' => $period])); ?>"
    style="display:inline-flex; align-items:center; gap:6px; margin-bottom:20px; color:var(--mid); text-decoration:none;">
    ← Kembali ke Dashboard
</a>


<div class="card" style="margin-bottom:20px;">
    <div style="padding:24px; display:flex; align-items:center; gap:16px;">
        <div style="width:72px; height:72px; border-radius:50%; background:linear-gradient(135deg,#4f7cff,#a855f7); display:flex; align-items:center; justify-content:center; font-size:28px; color:white; font-weight:700; flex-shrink:0;">
            <?php echo e(strtoupper(substr($karyawan->nama, 0, 2))); ?>

        </div>
        <div>
            <h3 style="margin:0 0 4px;"><?php echo e($karyawan->nama); ?></h3>
            <span style="color:var(--mid);"><?php echo e($karyawan->jabatan->nama_jabatan ?? '-'); ?> • <?php echo e($karyawan->divisi->nama_divisi ?? '-'); ?></span>
            <div style="margin-top:8px;">
                <span class="badge badge-blue">Periode: <?php echo e($period); ?></span>
                <?php if($existingAssessment): ?>
                    <span class="badge badge-green" style="margin-left:6px;">✏️ Edit Mode</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<form action="<?php echo e(route('admin.assessments.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="evaluatee_id" value="<?php echo e($karyawan->id); ?>">
    <input type="hidden" name="period" value="<?php echo e($period); ?>">

    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
            <div class="card-title">
                <div class="card-title-dot"></div>
                <?php echo e($category->name); ?>

            </div>
        </div>
        <div style="padding:4px 0; color:var(--mid); font-size:0.85rem; padding-left:20px; margin-bottom:8px;">
            <?php echo e($category->description); ?>

        </div>

        <div style="padding:0 20px 20px; display:flex; flex-direction:column; gap:16px;">
            <?php $__currentLoopData = $category->statements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $existingScore = $existingAssessment
                    ? $existingAssessment->details->firstWhere('statement_id', $statement->id)?->score
                    : null;
                $existingScore = $existingScore ? (int) $existingScore : null;
            ?>
            <div class="statement-item" id="card-<?php echo e($statement->id); ?>"
                style="border:1px solid #e5e7eb; border-radius:12px; padding:16px 20px; transition:border-color 0.2s; <?php echo e($existingScore ? 'border-color:#22c55e; background:#f0fff4;' : ''); ?>">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                    <div style="flex:1;">
                        <div style="font-size:0.9rem; font-weight:500;"><?php echo e($loop->iteration); ?>. <?php echo e($statement->statement); ?></div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        
                        <div class="star-rating" id="stars-<?php echo e($statement->id); ?>">
                            <?php for($i = 5; $i >= 1; $i--): ?>
                            <input type="radio"
                                name="scores[<?php echo e($statement->id); ?>]"
                                id="star-<?php echo e($statement->id); ?>-<?php echo e($i); ?>"
                                value="<?php echo e($i); ?>"
                                <?php echo e($existingScore == $i ? 'checked' : ''); ?>

                                required>
                            <label for="star-<?php echo e($statement->id); ?>-<?php echo e($i); ?>">★</label>
                            <?php endfor; ?>
                        </div>
                        <span class="score-badge" id="badge-<?php echo e($statement->id); ?>"
                            style="<?php echo e($existingScore ? '' : 'display:none;'); ?>">
                            <?php echo e($existingScore ? $existingScore . '/5' : ''); ?>

                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <div class="card" style="margin-bottom:20px;">
        <div style="padding:20px;">
            <label style="font-weight:600; display:block; margin-bottom:8px;">💬 Catatan / Feedback</label>
            <textarea name="general_notes" class="form-control" rows="5"
                placeholder="Tuliskan catatan evaluasi, saran pengembangan, atau feedback..."><?php echo e($existingAssessment?->general_notes); ?></textarea>
        </div>
    </div>

    
    <div style="display:flex; gap:12px; justify-content:flex-end;">
        <a href="<?php echo e(route('admin.assessments.index', ['period' => $period])); ?>"
            style="padding:12px 24px; border:1px solid #ddd; border-radius:8px; text-decoration:none; color:var(--mid);">
            Batal
        </a>
        <button type="submit" class="btn-primary">
            💾 Simpan Penilaian
        </button>
    </div>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        gap: 4px;
    }
    .star-rating input { display: none; }
    .star-rating label {
        font-size: 2rem;
        color: #ddd;
        cursor: pointer;
        transition: color 0.2s, transform 0.1s;
        line-height: 1;
    }
    .star-rating label:hover,
    .star-rating label:hover ~ label,
    .star-rating input:checked ~ label {
        color: #fbbf24;
    }
    .star-rating label:hover { transform: scale(1.2); }
    .score-badge {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 99px;
        background: #4f7cff;
        color: white;
    }
    .statement-item.rated {
        border-color: #22c55e !important;
        background: #f0fff4;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const scoreLabels = {
        1: 'Sangat Kurang',
        2: 'Kurang',
        3: 'Cukup',
        4: 'Baik',
        5: 'Sangat Baik',
    };

    document.querySelectorAll('.star-rating input').forEach(input => {
        input.addEventListener('change', function () {
            const stmtId = this.name.match(/\[(\d+)\]/)[1];
            const score  = parseInt(this.value);
            const badge  = document.getElementById(`badge-${stmtId}`);
            const card   = document.getElementById(`card-${stmtId}`);

            badge.style.display = 'inline-block';
            badge.textContent   = score + '/5 – ' + scoreLabels[score];
            card.classList.add('rated');
        });
    });

    // Init badge untuk nilai yang sudah ada
    document.querySelectorAll('.star-rating input:checked').forEach(input => {
        const stmtId = input.name.match(/\[(\d+)\]/)[1];
        const score  = parseInt(input.value);
        const badge  = document.getElementById(`badge-${stmtId}`);
        if (badge && score) {
            badge.style.display = 'inline-block';
            badge.textContent   = score + '/5 – ' + scoreLabels[score];
            document.getElementById(`card-${stmtId}`).classList.add('rated');
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/assessments/create.blade.php ENDPATH**/ ?>