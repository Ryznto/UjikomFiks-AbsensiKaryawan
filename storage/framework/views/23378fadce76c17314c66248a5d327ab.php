

<?php $__env->startSection('title', 'Laporan'); ?>
<?php $__env->startSection('page-title', 'Laporan'); ?>
<?php $__env->startSection('page-sub', 'Download laporan presensi, rekap karyawan, dan izin cuti'); ?>

<?php $__env->startSection('content'); ?>


<div class="card" style="margin-bottom: 24px;">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.laporan.index')); ?>" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
            <div>
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-control" style="width: 160px;">
                    <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($m); ?>" <?php echo e($bulan == $m ? 'selected' : ''); ?>>
                        <?php echo e(\Carbon\Carbon::create()->month($m)->translatedFormat('F')); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-control" style="width: 120px;">
                    <?php $__currentLoopData = range(date('Y'), date('Y') - 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($y); ?>" <?php echo e($tahun == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">Divisi</label>
                <select name="divisi_id" class="form-control" style="width: 180px;">
                    <option value="">Semua Divisi</option>
                    <?php $__currentLoopData = $divisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($d->id); ?>" <?php echo e($divisiId == $d->id ? 'selected' : ''); ?>><?php echo e($d->nama_divisi); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>
    </div>
</div>


<div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card green">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?php echo e($totalHadir); ?></div>
        <div class="stat-label">Total Hadir</div>
        <div class="stat-sub"><?php echo e(\Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y')); ?></div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon">⏰</div>
        <div class="stat-value"><?php echo e($totalTerlambat); ?></div>
        <div class="stat-label">Total Terlambat</div>
        <div class="stat-sub"><?php echo e(\Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y')); ?></div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon">❌</div>
        <div class="stat-value"><?php echo e($totalAlfa); ?></div>
        <div class="stat-label">Total Alfa</div>
        <div class="stat-sub"><?php echo e(\Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y')); ?></div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon">📋</div>
        <div class="stat-value"><?php echo e($totalIzinCuti); ?></div>
        <div class="stat-label">Izin & Cuti</div>
        <div class="stat-sub"><?php echo e(\Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y')); ?></div>
    </div>
</div>

<?php
    $queryString = http_build_query(['bulan' => $bulan, 'tahun' => $tahun, 'divisi_id' => $divisiId]);
?>


<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">

    
    <div class="card">
        <div class="card-header">
            <div class="card-title">📅 Laporan Presensi</div>
        </div>
        <div class="card-body">
            <p style="font-size: 12px; color: var(--mid); margin-bottom: 16px;">
                Data presensi lengkap per karyawan termasuk jam masuk, jam pulang, dan status kehadiran.
            </p>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="<?php echo e(route('admin.laporan.presensi.excel')); ?>?<?php echo e($queryString); ?>" class="btn btn-success">
                    📊 Download Excel
                </a>
                <a href="<?php echo e(route('admin.laporan.presensi.pdf')); ?>?<?php echo e($queryString); ?>" class="btn btn-secondary">
                    📄 Download PDF
                </a>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <div class="card-title">👥 Rekap Karyawan</div>
        </div>
        <div class="card-body">
            <p style="font-size: 12px; color: var(--mid); margin-bottom: 16px;">
                Rekap kehadiran per karyawan: total hadir, terlambat, alfa, dan pulang cepat dalam sebulan.
            </p>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="<?php echo e(route('admin.laporan.karyawan.excel')); ?>?<?php echo e($queryString); ?>" class="btn btn-success">
                    📊 Download Excel
                </a>
                <a href="<?php echo e(route('admin.laporan.karyawan.pdf')); ?>?<?php echo e($queryString); ?>" class="btn btn-secondary">
                    📄 Download PDF
                </a>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <div class="card-title">🏖️ Laporan Izin & Cuti</div>
        </div>
        <div class="card-body">
            <p style="font-size: 12px; color: var(--mid); margin-bottom: 16px;">
                Data pengajuan izin, sakit, dan cuti karyawan beserta status persetujuan.
            </p>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="<?php echo e(route('admin.laporan.izin-cuti.excel')); ?>?<?php echo e($queryString); ?>" class="btn btn-success">
                    📊 Download Excel
                </a>
                <a href="<?php echo e(route('admin.laporan.izin-cuti.pdf')); ?>?<?php echo e($queryString); ?>" class="btn btn-secondary">
                    📄 Download PDF
                </a>
            </div>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/laporan/index.blade.php ENDPATH**/ ?>