<?php

namespace App\Exports;

use App\Models\Presensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class PresensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $bulan;
    protected $tahun;
    protected $divisiId;

    public function __construct($bulan, $tahun, $divisiId = null)
    {
        $this->bulan   = $bulan;
        $this->tahun   = $tahun;
        $this->divisiId = $divisiId;
    }

    public function collection()
    {
        $query = Presensi::with(['karyawan.user', 'karyawan.divisi', 'karyawan.shift'])
            ->whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun);

        if ($this->divisiId) {
            $query->whereHas('karyawan', fn($q) => $q->where('divisi_id', $this->divisiId));
        }

        return $query->orderBy('tanggal')->orderBy('karyawan_id')->get();
    }

    public function headings(): array
    {
        return [
            'No', 'NIP', 'Nama', 'Divisi', 'Shift',
            'Tanggal', 'Jam Masuk', 'Jam Pulang',
            'Status Masuk', 'Status Pulang'
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $statusMasuk = [
            'tepat_waktu' => 'Tepat Waktu',
            'terlambat'   => 'Terlambat',
            'alfa'        => 'Alfa',
        ];

        $statusPulang = [
            'tepat_waktu'  => 'Tepat Waktu',
            'pulang_cepat' => 'Pulang Cepat',
        ];

        return [
            $no,
            $row->karyawan->user->nip,
            $row->karyawan->nama,
            $row->karyawan->divisi->nama_divisi,
            $row->karyawan->shift->nama_shift ?? '-',
            Carbon::parse($row->tanggal)->format('d/m/Y'),
            $row->jam_masuk ?? '-',
            $row->jam_pulang ?? '-',
            $statusMasuk[$row->status_absen] ?? '-',
            $statusPulang[$row->status_pulang] ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Presensi ' . Carbon::create($this->tahun, $this->bulan)->format('F Y');
    }
}