

<?php $__env->startSection('title', 'Ajukan Koreksi Absen'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('error')): ?>
<div class="alert alert-danger">❌ <?php echo e(session('error')); ?></div>
<?php endif; ?>

<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
    <a href="<?php echo e(route('karyawan.koreksi-absen.index')); ?>" class="btn btn-secondary btn-inline">← Kembali</a>
    <div class="section-title" style="margin-bottom: 0;">Ajukan Koreksi Absen</div>
</div>

<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">Form Koreksi</div>
    </div>
    <div class="card-body">

        <div class="alert alert-warning" style="margin-bottom: 20px;">
            ⚠️ Koreksi absen hanya bisa diajukan untuk hari yang sudah lewat. Admin akan memverifikasi pengajuan kamu.
        </div>
<?php if($alfas->isEmpty()): ?>
<div class="alert alert-warning">
    ⚠️ Tidak ada presensi alfa yang bisa dikoreksi.
</div>
<?php endif; ?>
        <form method="POST" action="<?php echo e(route('karyawan.koreksi-absen.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
    <label class="form-label">Pilih Tanggal Alfa <span style="color:var(--red)">*</span></label>
    <select name="tanggal" id="input-tanggal" class="form-control">
        <option value="">-- Pilih Tanggal --</option>
        <?php $__empty_1 = true; $__currentLoopData = $alfas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <option value="<?php echo e($a->tanggal); ?>" <?php echo e(old('tanggal') == $a->tanggal ? 'selected' : ''); ?>>
            <?php echo e(\Carbon\Carbon::parse($a->tanggal)->translatedFormat('l, d F Y')); ?>

        </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <option value="" disabled>Tidak ada presensi alfa</option>
        <?php endif; ?>
    </select>
    <div id="nama-hari" style="font-size: 12px; color: var(--mid); margin-top: 6px; font-family: var(--mono);"></div>
    <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>

            <div class="form-group">
                <label class="form-label">Jam Masuk <span style="color:var(--red)">*</span></label>
                <input type="time" name="jam_masuk" class="form-control" value="<?php echo e(old('jam_masuk')); ?>">
                <?php $__errorArgs = ['jam_masuk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Jam Pulang</label>
                <input type="time" name="jam_pulang" class="form-control" value="<?php echo e(old('jam_pulang')); ?>">
                <div style="font-size: 11px; color: var(--mid); margin-top: 4px;">Opsional — isi jika juga lupa absen pulang</div>
                <?php $__errorArgs = ['jam_pulang'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Alasan <span style="color:var(--red)">*</span></label>
                <textarea name="alasan" class="form-control" rows="3"
                    placeholder="Jelaskan alasan koreksi absen..."><?php echo e(old('alasan')); ?></textarea>
                <?php $__errorArgs = ['alasan'];
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
<?php $__env->startPush('scripts'); ?>
<script>
    const inputTanggal = document.getElementById('input-tanggal');
    const namaHari     = document.getElementById('nama-hari');

    const hariMap = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const hariWarna = {
        0: 'var(--red)',   // Minggu
        6: 'var(--red)',   // Sabtu
    };

    function updateNamaHari() {
        const val = inputTanggal.value;
        if (!val) { namaHari.textContent = ''; return; }

        const date  = new Date(val + 'T00:00:00');
        const day   = date.getDay();
        const nama  = hariMap[day];
        const color = hariWarna[day] ?? 'var(--green)';
        const extra = (day === 0 || day === 6) ? ' — ⚠️ Hari libur, tidak bisa diajukan' : '';

        namaHari.style.color   = color;
        namaHari.textContent   = `📅 ${nama}${extra}`;
    }

    inputTanggal.addEventListener('change', updateNamaHari);

    // Trigger kalau ada old value
    if (inputTanggal.value) updateNamaHari();
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.karyawan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/karyawan/koreksi-absen/create.blade.php ENDPATH**/ ?>