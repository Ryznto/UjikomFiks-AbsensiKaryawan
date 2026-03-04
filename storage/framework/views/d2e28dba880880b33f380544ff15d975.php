

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>


<div style="margin-bottom: 20px;">
    <div style="font-size: 20px; font-weight: 800; letter-spacing: -0.5px;">
        👋 Halo, <?php echo e(explode(' ', $karyawan->nama)[0]); ?>!
    </div>
    <div style="font-size: 12px; color: var(--mid); font-family: var(--mono); margin-top: 4px;">
        <?php echo e(\Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?>

    </div>
</div>


<div class="card fade-in" style="margin-bottom: 16px;">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Absensi Hari Ini
        </div>
        <?php if($presensiHariIni): ?>
            <?php
                $badgeMap = [
                    'tepat_waktu'  => ['class' => 'badge-green', 'label' => 'Tepat Waktu'],
                    'terlambat'    => ['class' => 'badge-amber', 'label' => 'Terlambat'],
                    'pulang_cepat' => ['class' => 'badge-amber', 'label' => 'Pulang Cepat'],
                    'alfa'         => ['class' => 'badge-red',   'label' => 'Alfa'],
                ];
                $b = $badgeMap[$presensiHariIni->status_absen] ?? ['class' => 'badge-gray', 'label' => '-'];
            ?>
            <span class="badge <?php echo e($b['class']); ?>"><span class="badge-dot"></span><?php echo e($b['label']); ?></span>
        <?php else: ?>
            <span class="badge badge-gray">Belum Absen</span>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <div class="info-row">
            <span class="info-label">🌅 Jam Masuk</span>
            <span class="info-value mono" style="color: <?php echo e($presensiHariIni?->jam_masuk ? 'var(--green)' : 'var(--mid)'); ?>">
                <?php echo e($presensiHariIni?->jam_masuk ?? '—'); ?>

            </span>
        </div>
        <div class="info-row">
            <span class="info-label">🌆 Jam Pulang</span>
            <span class="info-value mono" style="color: <?php echo e($presensiHariIni?->jam_pulang ? 'var(--amber)' : 'var(--mid)'); ?>">
                <?php echo e($presensiHariIni?->jam_pulang ?? '—'); ?>

            </span>
        </div>
        <div class="info-row">
            <span class="info-label">⏱️ Shift</span>
            <span class="info-value">
                <?php echo e($karyawan->shift->nama_shift); ?>

                <span style="color: var(--mid); font-size: 11px; font-family: var(--mono);">
                    <?php echo e($karyawan->shift->jam_masuk); ?> - <?php echo e($karyawan->shift->jam_pulang); ?>

                </span>
            </span>
        </div>

        <div style="margin-top: 16px; display: flex; gap: 10px;">
            <?php if(!$presensiHariIni || !$presensiHariIni->jam_masuk): ?>
                <a href="<?php echo e(route('karyawan.presensi.index')); ?>" class="btn btn-primary">
                    📷 Absen Masuk
                </a>
            <?php elseif(!$presensiHariIni->jam_pulang): ?>
                <a href="<?php echo e(route('karyawan.presensi.index')); ?>" class="btn btn-success">
                    🏠 Absen Pulang
                </a>
            <?php else: ?>
                <div class="btn btn-secondary" style="cursor: default;">
                    ✅ Absensi Selesai
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
    <div class="card fade-in" style="margin-bottom: 0;">
        <div class="card-body" style="text-align: center; padding: 20px 16px;">
            <div style="font-size: 32px; font-weight: 800; font-family: var(--mono); color: var(--green); letter-spacing: -1px;">
                <?php echo e($hadirBulanIni); ?>

            </div>
            <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">Hadir Bulan Ini</div>
        </div>
    </div>
    <div class="card fade-in" style="margin-bottom: 0;">
        <div class="card-body" style="text-align: center; padding: 20px 16px;">
            <div style="font-size: 32px; font-weight: 800; font-family: var(--mono); color: var(--amber); letter-spacing: -1px;">
                <?php echo e($terlambatBulanIni); ?>

            </div>
            <div style="font-size: 12px; color: var(--muted); margin-top: 4px;">Terlambat Bulan Ini</div>
        </div>
    </div>
</div>


<div class="card fade-in" style="margin-bottom: 16px;">
    <div class="card-header">
        <div class="card-title">Info Akun</div>
    </div>
    <div class="card-body">
        <div class="info-row">
            <span class="info-label">NIP</span>
            <span class="info-value mono"><?php echo e(Auth::user()->nip); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Divisi</span>
            <span class="info-value"><?php echo e($karyawan->divisi->nama_divisi); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Jabatan</span>
            <span class="info-value"><?php echo e($karyawan->jabatan->nama_jabatan); ?></span>
        </div>
    </div>
</div>


<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">Riwayat Terakhir</div>
        <a href="<?php echo e(route('karyawan.presensi.index')); ?>" class="card-action">Semua →</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Masuk</th>
                    <th>Pulang</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $riwayatPresensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-family: var(--mono); font-size: 11px;">
                        <?php echo e(\Carbon\Carbon::parse($p->tanggal)->format('d M')); ?>

                    </td>
                    <td style="font-family: var(--mono); color: var(--green); font-size: 12px;">
                        <?php echo e($p->jam_masuk ?? '—'); ?>

                    </td>
                    <td style="font-family: var(--mono); color: var(--amber); font-size: 12px;">
                        <?php echo e($p->jam_pulang ?? '—'); ?>

                    </td>
                    <td>
                        <?php
                            $badgeMap = [
                                'tepat_waktu'  => ['class' => 'badge-green', 'label' => 'Tepat'],
                                'terlambat'    => ['class' => 'badge-amber', 'label' => 'Lambat'],
                                'pulang_cepat' => ['class' => 'badge-amber', 'label' => 'P.Cepat'],
                                'alfa'         => ['class' => 'badge-red',   'label' => 'Alfa'],
                            ];
                            $b = $badgeMap[$p->status_absen] ?? ['class' => 'badge-gray', 'label' => '-'];
                        ?>
                        <span class="badge <?php echo e($b['class']); ?>" style="font-size: 10px; padding: 2px 7px;">
                            <?php echo e($b['label']); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <p>Belum ada riwayat presensi</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.karyawan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/karyawan/dashboard.blade.php ENDPATH**/ ?>