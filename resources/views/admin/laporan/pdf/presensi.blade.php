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
        .gray  { background: #f3f4f6; color: #6b7280; }
    </style>
</head>
<body>
    <h2>{{ $judul }}</h2>
    <p>Dicetak pada: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('d M Y H:i') }} WIB</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama</th>
                <th>Divisi</th>
                <th>Shift</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Status Masuk</th>
                <th>Status Pulang</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presensis as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->karyawan->user->nip }}</td>
                <td>{{ $p->karyawan->nama }}</td>
                <td>{{ $p->karyawan->divisi->nama_divisi }}</td>
                <td>{{ $p->karyawan->shift->nama_shift ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $p->jam_masuk ?? '-' }}</td>
                <td>{{ $p->jam_pulang ?? '-' }}</td>
                <td>
                    @php $sm = ['tepat_waktu'=>['green','Tepat'],'terlambat'=>['amber','Lambat'],'alfa'=>['red','Alfa']]; @endphp
                    @if(isset($sm[$p->status_absen]))
                    <span class="badge {{ $sm[$p->status_absen][0] }}">{{ $sm[$p->status_absen][1] }}</span>
                    @else - @endif
                </td>
                <td>
                    @php $sp = ['tepat_waktu'=>['green','Tepat'],'pulang_cepat'=>['amber','P.Cepat']]; @endphp
                    @if($p->jam_pulang && isset($sp[$p->status_pulang]))
                    <span class="badge {{ $sp[$p->status_pulang][0] }}">{{ $sp[$p->status_pulang][1] }}</span>
                    @else - @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="10" style="text-align:center; color:#999;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>