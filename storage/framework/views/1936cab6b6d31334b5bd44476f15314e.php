<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1a1a2e; }
        h2 { font-size: 15px; margin-bottom: 4px; }
        p { font-size: 11px; color: #666; margin: 0 0 14px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #4f7cff; color: white; padding: 7px 10px; text-align: left; font-size: 10px; }
        td { padding: 6px 10px; border-bottom: 1px solid #eee; font-size: 10px; }
        tr:nth-child(even) td { background: #f8f9ff; }
        .num { text-align: center; font-weight: bold; }
        .green { color: #16a34a; font-weight: bold; }
        .amber { color: #d97706; font-weight: bold; }
        .red   { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <h2><?php echo e($judul); ?></h2>
    <p>Dicetak pada: <?php echo e(\Carbon\Carbon::now('Asia/Jakarta')->format('d M Y H:i')); ?> WIB</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Divisi</th>
                <th>Jabatan</th>
                <th>Shift</th>
                <th style="text-align:center">Hadir</th>
                <th style="text-align:center">Terlambat</th>
                <th style="text-align:center">Alfa</th>
                <th style="text-align:center">Pulang Cepat</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $karyawans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $hadir = \App\Models\Presensi::where('karyawan_id', $k->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->whereNotNull('jam_masuk')->where('status_absen','!=','alfa')->count();
                $terlambat = \App\Models\Presensi::where('karyawan_id', $k->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status_absen','terlambat')->count();
                $alfa = \App\Models\Presensi::where('karyawan_id', $k->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status_absen','alfa')->count();
                $pulangCepat = \App\Models\Presensi::where('karyawan_id', $k->id)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('status_pulang','pulang_cepat')->count();
            ?>
            <tr>
                <td><?php echo e($i + 1); ?></td>
                <td><?php echo e($k->user->nip); ?></td>
                <td><?php echo e($k->nama); ?></td>
                <td><?php echo e($k->divisi->nama_divisi); ?></td>
                <td><?php echo e($k->jabatan->nama_jabatan); ?></td>
                <td><?php echo e($k->shift->nama_shift); ?></td>
                <td class="num green"><?php echo e($hadir); ?></td>
                <td class="num amber"><?php echo e($terlambat); ?></td>
                <td class="num red"><?php echo e($alfa); ?></td>
                <td class="num amber"><?php echo e($pulangCepat); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="10" style="text-align:center; color:#999;">Tidak ada data</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/laporan/pdf/karyawan.blade.php ENDPATH**/ ?>