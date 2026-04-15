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

/**
 * @package App\\Exports
 * @author AbsensiKu
 * @version 1.0.0
 * 
 * Export laporan presensi karyawan ke Excel.
 */
class PresensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $bulan;
    protected $tahun;
    protected $divisiId;

    /**
     * Constructor untuk menginisialisasi parameter export.
     * 
     * @param int $bulan Bulan laporan (1-12)
     * @param int $tahun Tahun laporan
     * @param int|null $divisiId ID divisi opsional untuk filter
     */
    public function __construct($bulan, $tahun, $divisiId = null)
    {
        $this->bulan   = $bulan;
        $this->tahun   = $tahun;
        $this->divisiId = $divisiId;
    }

    /**
     * Mengambil koleksi data presensi untuk export.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
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

    /**
     * Header kolom Excel.
     * 
     * @return array
     */
    public function headings(): array
    {
        return [
            'No', 'NIP', 'Nama', 'Divisi', 'Shift',
            'Tanggal', 'Jam Masuk', 'Jam Pulang',
            'Status Masuk', 'Status Pulang'
        ];
    }

    /**
     * Mapping data row ke array Excel.
     * 
     * @param \App\\Models\\Presensi $row Data presensi
     * @return array
     */
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

    /**
     * Styling untuk worksheet Excel.
     * 
     * @param \PhpOffice\\PhpSpreadsheet\\Worksheet\\Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    /**
     * Judul sheet Excel.
     * 
     * @return string
     */
    public function title(): string
    {
        return 'Presensi ' . Carbon::create($this->tahun, $this->bulan)->format('F Y');
    }
}

