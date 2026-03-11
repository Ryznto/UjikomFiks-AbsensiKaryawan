

<?php $__env->startSection('title', 'Presensi'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<div class="alert alert-success">✅ <?php echo e(session('success')); ?></div>
<?php endif; ?>


<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Absensi Hari Ini
        </div>
        <div style="font-family: var(--mono); font-size: 11px; color: var(--mid);" id="jam-sekarang"></div>
    </div>
    <div class="card-body">

        
        <div style="margin-bottom: 16px;">
            <div class="info-row">
                <span class="info-label">Shift</span>
                <span class="info-value"><?php echo e($karyawan->shift->nama_shift); ?>

                    <span style="color:var(--mid);font-size:11px;font-family:var(--mono);">
                        <?php echo e($karyawan->shift->jam_masuk); ?> - <?php echo e($karyawan->shift->jam_pulang); ?>

                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">🌅 Jam Masuk</span>
                <span class="info-value mono" style="color: <?php echo e($presensiHariIni?->jam_masuk ? 'var(--green)' : 'var(--mid)'); ?>">
                    <?php echo e($presensiHariIni?->jam_masuk ?? '—'); ?>

                </span>
            </div>
            <div class="info-row">
                <span class="info-label">🌆 Jam Pulang</span>
                <span class="info-value mono" style="color: <?php echo e($presensiHariIni?->jam_pulang ? 'var(--amber)' : 'var(--mid)'); ?>">
                    <?php echo e($presensiHariIni?->jam_pulang ?? '—'); ?>

                </span>
            </div>
        </div>

        
        <div id="lokasi-bar" style="display:flex; align-items:center; gap:10px; padding: 10px 14px;
            background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--radius-sm);
            font-size: 12px; color: var(--mid); margin-bottom: 16px;">
            <div id="lokasi-dot" style="width:8px;height:8px;border-radius:50%;background:var(--mid);flex-shrink:0;"></div>
            <span id="lokasi-text">Mengambil lokasi...</span>
        </div>

        
        <div id="camera-wrap" style="display:none; margin-bottom: 16px;">
            <video id="video" autoplay playsinline
                style="width:100%; border-radius: var(--radius); background:#000; max-height: 280px; object-fit: cover;"></video>
            <canvas id="canvas" style="display:none;"></canvas>
            <div id="preview-wrap" style="display:none; margin-top: 10px;">
                <img id="preview" style="width:100%; border-radius: var(--radius); max-height: 280px; object-fit: cover;">
                <div style="display:flex; gap:8px; margin-top:10px;">
                    <button onclick="retake()" class="btn btn-secondary btn-inline">📷 Ulang</button>
                    <button onclick="submitAbsen()" id="btn-submit" class="btn btn-primary btn-inline" style="flex:1;">
                        ✅ Konfirmasi
                    </button>
                </div>
            </div>
            <button id="btn-capture" onclick="capture()" class="btn btn-primary" style="margin-top:10px;">
                📸 Ambil Foto
            </button>
        </div>

        
        <?php if(!$presensiHariIni || !$presensiHariIni->jam_masuk): ?>
        <button onclick="startAbsen('masuk')" id="btn-absen-masuk" class="btn btn-primary">
            📷 Absen Masuk
        </button>
        <?php elseif(!$presensiHariIni->jam_pulang): ?>
        <button onclick="startAbsen('pulang')" id="btn-absen-pulang" class="btn btn-success">
            🏠 Absen Pulang
        </button>
        <?php else: ?>
        <div class="btn btn-secondary" style="cursor:default; justify-content:center;">
            ✅ Absensi Hari Ini Selesai
        </div>
        <?php endif; ?>

    </div>
</div>

<div style="display: flex; justify-content: flex-end; margin-bottom: 16px;">
    <a href="<?php echo e(route('karyawan.koreksi-absen.create')); ?>" class="btn btn-secondary btn-inline">
        ✏️ Ajukan Koreksi Absen
    </a>
</div>


