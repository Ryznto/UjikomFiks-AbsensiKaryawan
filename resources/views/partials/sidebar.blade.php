<aside class="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        <div class="logo-mark">A</div>
        <div>
            <div class="logo-text">AbsensiKu</div>
            <div class="logo-sub">Admin Panel</div>
        </div>
    </a>

    <div class="nav-section">
        <div class="nav-label">Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
            Dashboard
        </a>
        <a href="{{ route('admin.karyawan.index') }}" class="nav-item {{ request()->routeIs('admin.karyawan.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            Karyawan
        </a>
        <a href="{{ route('admin.presensi.index') }}" class="nav-item {{ request()->routeIs('admin.presensi.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Presensi
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-label">Pengajuan</div>
        <a href="{{ route('admin.izin-cuti.index') }}" class="nav-item {{ request()->routeIs('admin.izin-cuti.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Izin & Cuti
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-label">Master Data</div>
        <a href="{{ route('admin.divisi.index') }}" class="nav-item {{ request()->routeIs('admin.divisi.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Divisi
        </a>
        <a href="{{ route('admin.jabatan.index') }}" class="nav-item {{ request()->routeIs('admin.jabatan.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Jabatan
        </a>
        <a href="{{ route('admin.shift.index') }}" class="nav-item {{ request()->routeIs('admin.shift.*') ? 'active' : '' }}">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            Shift
        </a>
    </div>

   <div class="sidebar-footer">
    <a href="{{ route('admin.profil.index') }}" class="user-card">
        <div style="width: 34px; height: 34px; border-radius: 9px; overflow: hidden; flex-shrink: 0;">
            @if(auth()->user()->adminProfile->foto)
                <img src="{{ asset('storage/' . auth()->user()->adminProfile->foto) }}"
                    style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <div class="avatar">
                    {{ strtoupper(substr(auth()->user()->adminProfile->nama_admin ?? 'A', 0, 2)) }}
                </div>
            @endif
        </div>
        <div style="flex:1; min-width:0;">
            <div class="user-name">{{ auth()->user()->adminProfile->nama_admin ?? 'Admin' }}</div>
            <div class="user-role">{{ auth()->user()->nip }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" title="Logout" style="background:none; border:none; cursor:pointer; color:var(--mid); display:flex; align-items:center;">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </button>
        </form>
    </a>
</div>
</aside>