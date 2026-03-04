<header class="navbar">
    <div>
        <div class="navbar-title">@yield('page-title', 'Dashboard')</div>
        <div class="navbar-sub">@yield('page-sub', \Carbon\Carbon::now()->translatedFormat('l, d F Y'))</div>
    </div>
    <div class="navbar-right">
        @yield('page-actions')
    </div>
</header>