<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">Riwayat Presensi</div>
    </div>
    <div style="padding: 12px 18px; border-bottom: 1px solid var(--border);">
        <form method="GET" action="<?php echo e(route('karyawan.presensi.index')); ?>" style="display: flex; gap: 8px; flex-wrap: wrap;">
            <select name="bulan" class="form-control" style="width: auto; flex: 1;">
                <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($m); ?>" <?php echo e(request('bulan', date('n')) == $m ? 'selected' : ''); ?>>
                    <?php echo e(\Carbon\Carbon::create()->month($m)->translatedFormat('F')); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="tahun" class="form-control" style="width: auto; flex: 1;">
                <?php $__currentLoopData = range(date('Y'), date('Y') - 2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($y); ?>" <?php echo e(request('tahun', date('Y')) == $y ? 'selected' : ''); ?>>
                    <?php echo e($y); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="status_absen" class="form-control" style="width: auto; flex: 1;">
                <option value="">Semua</option>
                <option value="tepat_waktu"  <?php echo e(request('status_absen') == 'tepat_waktu'  ? 'selected' : ''); ?>>Tepat Waktu</option>
                <option value="terlambat"    <?php echo e(request('status_absen') == 'terlambat'    ? 'selected' : ''); ?>>Terlambat</option>
                <option value="pulang_cepat" <?php echo e(request('status_absen') == 'pulang_cepat' ? 'selected' : ''); ?>>Pulang Cepat</option>
                <option value="alfa"         <?php echo e(request('status_absen') == 'alfa'         ? 'selected' : ''); ?>>Alfa</option>
            </select>
            <button type="submit" class="btn btn-primary btn-inline">Filter</button>
        </form>
    </div>

    <div class="table-wrap">
          <table>
          <thead>
    <tr>
        <th>Tanggal</th>
        <th>Masuk</th>
        <th>Pulang</th>
        <th>Status Masuk</th>
        <th>Status Pulang</th>
    </tr>
</thead>
<tbody>
    <?php $__empty_1 = true; $__currentLoopData = $riwayat; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr>
        <td style="font-family:var(--mono);font-size:11px;">
            <?php echo e(\Carbon\Carbon::parse($p->tanggal)->format('d M Y')); ?>

        </td>
        <td style="font-family:var(--mono);color:var(--green);font-size:12px;">
            <?php echo e($p->jam_masuk ?? '—'); ?>

        </td>
        <td style="font-family:var(--mono);color:var(--amber);font-size:12px;">
            <?php echo e($p->jam_pulang ?? '—'); ?>

        </td>
        <td>
            <?php
                $badgeMasuk = [
                    'tepat_waktu' => ['class' => 'badge-green', 'label' => 'Tepat'],
                    'terlambat'   => ['class' => 'badge-amber', 'label' => 'Lambat'],
                    'alfa'        => ['class' => 'badge-red',   'label' => 'Alfa'],
                ];
                $bm = $badgeMasuk[$p->status_absen] ?? ['class' => 'badge-gray', 'label' => '-'];
            ?>
            <span class="badge <?php echo e($bm['class']); ?>" style="font-size:10px;padding:2px 7px;">
                <?php echo e($bm['label']); ?>

            </span>
        </td>
        <td>
            <?php if($p->jam_pulang): ?>
                <?php
                    $badgePulang = [
                        'tepat_waktu'  => ['class' => 'badge-green', 'label' => 'Tepat'],
                        'pulang_cepat' => ['class' => 'badge-amber', 'label' => 'P.Cepat'],
                    ];
                    $bp = $badgePulang[$p->status_pulang] ?? ['class' => 'badge-gray', 'label' => '-'];
                ?>
                <span class="badge <?php echo e($bp['class']); ?>" style="font-size:10px;padding:2px 7px;">
                    <?php echo e($bp['label']); ?>

                </span>
            <?php else: ?>
                <span style="color:var(--mid);font-size:12px;">—</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="5">
            <div class="empty-state"><p>Belum ada riwayat presensi</p></div>
        </td>
    </tr>
    <?php endif; ?>
</tbody>
        </table>
    </div>
    <?php if($riwayat->hasPages()): ?>
    <div style="padding: 14px 18px; border-top: 1px solid var(--border);">
        <?php echo e($riwayat->links()); ?>

    </div>
    <?php endif; ?>
</div>
 

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // ── JAM REALTIME ──
    function updateJam() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2,'0');
        const m = String(now.getMinutes()).padStart(2,'0');
        const s = String(now.getSeconds()).padStart(2,'0');
        const el = document.getElementById('jam-sekarang');
        if (el) el.textContent = `${h}:${m}:${s}`;
    }
    updateJam();
    setInterval(updateJam, 1000);

    // ── GPS ──
    let latitude  = null;
    let longitude = null;

    function getLocation() {
        if (!navigator.geolocation) {
            setLokasi(false, 'GPS tidak didukung browser ini');
            return;
        }
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                latitude  = pos.coords.latitude;
                longitude = pos.coords.longitude;
                setLokasi(true, `${latitude.toFixed(5)}, ${longitude.toFixed(5)}`);
            },
            () => setLokasi(false, 'Gagal mengambil lokasi, izinkan akses GPS')
        );
    }

    function setLokasi(ok, text) {
        document.getElementById('lokasi-dot').style.background = ok ? 'var(--green)' : 'var(--red)';
        document.getElementById('lokasi-text').textContent = ok ? `📍 ${text}` : `⚠️ ${text}`;
    }

    getLocation();

    // ── KAMERA ──
    let stream      = null;
    let fotoBase64  = null;
    let absenType   = null;

    async function startAbsen(type) {
        if (!latitude || !longitude) {
            alert('Lokasi belum didapat, aktifkan GPS dan coba lagi.');
            return;
        }

        absenType = type;

        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user' }
            });
            document.getElementById('video').srcObject = stream;
            document.getElementById('camera-wrap').style.display = 'block';

            // Sembunyikan tombol absen
            const btnMasuk  = document.getElementById('btn-absen-masuk');
            const btnPulang = document.getElementById('btn-absen-pulang');
            if (btnMasuk)  btnMasuk.style.display  = 'none';
            if (btnPulang) btnPulang.style.display  = 'none';

        } catch (err) {
            alert('Gagal mengakses kamera: ' + err.message);
        }
    }

    function capture() {
        const video   = document.getElementById('video');
        const canvas  = document.getElementById('canvas');
        canvas.width  = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);

        fotoBase64 = canvas.toDataURL('image/jpeg', 0.8);

        document.getElementById('preview').src = fotoBase64;
        document.getElementById('preview-wrap').style.display = 'block';
        document.getElementById('btn-capture').style.display  = 'none';
        document.getElementById('video').style.display        = 'none';
    }

    function retake() {
        fotoBase64 = null;
        document.getElementById('preview-wrap').style.display = 'none';
        document.getElementById('btn-capture').style.display  = 'block';
        document.getElementById('video').style.display        = 'block';
    }

    async function submitAbsen() {
        if (!fotoBase64 || !latitude || !longitude) return;

        const btn = document.getElementById('btn-submit');
        btn.textContent = 'Menyimpan...';
        btn.disabled    = true;

        const url = absenType === 'masuk'
            ? '<?php echo e(route("karyawan.presensi.masuk")); ?>'
            : '<?php echo e(route("karyawan.presensi.pulang")); ?>';

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    foto:      fotoBase64,
                    latitude:  latitude,
                    longitude: longitude,
                })
            });

            const data = await res.json();

            if (res.ok) {
                // Stop kamera
                if (stream) stream.getTracks().forEach(t => t.stop());
                alert('✅ ' + data.message);
                window.location.reload();
            } else {
                alert('❌ ' + data.message);
                btn.textContent = '✅ Konfirmasi';
                btn.disabled    = false;
            }
        } catch (err) {
            alert('Terjadi kesalahan, coba lagi.');
            btn.textContent = '✅ Konfirmasi';
            btn.disabled    = false;
        }
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.karyawan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/karyawan/presensi/index.blade.php ENDPATH**/ ?>