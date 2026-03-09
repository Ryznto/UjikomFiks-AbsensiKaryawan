<?php

namespace App\Exports;

use App\Models\IzinCuti;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class IzinCutiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $bulan;
    protected $tahun;
    protected $divisiId;

    public function __construct($bulan, $tahun, $divisiId = null)
    {
        $this->bulan    = $bulan;
        $this->tahun    = $tahun;
        $this->divisiId = $divisiId;
    }

    public function collection()
    {
        $query = IzinCuti::with(['karyawan.user', 'karyawan.divisi'])
            ->whereMonth('tanggal_mulai', $this->bulan)
            ->whereYear('tanggal_mulai', $this->tahun);

        if ($this->divisiId) {
            $query->whereHas('karyawan', fn($q) => $q->where('divisi_id', $this->divisiId));
        }

        return $query->orderBy('tanggal_mulai')->get();
    }

    public function headings(): array
    {
        return [
            'No', 'NIP', 'Nama', 'Divisi',
            'Jenis', 'Tanggal Mulai', 'Tanggal Selesai',
            'Durasi (Hari)', 'Keterangan', 'Status'
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $durasi = Carbon::parse($row->tanggal_mulai)
            ->diffInDays(Carbon::parse($row->tanggal_selesai)) + 1;

        $jenis = [
            'izin'  => 'Izin',
            'sakit' => 'Sakit',
            'cuti'  => 'Cuti',
        ];

        $status = [
            'pending'  => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];

        return [
            $no,
            $row->karyawan->user->nip,
            $row->karyawan->nama,
            $row->karyawan->divisi->nama_divisi,
            $jenis[$row->jenis] ?? $row->jenis,
            Carbon::parse($row->tanggal_mulai)->format('d/m/Y'),
            Carbon::parse($row->tanggal_selesai)->format('d/m/Y'),
            $durasi,
            $row->keterangan ?? '-',
            $status[$row->status] ?? $row->status,
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
        return 'Izin Cuti ' . Carbon::create($this->tahun, $this->bulan)->format('F Y');
    }
}