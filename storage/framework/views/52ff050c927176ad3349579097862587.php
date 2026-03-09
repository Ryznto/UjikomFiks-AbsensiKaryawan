<aside class="sidebar">
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-logo">
        <div class="logo-mark">A</div>
        <div>
            <div class="logo-text">AbsensiKu</div>
            <div class="logo-sub">Admin Panel</div>
        </div>
    </a>

    <div class="nav-section">
        <div class="nav-label">Utama</div>
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a href="<?php echo e(route('admin.karyawan.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.karyawan.*') ? 'active' : ''); ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            Karyawan
        </a>
        <a href="<?php echo e(route('admin.presensi.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.presensi.*') ? 'active' : ''); ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Presensi
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-label">Pengajuan</div>
        <a href="<?php echo e(route('admin.izin-cuti.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.izin-cuti.*') ? 'active' : ''); ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Izin & Cuti
        </a>
        <a href="<?php echo e(route('admin.koreksi-absen.index')); ?>"
    class="nav-item <?php echo e(request()->routeIs('admin.koreksi-absen.*') ? 'active' : ''); ?>">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
    </svg>
    Koreksi Absen
    <?php $pendingKoreksi = \App\Models\KoreksiAbsen::where('status','pending')->count(); ?>
    <?php if($pendingKoreksi > 0): ?>
        <span class="nav-badge"><?php echo e($pendingKoreksi); ?></span>
    <?php endif; ?>
</a>
<a href="<?php echo e(route('admin.laporan.index')); ?>"
    class="nav-item <?php echo e(request()->routeIs('admin.laporan.*') ? 'active' : ''); ?>">
    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    Laporan
</a>
    </div>

    

    <div class="nav-section">
        <div class="nav-label">Master Data</div>
        <a href="<?php echo e(route('admin.divisi.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.divisi.*') ? 'active' : ''); ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Divisi
        </a>
        <a href="<?php echo e(route('admin.jabatan.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.jabatan.*') ? 'active' : ''); ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Jabatan
        </a>
        <a href="<?php echo e(route('admin.shift.index')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.shift.*') ? 'active' : ''); ?>">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            Shift
        </a>
    </div>

   <div class="sidebar-footer">
    <a href="<?php echo e(route('admin.profil.index')); ?>" class="user-card">
        <div style="width: 34px; height: 34px; border-radius: 9px; overflow: hidden; flex-shrink: 0;">
            <?php if(auth()->user()->adminProfile->foto): ?>
                <img src="<?php echo e(asset('storage/' . auth()->user()->adminProfile->foto)); ?>"
                    style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
                <div class="avatar">
                    <?php echo e(strtoupper(substr(auth()->user()->adminProfile->nama_admin ?? 'A', 0, 2))); ?>

                </div>
            <?php endif; ?>
        </div>
        <div style="flex:1; min-width:0;">
            <div class="user-name"><?php echo e(auth()->user()->adminProfile->nama_admin ?? 'Admin'); ?></div>
            <div class="user-role"><?php echo e(auth()->user()->nip); ?></div>
        </div>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" title="Logout" style="background:none; border:none; cursor:pointer; color:var(--mid); display:flex; align-items:center;">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </button>
        </form>
    </a>
</div>
</aside><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>