<header class="navbar">
    <div>
        <div class="navbar-title"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></div>
        <div class="navbar-sub"><?php echo $__env->yieldContent('page-sub', \Carbon\Carbon::now()->translatedFormat('l, d F Y')); ?></div>
    </div>
    <div class="navbar-right">
        <?php echo $__env->yieldContent('page-actions'); ?>
    </div>
</header><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/partials/navbar.blade.php ENDPATH**/ ?>