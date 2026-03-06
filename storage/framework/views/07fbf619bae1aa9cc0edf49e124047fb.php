

<?php $__env->startSection('title', 'Profil Admin'); ?>
<?php $__env->startSection('page-title', 'Profil Saya'); ?>
<?php $__env->startSection('page-sub', 'Kelola informasi akun admin'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
<?php endif; ?>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">

    
    <div class="card">
        <div class="card-header">
            <div class="card-title">Informasi Profil</div>
        </div>
        <div class="card-body">

            
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
                <div style="width: 72px; height: 72px; border-radius: 16px; overflow: hidden; flex-shrink: 0; background: linear-gradient(135deg, #4f7cff, #a855f7); display: flex; align-items: center; justify-content: center;">
                    <?php if($profil->foto): ?>
                        <img src="<?php echo e(asset('storage/' . $profil->foto)); ?>"
                            style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <span style="font-size: 28px; font-weight: 800; color: white;">
                            <?php echo e(strtoupper(substr($profil->nama_admin, 0, 2))); ?>

                        </span>
                    <?php endif; ?>
                </div>
                <div>
                    <div style="font-size: 18px; font-weight: 700;"><?php echo e($profil->nama_admin); ?></div>
                    <div style="font-size: 12px; color: var(--mid); font-family: var(--mono); margin-top: 3px;"><?php echo e($user->nip); ?></div>
                    <span class="badge badge-blue" style="margin-top: 6px;">Admin</span>
                </div>
            </div>

            <form method="POST" action="<?php echo e(route('admin.profil.update')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="form-group">
                    <label class="form-label">Nama Admin <span style="color:var(--red)">*</span></label>
                    <input type="text" name="nama_admin" class="form-control"
                        value="<?php echo e(old('nama_admin', $profil->nama_admin)); ?>">
                    <?php $__errorArgs = ['nama_admin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                        value="<?php echo e(old('email', $profil->email)); ?>" placeholder="Opsional">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" class="form-control"
                        value="<?php echo e(old('no_hp', $profil->no_hp)); ?>" placeholder="Opsional">
                    <?php $__errorArgs = ['no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="foto" class="form-control" accept="image/*"
                        onchange="previewFoto(this)">
                    <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div id="foto-preview" style="margin-top: 10px; display: none;">
                        <img id="preview-img" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover; border: 1px solid var(--border);">
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <div class="card-title">Ganti Password</div>
        </div>
        <div class="card-body">

            <div style="background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 14px 16px; margin-bottom: 20px;">
                <div style="font-size: 12px; color: var(--mid);">
                    🔐 NIP Login: <strong style="color: var(--white); font-family: var(--mono);"><?php echo e($user->nip); ?></strong>
                </div>
            </div>

            <form method="POST" action="<?php echo e(route('admin.profil.password')); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="form-group">
                    <label class="form-label">Password Lama <span style="color:var(--red)">*</span></label>
                    <input type="password" name="password_lama" class="form-control"
                        placeholder="Masukkan password saat ini">
                    <?php $__errorArgs = ['password_lama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Password Baru <span style="color:var(--red)">*</span></label>
                    <input type="password" name="password_baru" class="form-control"
                        placeholder="Minimal 8 karakter">
                    <?php $__errorArgs = ['password_baru'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="form-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru <span style="color:var(--red)">*</span></label>
                    <input type="password" name="password_baru_confirmation" class="form-control"
                        placeholder="Ulangi password baru">
                </div>

                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('foto-preview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/profil/index.blade.php ENDPATH**/ ?>