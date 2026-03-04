

<?php $__env->startSection('title', 'Izin & Cuti'); ?>
<?php $__env->startSection('page-title', 'Izin & Cuti'); ?>
<?php $__env->startSection('page-sub', 'Kelola pengajuan izin dan cuti karyawan'); ?>

<?php $__env->startSection('content'); ?>

<div class="stats-grid" style="grid-template-columns: repeat(3,1fr); margin-bottom: 24px;">
    <div class="stat-card amber">
        <div class="stat-icon">⏳</div>
        <div class="stat-value"><?php echo e($totalPending); ?></div>
        <div class="stat-label">Menunggu</div>
        <div class="stat-sub">Perlu diproses</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?php echo e($totalApproved); ?></div>
        <div class="stat-label">Disetujui</div>
        <div class="stat-sub">Total approved</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon">❌</div>
        <div class="stat-value"><?php echo e($totalRejected); ?></div>
        <div class="stat-label">Ditolak</div>
        <div class="stat-sub">Total rejected</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Daftar Pengajuan
        </div>
        <?php if($totalPending > 0): ?>
        <span class="badge badge-amber"><?php echo e($totalPending); ?> pending</span>
        <?php endif; ?>
    </div>

    
    <div style="padding: 16px 22px; border-bottom: 1px solid var(--border);">
        <form method="GET" action="<?php echo e(route('admin.izin-cuti.index')); ?>" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-control" style="width: 160px;">
                    <option value="">Semua Status</option>
                    <option value="pending"  <?php echo e(request('status') == 'pending'  ? 'selected' : ''); ?>>Pending</option>
                    <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approved</option>
                    <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                </select>
            </div>
            <div>
                <label class="form-label">Jenis</label>
                <select name="jenis" class="form-control" style="width: 160px;">
                    <option value="">Semua Jenis</option>
                    <option value="izin"  <?php echo e(request('jenis') == 'izin'  ? 'selected' : ''); ?>>Izin</option>
                    <option value="sakit" <?php echo e(request('jenis') == 'sakit' ? 'selected' : ''); ?>>Sakit</option>
                    <option value="cuti"  <?php echo e(request('jenis') == 'cuti'  ? 'selected' : ''); ?>>Cuti</option>
                </select>
            </div>
            <div>
                <label class="form-label">Divisi</label>
                <select name="divisi_id" class="form-control" style="width: 160px;">
                    <option value="">Semua Divisi</option>
                    <?php $__currentLoopData = $divisis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($d->id); ?>" <?php echo e(request('divisi_id') == $d->id ? 'selected' : ''); ?>>
                        <?php echo e($d->nama_divisi); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control"
                    value="<?php echo e(request('tanggal')); ?>" style="width: 170px;">
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="<?php echo e(route('admin.izin-cuti.index')); ?>" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Divisi</th>
                    <th>Jenis</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Durasi</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $izinCutis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="emp-cell">
                            <div class="emp-avatar" style="background: linear-gradient(135deg, #4f7cff, #a855f7)">
                                <?php echo e(strtoupper(substr($ic->karyawan->nama, 0, 2))); ?>

                            </div>
                            <div>
                                <div class="emp-name"><?php echo e($ic->karyawan->nama); ?></div>
                                <div class="emp-sub"><?php echo e($ic->karyawan->user->nip); ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?php echo e($ic->karyawan->divisi->nama_divisi); ?></td>
                    <td>
                        <?php
                            $jenisMap = [
                                'izin'  => ['class' => 'badge-blue',  'label' => '📋 Izin'],
                                'sakit' => ['class' => 'badge-red',   'label' => '🏥 Sakit'],
                                'cuti'  => ['class' => 'badge-green', 'label' => '🏖️ Cuti'],
                            ];
                            $j = $jenisMap[$ic->jenis] ?? ['class' => 'badge-gray', 'label' => $ic->jenis];
                        ?>
                        <span class="badge <?php echo e($j['class']); ?>"><?php echo e($j['label']); ?></span>
                    </td>
                    <td style="font-family: var(--mono); font-size: 12px;">
                        <?php echo e(\Carbon\Carbon::parse($ic->tanggal_mulai)->format('d M Y')); ?>

                    </td>
                    <td style="font-family: var(--mono); font-size: 12px;">
                        <?php echo e(\Carbon\Carbon::parse($ic->tanggal_selesai)->format('d M Y')); ?>

                    </td>
                    <td>
                        <?php
                            $durasi = \Carbon\Carbon::parse($ic->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($ic->tanggal_selesai)) + 1;
                        ?>
                        <span class="badge badge-gray"><?php echo e($durasi); ?> hari</span>
                    </td>
                    <td style="max-width: 200px; font-size: 12px; color: var(--muted);">
                        <?php echo e($ic->keterangan ? Str::limit($ic->keterangan, 40) : '—'); ?>

                    </td>
                    <td>
                        <?php if($ic->status === 'pending'): ?>
                            <span class="badge badge-amber"><span class="badge-dot"></span>Pending</span>
                        <?php elseif($ic->status === 'approved'): ?>
                            <span class="badge badge-green"><span class="badge-dot"></span>Approved</span>
                        <?php else: ?>
                            <span class="badge badge-red"><span class="badge-dot"></span>Rejected</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($ic->status === 'pending'): ?>
                        <div style="display: flex; gap: 6px;">
                            <form method="POST" action="<?php echo e(route('admin.izin-cuti.approve', $ic)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-success btn-sm">✓ Approve</button>
                            </form>
                            <form method="POST" action="<?php echo e(route('admin.izin-cuti.reject', $ic)); ?>"
                                onsubmit="return confirm('Tolak pengajuan ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">✕ Reject</button>
                            </form>
                        </div>
                        <?php else: ?>
                            <div style="font-size: 11px; color: var(--mid); font-family: var(--mono);">
                                <?php echo e($ic->approvedBy->adminProfile->nama_admin ?? '-'); ?><br>
                                <?php echo e($ic->approved_at ? \Carbon\Carbon::parse($ic->approved_at)->format('d M Y') : ''); ?>

                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <p>Belum ada pengajuan izin atau cuti</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($izinCutis->hasPages()): ?>
    <div style="padding: 16px 22px; border-top: 1px solid var(--border);">
        <?php echo e($izinCutis->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/izin-cuti/index.blade.php ENDPATH**/ ?>