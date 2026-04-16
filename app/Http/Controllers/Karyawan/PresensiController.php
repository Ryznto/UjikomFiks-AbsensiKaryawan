<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\IzinCuti;
use App\Events\PresensiDisimpan;

// [INHERITANCE] = PresensiController mewarisi fitur dari Controller Laravel
class PresensiController extends Controller
{
    // [METHOD] = fungsi untuk menampilkan halaman presensi karyawan
    public function index(Request $request)
    {
        // [VARIABLE + OBJECT] = ambil data karyawan yang sedang login
        $karyawan = Auth::user()->karyawan;
        $today    = today()->toDateString();

        // [VARIABLE] = cek presensi karyawan hari ini
        $presensiHariIni = Presensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->first();

        // [VARIABLE] = ?? artinya jika bulan/tahun kosong, pakai bulan/tahun sekarang
        $bulan  = $request->bulan  ?? date('n');
        $tahun  = $request->tahun  ?? date('Y');

        // [VARIABLE] = query riwayat presensi karyawan berdasarkan bulan & tahun
        $query = Presensi::where('karyawan_id', $karyawan->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun);

        // [KONDISI] = jika ada filter status absen, tambahkan ke query
        if ($request->status_absen) {
            $query->where('status_absen', $request->status_absen);
        }

        // [VARIABLE] = hasil query diurutkan terbaru, dipaginasi 15 data per halaman
        $riwayat = $query->orderByDesc('tanggal')->paginate(15)->withQueryString();

        return view('karyawan.presensi.index', compact('karyawan', 'presensiHariIni', 'riwayat'));
    }

    // [METHOD] = fungsi untuk melakukan absen masuk
    public function absenMasuk(Request $request)
    {
        // [KONDISI] = validasi input yang dikirim, jika tidak sesuai otomatis return error
        $request->validate([
            'foto'      => 'required|string', // foto wajib dikirim dalam format base64
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // [VARIABLE + OBJECT] = ambil data karyawan yang sedang login
        $karyawan = Auth::user()->karyawan;
        $today    = today()->toDateString();

        // [VARIABLE] = cek apakah karyawan punya izin/cuti yang approved hari ini
        $izinAktif = IzinCuti::where('karyawan_id', $karyawan->id)
            ->where('status', 'approved')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->first();

        // [KONDISI] = jika ada izin aktif, karyawan tidak boleh absen
        if ($izinAktif) {
            // [ARRAY] = mapping jenis izin ke label yang lebih readable
            $jenisLabel = ['izin' => 'Izin', 'sakit' => 'Sakit', 'cuti' => 'Cuti'];
            // [KONDISI] = ?? jika jenis tidak ada di array, pakai nilai aslinya
            $label = $jenisLabel[$izinAktif->jenis] ?? $izinAktif->jenis;
            return response()->json([
                'message' => "Kamu sedang dalam status {$label} hari ini, tidak bisa melakukan absensi."
            ], 422);
        }

        // [KONDISI] = cek apakah karyawan sudah absen masuk hari ini
        $exists = Presensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->whereNotNull('jam_masuk')
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Sudah absen masuk hari ini.'], 422);
        }

        // [METHOD] = simpan foto base64 ke storage, return path nya
        $fotoPath = $this->saveFoto($request->foto, 'masuk');

        // [VARIABLE] = ambil shift karyawan & jam masuk sekarang
        $shift      = $karyawan->shift;
        $jamMasuk   = Carbon::now('Asia/Jakarta');

        // [VARIABLE] = hitung batas waktu toleransi terlambat
        // jam masuk shift + toleransi menit = batas waktu
        $batasWaktu = Carbon::today('Asia/Jakarta')
            ->setTimeFromTimeString($shift->jam_masuk)
            ->addMinutes($shift->toleransi_terlambat);

        // [KONDISI] = jika jam masuk melewati batas toleransi = terlambat, jika tidak = tepat waktu
        $statusAbsen = $jamMasuk->gt($batasWaktu) ? 'terlambat' : 'tepat_waktu';

        // [OBJECT] = simpan data presensi masuk ke database
        // [OBJECT] = simpan data presensi masuk ke database
        $presensi = Presensi::create([
            'karyawan_id'  => $karyawan->id,
            'shift_id'     => $karyawan->shift_id,
            'tanggal'      => $today,
            'jam_masuk'    => $jamMasuk->format('H:i:s'),
            'foto_masuk'   => $fotoPath,
            'latitude'     => $request->latitude,
            'longitude'    => $request->longitude,
            'status_absen' => $statusAbsen,
        ]);

        // [EVENT] = trigger Rule Engine & Token Interceptor secara otomatis
        \App\Events\PresensiDisimpan::dispatch($presensi);

        return response()->json(['message' => 'Absen masuk berhasil!', 'status' => $statusAbsen]);
    }

    // [METHOD] = fungsi untuk melakukan absen pulang
    public function absenPulang(Request $request)
    {
        // [KONDISI] = validasi input yang dikirim
        $request->validate([
            'foto'      => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // [VARIABLE + OBJECT] = ambil data karyawan yang sedang login
        $karyawan = Auth::user()->karyawan;
        $today    = today()->toDateString();

        // [VARIABLE] = cari data presensi hari ini yang sudah absen masuk tapi belum absen pulang
        $presensi = Presensi::where('karyawan_id', $karyawan->id)
            ->whereDate('tanggal', $today)
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_pulang')
            ->first();

        // [KONDISI] = jika belum absen masuk atau sudah absen pulang, tolak request
        if (!$presensi) {
            return response()->json(['message' => 'Belum absen masuk atau sudah absen pulang.'], 422);
        }

        // [METHOD] = simpan foto base64 ke storage, return path nya
        $fotoPath = $this->saveFoto($request->foto, 'pulang');

        // [VARIABLE] = ambil jam pulang sekarang & jam pulang shift
        $jamPulang      = Carbon::now('Asia/Jakarta');
        $shift          = $karyawan->shift;
        $jamPulangShift = Carbon::today('Asia/Jakarta')
            ->setTimeFromTimeString($shift->jam_pulang);

        // [KONDISI] = jika pulang sebelum jam shift = pulang cepat, jika tidak = tepat waktu
        $statusPulang = $jamPulang->lt($jamPulangShift) ? 'pulang_cepat' : 'tepat_waktu';

        // [OBJECT] = update data presensi dengan jam pulang & status pulang
        $presensi->update([
            'jam_pulang'    => $jamPulang->format('H:i:s'),
            'foto_pulang'   => $fotoPath,
            'status_pulang' => $statusPulang,
        ]);

        return response()->json(['message' => 'Absen pulang berhasil!']);
    }

    // [METHOD - PRIVATE] = fungsi khusus untuk simpan foto base64 ke storage
    // private = hanya bisa dipanggil dari dalam class ini saja
    private function saveFoto(string $base64, string $type): string
    {
        // [ARRAY] = explode memecah string base64 menjadi array berdasarkan tanda ','
        $data    = explode(',', $base64);
        // [VARIABLE] = decode base64 menjadi binary image
        $imgData = base64_decode($data[1]);
        // [VARIABLE] = buat nama file unik: presensi/masuk_userId_timestamp.jpg
        $filename = 'presensi/' . $type . '_' . Auth::id() . '_' . time() . '.jpg';

        // [METHOD] = simpan file ke storage/public
        Storage::disk('public')->put($filename, $imgData);

        // [RETURN] = kembalikan path file untuk disimpan ke database
        return $filename;
    }
}
