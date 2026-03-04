<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PresensiController extends Controller
{
   public function index(Request $request)
{
    $karyawan = Auth::user()->karyawan;
    $today    = today()->toDateString();

    $presensiHariIni = Presensi::where('karyawan_id', $karyawan->id)
        ->whereDate('tanggal', $today)
        ->first();

    $bulan  = $request->bulan  ?? date('n');
    $tahun  = $request->tahun  ?? date('Y');

    $query = Presensi::where('karyawan_id', $karyawan->id)
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun);

    if ($request->status_absen) {
        $query->where('status_absen', $request->status_absen);
    }

    $riwayat = $query->orderByDesc('tanggal')->paginate(15)->withQueryString();

    return view('karyawan.presensi.index', compact('karyawan', 'presensiHariIni', 'riwayat'));
}

    public function absenMasuk(Request $request)
    {
        $request->validate([
            'foto'      => 'required|string', // base64
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $karyawan = Auth::user()->karyawan;
        $today    = today()->toDateString();

        // Cek sudah absen masuk belum
        $exists = Presensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->whereNotNull('jam_masuk')
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Sudah absen masuk hari ini.'], 422);
        }

        // Simpan foto
        $fotoPath = $this->saveFoto($request->foto, 'masuk');

        // Tentukan status absen
        $shift       = $karyawan->shift;
        $jamMasuk    = Carbon::now();
        $batasWaktu  = Carbon::createFromTimeString($shift->jam_masuk)
            ->addMinutes($shift->toleransi_terlambat);
        $statusAbsen = $jamMasuk->gt($batasWaktu) ? 'terlambat' : 'tepat_waktu';

        Presensi::create([
            'karyawan_id' => $karyawan->id,
            'tanggal'     => $today,
            'jam_masuk'   => $jamMasuk->format('H:i:s'),
            'foto_masuk'  => $fotoPath,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'status_absen' => $statusAbsen,
        ]);

        return response()->json(['message' => 'Absen masuk berhasil!', 'status' => $statusAbsen]);
    }

    public function absenPulang(Request $request)
    {
        $request->validate([
            'foto'      => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $karyawan = Auth::user()->karyawan;
        $today    = today()->toDateString();

        $presensi = Presensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_pulang')
            ->first();

        if (!$presensi) {
            return response()->json(['message' => 'Belum absen masuk atau sudah absen pulang.'], 422);
        }

        $fotoPath   = $this->saveFoto($request->foto, 'pulang');
        $jamPulang  = Carbon::now();
        $shift      = $karyawan->shift;
        $jamPulangShift = Carbon::createFromTimeString($shift->jam_pulang);

        // Cek pulang cepat
        if ($jamPulang->lt($jamPulangShift)) {
            $presensi->status_absen = 'pulang_cepat';
        }

        $presensi->update([
            'jam_pulang'   => $jamPulang->format('H:i:s'),
            'foto_pulang'  => $fotoPath,
            'status_absen' => $presensi->status_absen,
        ]);

        return response()->json(['message' => 'Absen pulang berhasil!']);
    }

   private function saveFoto(string $base64, string $type): string
{
    $data     = explode(',', $base64);
    $imgData  = base64_decode($data[1]);
    $filename = 'presensi/' . $type . '_' . Auth::id() . '_' . time() . '.jpg';

    Storage::disk('public')->put($filename, $imgData);

    return $filename;
}
}