

<?php $__env->startSection('title', 'Monitoring Presensi'); ?>
<?php $__env->startSection('page-title', 'Monitoring Presensi'); ?>
<?php $__env->startSection('page-sub', 'Pantau kehadiran karyawan secara realtime'); ?>

<?php $__env->startSection('content'); ?>

<div class="stats-grid" style="grid-template-columns: repeat(4,1fr); margin-bottom: 24px;">
    <div class="stat-card green">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?php echo e($totalHadir); ?></div>
        <div class="stat-label">Hadir</div>
        <div class="stat-sub"><?php echo e(\Carbon\Carbon::parse($tanggal)->format('d M Y')); ?></div>
    </div>
    <div class="stat-card amber">
        <div class="stat-icon">⏰</div>
        <div class="stat-value"><?php echo e($totalTerlambat); ?></div>
        <div class="stat-label">Terlambat</div>
        <div class="stat-sub"><?php echo e(\Carbon\Carbon::parse($tanggal)->format('d M Y')); ?></div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon">❌</div>
        <div class="stat-value"><?php echo e($totalAlfa); ?></div>
        <div class="stat-label">Alfa</div>
        <div class="stat-sub"><?php echo e(\Carbon\Carbon::parse($tanggal)->format('d M Y')); ?></div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon">🏃</div>
        <div class="stat-value"><?php echo e($totalPulangCepat); ?></div>
        <div class="stat-label">Pulang Cepat</div>
        <div class="stat-sub"><?php echo e(\Carbon\Carbon::parse($tanggal)->format('d M Y')); ?></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Log Presensi
        </div>
    </div>

    
    <div style="padding: 16px 22px; border-bottom: 1px solid var(--border);">
        <form method="GET" action="<?php echo e(route('admin.presensi.index')); ?>" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
            <div>
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control"
                    value="<?php echo e(request('tanggal', today()->toDateString())); ?>"
                    style="width: 180px;">
            </div>
            <div>
                <label class="form-label">Divisi</label>
                <select name="divisi_id" class="form-control" style="width: 180px;">
                    <option value="">Semua Divisi</option>
                    <?php $__currentLoopData = $divisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($d->id); ?>" <?php echo e(request('divisi_id') == $d->id ? 'selected' : ''); ?>>
                        <?php echo e($d->nama_divisi); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">Status</label>
                <select name="status_absen" class="form-control" style="width: 180px;">
                    <option value="">Semua Status</option>
                    <option value="tepat_waktu"  <?php echo e(request('status_absen') == 'tepat_waktu'  ? 'selected' : ''); ?>>Tepat Waktu</option>
                    <option value="terlambat"    <?php echo e(request('status_absen') == 'terlambat'    ? 'selected' : ''); ?>>Terlambat</option>
                    <option value="alfa"         <?php echo e(request('status_absen') == 'alfa'         ? 'selected' : ''); ?>>Alfa</option>
                </select>
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="<?php echo e(route('admin.presensi.index')); ?>" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Divisi</th>
                    <th>Shift</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Lokasi</th>
                    <th>Foto</th>
                    <th>Status Masuk</th>
                    <th>Status Pulang</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $presensis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                    <td style="font-family: var(--mono); font-size: 12px;">
                        <?php echo e(\Carbon\Carbon::parse($p->tanggal)->format('d M Y')); ?>

                    </td>
                    <td style="font-family: var(--mono); color: var(--green);">
                        <?php echo e($p->jam_masuk ?? '—'); ?>

                    </td>
                    <td style="font-family: var(--mono); color: var(--amber);">
                        <?php echo e($p->jam_pulang ?? '—'); ?>

                    </td>
                    <td>
                        <?php if($p->latitude && $p->longitude): ?>
                            <a href="https://maps.google.com/?q=<?php echo e($p->latitude); ?>,<?php echo e($p->longitude); ?>"
                                target="_blank" class="btn btn-secondary btn-sm">📍 Maps</a>
                        <?php else: ?>
                            <span style="color: var(--mid); font-size: 12px;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($p->foto_masuk): ?>
                            <a href="<?php echo e(asset('storage/' . $p->foto_masuk)); ?>" target="_blank" class="btn btn-secondary btn-sm">📸 Lihat</a>
                        <?php else: ?>
                            <span style="color: var(--mid); font-size: 12px;">—</span>
                        <?php endif; ?>
                    </td>
                   <td>
                        <?php
                            $badgeMasuk = [
                                'tepat_waktu' => ['class' => 'badge-green', 'label' => 'Tepat Waktu'],
                                'terlambat'   => ['class' => 'badge-amber', 'label' => 'Terlambat'],
                                'alfa'        => ['class' => 'badge-red',   'label' => 'Alfa'],
                            ];
                            $bm = $badgeMasuk[$p->status_absen] ?? ['class' => 'badge-gray', 'label' => '-'];
                        ?>
                        <span class="badge <?php echo e($bm['class']); ?>">
                            <span class="badge-dot"></span><?php echo e($bm['label']); ?>

                        </span>
                    </td>
                    <td>
                        <?php if($p->jam_pulang): ?>
                            <?php
                                $badgePulang = [
                                    'tepat_waktu'  => ['class' => 'badge-green', 'label' => 'Tepat Waktu'],
                                    'pulang_cepat' => ['class' => 'badge-amber', 'label' => 'Pulang Cepat'],
                                ];
                                $bp = $badgePulang[$p->status_pulang] ?? ['class' => 'badge-gray', 'label' => '-'];
                            ?>
                            <span class="badge <?php echo e($bp['class']); ?>">
                                <span class="badge-dot"></span><?php echo e($bp['label']); ?>

                            </span>
                        <?php else: ?>
                            <span style="color:var(--mid);font-size:12px;">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <p>Belum ada data presensi</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($presensis->hasPages()): ?>
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        <?php echo e($presensis->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/presensi/index.blade.php ENDPATH**/ ?>