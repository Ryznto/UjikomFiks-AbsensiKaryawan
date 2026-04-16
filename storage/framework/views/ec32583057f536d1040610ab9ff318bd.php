

<?php $__env->startSection('title', 'Dompet Integritas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="integrity-container" style="padding: 16px; padding-bottom: 80px;">

        
        <?php if(session('success')): ?>
            <div class="alert alert-success"
                style="background: #d1fae5; color: #065f46; padding: 12px; border-radius: 12px; margin-bottom: 16px;">
                ✅ <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger"
                style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 12px; margin-bottom: 16px;">
                ❌ <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        
        <div
            style="background: linear-gradient(135deg, #4f7cff, #a855f7); border-radius: 20px; padding: 20px; margin-bottom: 20px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <p style="opacity: 0.8; font-size: 12px; margin-bottom: 4px;">Saldo Poin Integritas</p>
                    <p style="font-size: 32px; font-weight: 700; margin: 0;"><?php echo e(number_format($user->point_balance ?? 0)); ?>

                    </p>
                    <div style="margin-top: 12px;">
                        <p style="font-size: 12px; opacity: 0.8;">Level: <?php echo e($level['nama'] ?? 'Pemula'); ?></p>
                        <div style="background: rgba(255,255,255,0.3); height: 6px; border-radius: 10px; width: 120px;">
                            <div
                                style="background: #fbbf24; height: 6px; border-radius: 10px; width: <?php echo e(min(100, ($user->point_balance / 500) * 100)); ?>%;">
                            </div>
                        </div>
                    </div>
                </div>
                <div style="font-size: 48px;"><?php echo e($level['icon'] ?? '💰'); ?></div>
            </div>
        </div>

        
        <div
            style="display: flex; gap: 8px; background: white; border-radius: 16px; padding: 6px; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <button class="tab-btn active" onclick="showTab('riwayat')"
                style="flex: 1; padding: 12px; border: none; background: <?php echo e(request()->tab == 'marketplace' ? 'transparent' : '#4f7cff'); ?>; color: <?php echo e(request()->tab == 'marketplace' ? '#4f7cff' : 'white'); ?>; border-radius: 12px; font-weight: 600; cursor: pointer;">
                📜 Riwayat
            </button>
            <button class="tab-btn" onclick="showTab('marketplace')"
                style="flex: 1; padding: 12px; border: none; background: <?php echo e(request()->tab == 'marketplace' ? '#4f7cff' : 'transparent'); ?>; color: <?php echo e(request()->tab == 'marketplace' ? 'white' : '#4f7cff'); ?>; border-radius: 12px; font-weight: 600; cursor: pointer;">
                🛒 Marketplace
            </button>
            <button class="tab-btn" onclick="showTab('inventory')"
                style="flex: 1; padding: 12px; border: none; background: transparent; color: #4f7cff; border-radius: 12px; font-weight: 600; cursor: pointer;">
                🎒 Inventory
            </button>
        </div>

        
        <div id="tab-riwayat" class="tab-content" style="display: block;">
            <div style="background: #1e293b; border-radius: 16px; overflow: hidden; border: 1px solid #334155;">
                <?php $__empty_1 = true; $__currentLoopData = $ledgers ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ledger): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border-bottom: 1px solid #334155;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div
                                style="width: 40px; height: 40px; border-radius: 40px; background: <?php echo e($ledger->transaction_type == 'EARN' ? '#064e3b' : '#7f1d1d'); ?>; display: flex; align-items: center; justify-content: center;">
                                <span
                                    style="font-size: 20px; color: white;"><?php echo e($ledger->transaction_type == 'EARN' ? '+' : '-'); ?></span>
                            </div>
                            <div>
                                <p style="font-weight: 600; margin: 0; color: #f1f5f9;">
                                    <?php echo e($ledger->description ?? 'Transaksi'); ?></p>
                                <p style="font-size: 11px; color: #94a3b8; margin: 4px 0 0;">
                                    <?php echo e($ledger->created_at->format('d/m/Y H:i')); ?></p>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <p
                                style="font-weight: 700; margin: 0; color: <?php echo e($ledger->transaction_type == 'EARN' ? '#34d399' : '#f87171'); ?>;">
                                <?php echo e($ledger->transaction_type == 'EARN' ? '+' : '-'); ?> <?php echo e(number_format($ledger->amount)); ?>

                            </p>
                            <p style="font-size: 11px; color: #94a3b8; margin: 4px 0 0;">Saldo:
                                <?php echo e(number_format($ledger->current_balance)); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div style="padding: 40px; text-align: center; color: #94a3b8; background: #1e293b;">
                        <p>Belum ada riwayat mutasi poin.</p>
                    </div>
                <?php endif; ?>
            </div>
            <div style="margin-top: 16px;">
                <?php echo e($ledgers->links() ?? ''); ?>

            </div>
        </div>

        
        
        <div id="tab-marketplace" class="tab-content" style="display: none;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <?php $__empty_1 = true; $__currentLoopData = $items ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div
                        style="background: #1e293b; border-radius: 16px; padding: 16px; border: 1px solid #334155; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                        <div style="font-size: 32px; margin-bottom: 8px;">
                            <?php if($item->token_type == 'late_tolerance'): ?>
                                ⏰
                            <?php elseif($item->token_type == 'izin_tanpa_surat'): ?>
                                📝
                            <?php elseif($item->token_type == 'wfh'): ?>
                                🏠
                            <?php else: ?>
                                🎁
                            <?php endif; ?>
                        </div>
                        <h3 style="font-weight: 700; font-size: 14px; margin: 0 0 4px; color: #f1f5f9;">
                            <?php echo e($item->item_name); ?></h3>
                        <p style="font-size: 11px; color: #94a3b8; margin: 0 0 12px;">
                            <?php echo e($item->description ?? 'Token kelonggaran untuk fleksibilitas kerja'); ?></p>
                        <?php if($item->token_type == 'late_tolerance' && $item->tolerance_minutes): ?>
                            <p style="font-size: 10px; color: #34d399; margin: 0 0 12px;">✅ Bebas terlambat
                                <?php echo e($item->tolerance_minutes); ?> menit</p>
                        <?php endif; ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                            <div>
                                <span
                                    style="font-weight: 700; font-size: 18px; color: #60a5fa;"><?php echo e(number_format($item->point_cost)); ?></span>
                                <span style="font-size: 10px; color: #94a3b8;"> poin</span>
                            </div>
                            <form action="<?php echo e(route('karyawan.integrity.buy', $item)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php $cukupPoin = ($user->point_balance ?? 0) >= $item->point_cost; ?>
                                <button type="submit" class="btn-buy"
                                    style="background: <?php echo e($cukupPoin ? '#4f7cff' : '#475569'); ?>; color: white; border: none; padding: 8px 16px; border-radius: 12px; font-weight: 600; cursor: <?php echo e($cukupPoin ? 'pointer' : 'not-allowed'); ?>; opacity: <?php echo e($cukupPoin ? '1' : '0.6'); ?>;"
                                    <?php echo e(!$cukupPoin ? 'disabled' : ''); ?>>
                                    <?php echo e($cukupPoin ? 'Tukar' : 'Poin Kurang'); ?>

                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div
                        style="grid-column: 1/-1; background: #1e293b; border-radius: 16px; padding: 40px; text-align: center; border: 1px solid #334155;">
                        <p style="color: #94a3b8;">Belum ada item di marketplace.</p>
                        <p style="font-size: 12px; color: #64748b;">Login sebagai admin untuk menambahkan item token.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div id="tab-inventory" class="tab-content" style="display: none;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <?php $__empty_1 = true; $__currentLoopData = ($tokens ?? [])->where('status', 'AVAILABLE'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $token): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div
                        style="background: #1e293b; border-radius: 16px; padding: 16px; border-left: 3px solid #34d399; border: 1px solid #334155; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <div style="font-size: 32px; margin-bottom: 8px;">
                                    <?php if($token->item->token_type == 'late_tolerance'): ?>
                                        ⏰
                                    <?php elseif($token->item->token_type == 'izin_tanpa_surat'): ?>
                                        📝
                                    <?php elseif($token->item->token_type == 'wfh'): ?>
                                        🏠
                                    <?php else: ?>
                                        🎁
                                    <?php endif; ?>
                                </div>
                                <h3 style="font-weight: 700; font-size: 14px; margin: 0; color: #f1f5f9;">
                                    <?php echo e($token->item->item_name); ?></h3>
                                <?php if($token->item->token_type == 'late_tolerance' && $token->item->tolerance_minutes): ?>
                                    <p style="font-size: 10px; color: #34d399; margin: 4px 0 0;">✅ Bebas terlambat
                                        <?php echo e($token->item->tolerance_minutes); ?> menit</p>
                                <?php endif; ?>
                            </div>
                            <span
                                style="background: #064e3b; color: #34d399; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 600; border: 1px solid #10b981;">AKTIF</span>
                        </div>
                        <?php if($token->expires_at): ?>
                            <p style="font-size: 9px; color: #64748b; margin: 12px 0 0;">Berlaku hingga:
                                <?php echo e($token->expires_at->format('d/m/Y')); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div
                        style="grid-column: 1/-1; background: #1e293b; border-radius: 16px; padding: 40px; text-align: center; border: 1px solid #334155;">
                        <p style="color: #94a3b8;">Kamu belum memiliki token kelonggaran.</p>
                        <p style="font-size: 12px; color: #64748b;">Kunjungi Marketplace untuk menukar poin!</p>
                    </div>
                <?php endif; ?>
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
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.karyawan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/karyawan/integrity/index.blade.php ENDPATH**/ ?>