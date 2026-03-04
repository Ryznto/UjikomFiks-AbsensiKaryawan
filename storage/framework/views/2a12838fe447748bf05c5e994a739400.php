

<?php $__env->startSection('title', 'Izin & Cuti'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger">❌ <?php echo e(session('error')); ?></div>
<?php endif; ?>


<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Ajukan Izin / Cuti
        </div>
    </div>
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('karyawan.izin-cuti.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label class="form-label">Jenis <span style="color:var(--red)">*</span></label>
                <select name="jenis" class="form-control">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="izin"  <?php echo e(old('jenis') == 'izin'  ? 'selected' : ''); ?>>📋 Izin</option>
                    <option value="sakit" <?php echo e(old('jenis') == 'sakit' ? 'selected' : ''); ?>>🏥 Sakit</option>
                    <option value="cuti"  <?php echo e(old('jenis') == 'cuti'  ? 'selected' : ''); ?>>🏖️ Cuti</option>
                </select>
                <?php $__errorArgs = ['jenis'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Mulai <span style="color:var(--red)">*</span></label>
                <input type="date" name="tanggal_mulai" class="form-control"
                    value="<?php echo e(old('tanggal_mulai')); ?>"
                    min="<?php echo e(today()->toDateString()); ?>">
                <?php $__errorArgs = ['tanggal_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Selesai <span style="color:var(--red)">*</span></label>
                <input type="date" name="tanggal_selesai" class="form-control"
                    value="<?php echo e(old('tanggal_selesai')); ?>"
                    min="<?php echo e(today()->toDateString()); ?>">
                <?php $__errorArgs = ['tanggal_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3"
                    placeholder="Opsional — tulis keterangan jika perlu"><?php echo e(old('keterangan')); ?></textarea>
                <?php $__errorArgs = ['keterangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
        </form>
    </div>
</div>


<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">Riwayat Pengajuan</div>
    </div>

 
<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">Riwayat Pengajuan</div>
    </div>

    
    <div style="padding: 12px 18px; border-bottom: 1px solid var(--border);">
        <form method="GET" action="<?php echo e(route('karyawan.izin-cuti.index')); ?>" style="display: flex; gap: 8px; flex-wrap: wrap;">
            <select name="bulan" class="form-control" style="flex: 1; min-width: 100px;">
                <option value="">Semua Bulan</option>
                <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($m); ?>" <?php echo e(request('bulan') == $m ? 'selected' : ''); ?>>
                    <?php echo e(\Carbon\Carbon::create()->month($m)->translatedFormat('F')); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="tahun" class="form-control" style="flex: 1; min-width: 80px;">
                <option value="">Semua Tahun</option>
                <?php $__currentLoopData = range(date('Y'), date('Y') - 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($y); ?>" <?php echo e(request('tahun') == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="jenis" class="form-control" style="flex: 1; min-width: 90px;">
                <option value="">Semua Jenis</option>
                <option value="izin"  <?php echo e(request('jenis') == 'izin'  ? 'selected' : ''); ?>>📋 Izin</option>
                <option value="sakit" <?php echo e(request('jenis') == 'sakit' ? 'selected' : ''); ?>>🏥 Sakit</option>
                <option value="cuti"  <?php echo e(request('jenis') == 'cuti'  ? 'selected' : ''); ?>>🏖️ Cuti</option>
            </select>
            <select name="status" class="form-control" style="flex: 1; min-width: 100px;">
                <option value="">Semua Status</option>
                <option value="pending"  <?php echo e(request('status') == 'pending'  ? 'selected' : ''); ?>>Pending</option>
                <option value="approved" <?php echo e(request('status') == 'approved' ? 'selected' : ''); ?>>Approved</option>
                <option value="rejected" <?php echo e(request('status') == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
            </select>
            <div style="display: flex; gap: 6px; width: 100%;">
                <button type="submit" class="btn btn-primary btn-inline" style="flex: 1;">Filter</button>
                <a href="<?php echo e(route('karyawan.izin-cuti.index')); ?>" class="btn btn-secondary btn-inline" style="flex: 1; text-align: center;">Reset</a>
            </div>
        </form>
    </div>

    <?php $__empty_1 = true; $__currentLoopData = $izinCutis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div style="padding: 14px 18px; border-bottom: 1px solid rgba(42,47,66,0.5);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <?php
                    $jenisMap = [
                        'izin'  => ['class' => 'badge-blue',  'label' => '📋 Izin'],
                        'sakit' => ['class' => 'badge-red',   'label' => '🏥 Sakit'],
                        'cuti'  => ['class' => 'badge-green', 'label' => '🏖️ Cuti'],
                    ];
                    $j = $jenisMap[$ic->jenis] ?? ['class' => 'badge-gray', 'label' => $ic->jenis];
                ?>
                <span class="badge <?php echo e($j['class']); ?>"><?php echo e($j['label']); ?></span>
                <?php
                    $durasi = \Carbon\Carbon::parse($ic->tanggal_mulai)
                        ->diffInDays(\Carbon\Carbon::parse($ic->tanggal_selesai)) + 1;
                ?>
                <span class="badge badge-gray"><?php echo e($durasi); ?> hari</span>
            </div>
            <?php if($ic->status === 'pending'): ?>
                <span class="badge badge-amber"><span class="badge-dot"></span>Pending</span>
            <?php elseif($ic->status === 'approved'): ?>
                <span class="badge badge-green"><span class="badge-dot"></span>Approved</span>
            <?php else: ?>
                <span class="badge badge-red"><span class="badge-dot"></span>Rejected</span>
            <?php endif; ?>
        </div>
        <div style="font-size: 12px; color: var(--mid); font-family: var(--mono);">
            <?php echo e(\Carbon\Carbon::parse($ic->tanggal_mulai)->format('d M Y')); ?>

            <?php if($ic->tanggal_mulai != $ic->tanggal_selesai): ?>
                → <?php echo e(\Carbon\Carbon::parse($ic->tanggal_selesai)->format('d M Y')); ?>

            <?php endif; ?>
        </div>
        <?php if($ic->keterangan): ?>
        <div style="font-size: 12px; color: var(--muted); margin-top: 6px;">
            <?php echo e($ic->keterangan); ?>

        </div>
        <?php endif; ?>
        <?php if($ic->status !== 'pending'): ?>
        <div style="font-size: 11px; color: var(--mid); margin-top: 6px; font-family: var(--mono);">
            Diproses: <?php echo e($ic->approved_at ? \Carbon\Carbon::parse($ic->approved_at)->format('d M Y') : '-'); ?>

        </div>
        <?php endif; ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="empty-state">
        <p>Belum ada riwayat pengajuan</p>
    </div>
    <?php endif; ?>

    <?php if($izinCutis->hasPages()): ?>
    <div style="padding: 14px 18px; border-top: 1px solid var(--border);">
        <?php echo e($izinCutis->links()); ?>

    </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.karyawan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/karyawan/izin-cuti/index.blade.php ENDPATH**/ ?>