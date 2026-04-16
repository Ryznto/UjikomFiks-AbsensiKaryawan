<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> — AbsensiKu</title>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f7cff">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="AbsensiKu">
    <link rel="apple-touch-icon" href="/icons/icon-192.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/karyawan.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="app-mobile">

        
        <header class="mobile-header">
            <div class="mobile-header-left">
                <div class="logo-mark-sm">A</div>
                <div>
                    <div class="mobile-header-title">AbsensiKu</div>
                    <div class="mobile-header-sub"><?php echo e(auth()->user()->karyawan->nama ?? ''); ?></div>
                </div>
            </div>
            <div class="mobile-header-right">
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-icon" title="Logout">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </header>

        
        <main class="mobile-content">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        
        <nav class="bottom-nav">
            <a href="<?php echo e(route('karyawan.dashboard')); ?>" class="bottom-nav-item <?php echo e(request()->routeIs('karyawan.dashboard') ? 'active' : ''); ?>">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
                </svg>
                <span>Dashboard</span>
            </a>
            
            <a href="<?php echo e(route('karyawan.presensi.index')); ?>" class="bottom-nav-item <?php echo e(request()->routeIs('karyawan.presensi.*') ? 'active' : ''); ?>">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Presensi</span>
            </a>
            
            <a href="<?php echo e(route('karyawan.izin-cuti.index')); ?>" class="bottom-nav-item <?php echo e(request()->routeIs('karyawan.izin-cuti.*') ? 'active' : ''); ?>">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Izin Cuti</span>
            </a>

            
            <a href="<?php echo e(route('karyawan.assessments.my-report')); ?>" class="bottom-nav-item <?php echo e(request()->routeIs('karyawan.assessments.*') ? 'active' : ''); ?>">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                <span>Rapor</span>
            </a>

            
            <a href="<?php echo e(route('karyawan.integrity.index')); ?>" class="bottom-nav-item <?php echo e(request()->routeIs('karyawan.integrity.*') ? 'active' : ''); ?>">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
                <span>Integritas</span>
            </a>
            

            <a href="<?php echo e(route('karyawan.profil')); ?>" class="bottom-nav-item <?php echo e(request()->routeIs('karyawan.profil*') ? 'active' : ''); ?>">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Profil</span>
            </a>
        </nav>

    </div>

    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js');
        }
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/layouts/karyawan.blade.php ENDPATH**/ ?>