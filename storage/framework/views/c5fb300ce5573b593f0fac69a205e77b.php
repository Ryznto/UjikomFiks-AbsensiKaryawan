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
        .badge { padding: 2px 7px; border-radius: 99px; font-size: 9px; font-weight: bold; }
        .green { background: #dcfce7; color: #16a34a; }
        .amber { background: #fef3c7; color: #d97706; }
        .red   { background: #fee2e2; color: #dc2626; }
        .blue  { background: #dbeafe; color: #2563eb; }
        .gray  { background: #f3f4f6; color: #6b7280; }
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
                <th>Jenis</th>
                <th>Tgl Mulai</th>
                <th>Tgl Selesai</th>
                <th>Durasi</th>
                <th>Keterangan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $izinCutis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $ic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $durasi = \Carbon\Carbon::parse($ic->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($ic->tanggal_selesai)) + 1;
                $jenisMap  = ['izin'=>['blue','Izin'],'sakit'=>['red','Sakit'],'cuti'=>['green','Cuti']];
                $statusMap = ['pending'=>['amber','Pending'],'approved'=>['green','Approved'],'rejected'=>['red','Rejected']];
            ?>
            <tr>
                <td><?php echo e($i + 1); ?></td>
                <td><?php echo e($ic->karyawan->user->nip); ?></td>
                <td><?php echo e($ic->karyawan->nama); ?></td>
                <td><?php echo e($ic->karyawan->divisi->nama_divisi); ?></td>
                <td><span class="badge <?php echo e($jenisMap[$ic->jenis][0]); ?>"><?php echo e($jenisMap[$ic->jenis][1]); ?></span></td>
                <td><?php echo e(\Carbon\Carbon::parse($ic->tanggal_mulai)->format('d/m/Y')); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($ic->tanggal_selesai)->format('d/m/Y')); ?></td>
                <td style="text-align:center"><?php echo e($durasi); ?> hari</td>
                <td><?php echo e($ic->keterangan ?? '-'); ?></td>
                <td><span class="badge <?php echo e($statusMap[$ic->status][0]); ?>"><?php echo e($statusMap[$ic->status][1]); ?></span></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr><td colspan="10" style="text-align:center; color:#999;">Tidak ada data</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html><?php /**PATH C:\laragon\www\absensi-karyawan\resources\views/admin/laporan/pdf/izin-cuti.blade.php ENDPATH**/ ?>