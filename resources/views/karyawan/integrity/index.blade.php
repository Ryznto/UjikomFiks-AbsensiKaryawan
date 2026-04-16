@extends('layouts.karyawan')

@section('title', 'Dompet Integritas')

@section('content')
    <div class="integrity-container" style="padding: 16px; padding-bottom: 80px;">

        {{-- Alert Success/Error --}}
        @if (session('success'))
            <div class="alert alert-success"
                style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 12px; margin-bottom: 16px;">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger"
                style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 12px; margin-bottom: 16px;">
                ❌ {{ session('error') }}
            </div>
        @endif

        {{-- Hero Section - E-Wallet Style --}}
        <div
            style="background: linear-gradient(135deg, #4f7cff, #a855f7); border-radius: 20px; padding: 20px; margin-bottom: 20px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="opacity: 0.8; font-size: 12px; margin-bottom: 4px;">Saldo Poin Integritas</p>
                    <p style="font-size: 32px; font-weight: 700; margin: 0;">{{ number_format($user->point_balance ?? 0) }}
                    </p>
                    <div style="margin-top: 12px;">
                        <p style="font-size: 12px; opacity: 0.8;">Level: {{ $level['nama'] ?? 'Pemula' }}</p>
                        <div style="background: rgba(255,255,255,0.3); height: 6px; border-radius: 10px; width: 120px;">
                            <div
                                style="background: #fbbf24; height: 6px; border-radius: 10px; width: {{ min(100, ($user->point_balance / 500) * 100) }}%;">
                            </div>
                        </div>
                    </div>
                </div>
                <div style="font-size: 48px;">{{ $level['icon'] ?? '💰' }}</div>
            </div>
        </div>

        {{-- Tabs Navigation --}}
        <div
            style="display: flex; gap: 8px; background: white; border-radius: 16px; padding: 6px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <button class="tab-btn active" onclick="showTab('riwayat')"
                style="flex: 1; padding: 12px; border: none; background: {{ request()->tab == 'marketplace' ? 'transparent' : '#4f7cff' }}; color: {{ request()->tab == 'marketplace' ? '#4f7cff' : 'white' }}; border-radius: 12px; font-weight: 600; cursor: pointer;">
                📜 Riwayat
            </button>
            <button class="tab-btn" onclick="showTab('marketplace')"
                style="flex: 1; padding: 12px; border: none; background: {{ request()->tab == 'marketplace' ? '#4f7cff' : 'transparent' }}; color: {{ request()->tab == 'marketplace' ? 'white' : '#4f7cff' }}; border-radius: 12px; font-weight: 600; cursor: pointer;">
                🛒 Marketplace
            </button>
            <button class="tab-btn" onclick="showTab('inventory')"
                style="flex: 1; padding: 12px; border: none; background: transparent; color: #4f7cff; border-radius: 12px; font-weight: 600; cursor: pointer;">
                🎒 Inventory
            </button>
        </div>

        {{-- Tab 1: Riwayat Mutasi --}}
        <div id="tab-riwayat" class="tab-content" style="display: block;">
            <div style="background: #1e293b; border-radius: 16px; overflow: hidden; border: 1px solid #334155;">
                @forelse($ledgers ?? [] as $ledger)
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border-bottom: 1px solid #334155;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div
                                style="width: 40px; height: 40px; border-radius: 40px; background: {{ $ledger->transaction_type == 'EARN' ? '#064e3b' : '#7f1d1d' }}; display: flex; align-items: center; justify-content: center;">
                                <span
                                    style="font-size: 20px; color: white;">{{ $ledger->transaction_type == 'EARN' ? '+' : '-' }}</span>
                            </div>
                            <div>
                                <p style="font-weight: 600; margin: 0; color: #f1f5f9;">
                                    {{ $ledger->description ?? 'Transaksi' }}</p>
                                <p style="font-size: 11px; color: #94a3b8; margin: 4px 0 0;">
                                    {{ $ledger->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <p
                                style="font-weight: 700; margin: 0; color: {{ $ledger->transaction_type == 'EARN' ? '#34d399' : '#f87171' }};">
                                {{ $ledger->transaction_type == 'EARN' ? '+' : '-' }} {{ number_format($ledger->amount) }}
                            </p>
                            <p style="font-size: 11px; color: #94a3b8; margin: 4px 0 0;">Saldo:
                                {{ number_format($ledger->current_balance) }}</p>
                        </div>
                    </div>
                @empty
                    <div style="padding: 40px; text-align: center; color: #94a3b8; background: #1e293b;">
                        <p>Belum ada riwayat mutasi poin.</p>
                    </div>
                @endforelse
            </div>
            <div style="margin-top: 16px;">
                {{ $ledgers->links() ?? '' }}
            </div>
        </div>

        {{-- Tab 2: Marketplace --}}
        {{-- Tab 2: Marketplace --}}
        <div id="tab-marketplace" class="tab-content" style="display: none;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                @forelse($items ?? [] as $item)
                    <div
                        style="background: #1e293b; border-radius: 16px; padding: 16px; border: 1px solid #334155; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                        <div style="font-size: 32px; margin-bottom: 8px;">
                            @if ($item->token_type == 'late_tolerance')
                                ⏰
                            @elseif($item->token_type == 'izin_tanpa_surat')
                                📝
                            @elseif($item->token_type == 'wfh')
                                🏠
                            @else
                                🎁
                            @endif
                        </div>
                        <h3 style="font-weight: 700; font-size: 14px; margin: 0 0 4px; color: #f1f5f9;">
                            {{ $item->item_name }}</h3>
                        <p style="font-size: 11px; color: #94a3b8; margin: 0 0 12px;">
                            {{ $item->description ?? 'Token kelonggaran untuk fleksibilitas kerja' }}</p>
                        @if ($item->token_type == 'late_tolerance' && $item->tolerance_minutes)
                            <p style="font-size: 10px; color: #34d399; margin: 0 0 12px;">✅ Bebas terlambat
                                {{ $item->tolerance_minutes }} menit</p>
                        @endif
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                            <div>
                                <span
                                    style="font-weight: 700; font-size: 18px; color: #60a5fa;">{{ number_format($item->point_cost) }}</span>
                                <span style="font-size: 10px; color: #94a3b8;"> poin</span>
                            </div>
                            <form action="{{ route('karyawan.integrity.buy', $item) }}" method="POST">
                                @csrf
                                @php $cukupPoin = ($user->point_balance ?? 0) >= $item->point_cost; @endphp
                                <button type="submit" class="btn-buy"
                                    style="background: {{ $cukupPoin ? '#4f7cff' : '#475569' }}; color: white; border: none; padding: 8px 16px; border-radius: 12px; font-weight: 600; cursor: {{ $cukupPoin ? 'pointer' : 'not-allowed' }}; opacity: {{ $cukupPoin ? '1' : '0.6' }};"
                                    {{ !$cukupPoin ? 'disabled' : '' }}>
                                    {{ $cukupPoin ? 'Tukar' : 'Poin Kurang' }}
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div
                        style="grid-column: 1/-1; background: #1e293b; border-radius: 16px; padding: 40px; text-align: center; border: 1px solid #334155;">
                        <p style="color: #94a3b8;">Belum ada item di marketplace.</p>
                        <p style="font-size: 12px; color: #64748b;">Login sebagai admin untuk menambahkan item token.</p>
                    </div>
                @endforelse
            </div>
        </div>
        {{-- Tab 3: My Inventory --}}
        <div id="tab-inventory" class="tab-content" style="display: none;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                @forelse(($tokens ?? [])->where('status', 'AVAILABLE') as $token)
                    <div
                        style="background: #1e293b; border-radius: 16px; padding: 16px; border-left: 3px solid #34d399; border: 1px solid #334155; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <div style="font-size: 32px; margin-bottom: 8px;">
                                    @if ($token->item->token_type == 'late_tolerance')
                                        ⏰
                                    @elseif($token->item->token_type == 'izin_tanpa_surat')
                                        📝
                                    @elseif($token->item->token_type == 'wfh')
                                        🏠
                                    @else
                                        🎁
                                    @endif
                                </div>
                                <h3 style="font-weight: 700; font-size: 14px; margin: 0; color: #f1f5f9;">
                                    {{ $token->item->item_name }}</h3>
                                @if ($token->item->token_type == 'late_tolerance' && $token->item->tolerance_minutes)
                                    <p style="font-size: 10px; color: #34d399; margin: 4px 0 0;">✅ Bebas terlambat
                                        {{ $token->item->tolerance_minutes }} menit</p>
                                @endif
                            </div>
                            <span
                                style="background: #064e3b; color: #34d399; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 600; border: 1px solid #10b981;">AKTIF</span>
                        </div>
                        @if ($token->expires_at)
                            <p style="font-size: 9px; color: #64748b; margin: 12px 0 0;">Berlaku hingga:
                                {{ $token->expires_at->format('d/m/Y') }}</p>
                        @endif
                    </div>
                @empty
                    <div
                        style="grid-column: 1/-1; background: #1e293b; border-radius: 16px; padding: 40px; text-align: center; border: 1px solid #334155;">
                        <p style="color: #94a3b8;">Kamu belum memiliki token kelonggaran.</p>
                        <p style="font-size: 12px; color: #64748b;">Kunjungi Marketplace untuk menukar poin!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <script>
            function showTab(tabName) {
                // Sembunyikan semua tab
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.style.display = 'none';
                });

                // Tampilkan tab yang dipilih
                document.getElementById('tab-' + tabName).style.display = 'block';

                // Update style tombol
                document.querySelectorAll('.tab-btn').forEach(btn => {
                    btn.style.background = 'transparent';
                    btn.style.color = '#4f7cff';
                });

                // Style tombol aktif
                const buttons = document.querySelectorAll('.tab-btn');
                if (tabName === 'riwayat') {
                    buttons[0].style.background = '#4f7cff';
                    buttons[0].style.color = 'white';
                } else if (tabName === 'marketplace') {
                    buttons[1].style.background = '#4f7cff';
                    buttons[1].style.color = 'white';
                } else if (tabName === 'inventory') {
                    buttons[2].style.background = '#4f7cff';
                    buttons[2].style.color = 'white';
                }
            }

            // Buka tab berdasarkan URL hash
            if (window.location.hash) {
                const hash = window.location.hash.substring(1);
                if (hash === 'marketplace') showTab('marketplace');
                else if (hash === 'inventory') showTab('inventory');
            }
        </script>
    @endsection
