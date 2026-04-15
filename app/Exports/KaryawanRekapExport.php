<?php

namespace App\Exports;

use App\Models\Karyawan;
use App\Models\Presensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

/**
 * @package App\\Exports
 * @author AbsensiKu
 * @version 1.0.0
 * 
 * Export rekap kehadiran karyawan per bulan ke Excel.
 */
class KaryawanRekapExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $bulan;
    protected $tahun;
    protected $divisiId;

    /**
     * Constructor untuk menginisialisasi parameter export.
     * 
     * @param int $bulan Bulan laporan
     * @param int $tahun Tahun laporan
     * @param int|null $divisiId ID divisi untuk filter
     */
    public function __construct($bulan, $tahun, $divisiId = null)
    {
        $this->bulan    = $bulan;
        $this->tahun    = $tahun;
        $this->divisiId = $divisiId;
    }

    public function collection()
    {
        $query = Karyawan::with(['user', 'divisi', 'jabatan', 'shift'])
            ->where('status_aktif', true);

        if ($this->divisiId) {
            $query->where('divisi_id', $this->divisiId);
        }

        return $query->orderBy('nama')->get();
    }

    public function headings(): array
    {
        return [
            'No', 'NIP', 'Nama', 'Divisi', 'Jabatan', 'Shift',
            'Hadir', 'Terlambat', 'Alfa', 'Pulang Cepat'
        ];
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $hadir = Presensi::where('karyawan_id', $row->id)
            ->whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->whereNotNull('jam_masuk')
            ->where('status_absen', '!=', 'alfa')
            ->count();

        $terlambat = Presensi::where('karyawan_id', $row->id)
            ->whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->where('status_absen', 'terlambat')
            ->count();

        $alfa = Presensi::where('karyawan_id', $row->id)
            ->whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->where('status_absen', 'alfa')
            ->count();

        $pulangCepat = Presensi::where('karyawan_id', $row->id)
            ->whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->where('status_pulang', 'pulang_cepat')
            ->count();

        return [
            $no,
            $row->user->nip,
            $row->nama,
            $row->divisi->nama_divisi,
            $row->jabatan->nama_jabatan,
            $row->shift->nama_shift,
            $hadir,
            $terlambat,
            $alfa,
            $pulangCepat,
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
        return 'Rekap ' . Carbon::create($this->tahun, $this->bulan)->format('F Y');
    }
}