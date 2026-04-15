<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Karyawan;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Shift;
use App\Models\Presensi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AbsensiTest extends TestCase
{
    use RefreshDatabase;

    private function buatKaryawan()
    {
        $divisi  = Divisi::create(['nama_divisi' => 'Engineering']);
        $jabatan = Jabatan::create(['nama_jabatan' => 'Developer', 'divisi_id' => $divisi->id]);
        $shift   = Shift::create([
            'nama_shift'          => 'Shift Pagi',
            'jam_masuk'           => '07:00:00',
            'jam_pulang'          => '15:00:00',
            'toleransi_terlambat' => 15,
        ]);

        $user = User::create([
            'nip'      => '10122491',
            'password' => bcrypt('karyawan123'),
            'role'     => 'karyawan',
        ]);

        $karyawan = Karyawan::create([
            'user_id'      => $user->id,
            'nama'         => 'Budi Santoso',
            'divisi_id'    => $divisi->id,
            'jabatan_id'   => $jabatan->id,
            'shift_id'     => $shift->id,
            'status_aktif' => true,
        ]);

        return $user;
    }

    // ── HALAMAN PRESENSI BISA DIAKSES ──
    public function test_halaman_presensi_bisa_diakses_karyawan()
    {
        $user = $this->buatKaryawan();

        $response = $this->actingAs($user)->get('/karyawan/presensi');

        $response->assertStatus(200);
        $response->assertViewIs('karyawan.presensi.index');
    }

    // ── ABSEN MASUK BERHASIL ──
    public function test_absen_masuk_berhasil()
    {
        $user = $this->buatKaryawan();

        $response = $this->actingAs($user)->postJson('/karyawan/presensi/masuk', [
            'foto'      => 'data:image/jpeg;base64,' . base64_encode('fake-image'),
            'latitude'  => -6.82342,
            'longitude' => 107.10583,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Absen masuk berhasil!']);

        // Cek data tersimpan di DB
        $this->assertDatabaseHas('presensi', [
            'karyawan_id' => $user->karyawan->id,
            'tanggal'     => today()->toDateString(),
        ]);
    }

    // ── TIDAK BISA ABSEN MASUK 2X ──
    public function test_tidak_bisa_absen_masuk_dua_kali()
    {
        $user     = $this->buatKaryawan();
        $karyawan = $user->karyawan;

        // Buat presensi existing
        Presensi::create([
            'karyawan_id'  => $karyawan->id,
            'shift_id'     => $karyawan->shift_id,
            'tanggal'      => today()->toDateString(),
            'jam_masuk'    => '07:05:00',
            'status_absen' => 'tepat_waktu',
        ]);

        $response = $this->actingAs($user)->postJson('/karyawan/presensi/masuk', [
            'foto'      => 'data:image/jpeg;base64,' . base64_encode('fake-image'),
            'latitude'  => -6.82342,
            'longitude' => 107.10583,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'Sudah absen masuk hari ini.']);
    }

    // ── ABSEN PULANG BERHASIL ──
    public function test_absen_pulang_berhasil()
    {
        $user     = $this->buatKaryawan();
        $karyawan = $user->karyawan;

        Presensi::create([
            'karyawan_id'  => $karyawan->id,
            'shift_id'     => $karyawan->shift_id,
            'tanggal'      => today()->toDateString(),
            'jam_masuk'    => '07:05:00',
            'status_absen' => 'tepat_waktu',
        ]);

        $response = $this->actingAs($user)->postJson('/karyawan/presensi/pulang', [
            'foto'      => 'data:image/jpeg;base64,' . base64_encode('fake-image'),
            'latitude'  => -6.82342,
            'longitude' => 107.10583,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Absen pulang berhasil!']);

        $this->assertDatabaseHas('presensi', [
            'karyawan_id' => $karyawan->id,
            'tanggal'     => today()->toDateString(),
        ]);
    }

    // ── TIDAK BISA ABSEN PULANG TANPA ABSEN MASUK ──
    public function test_tidak_bisa_absen_pulang_tanpa_absen_masuk()
    {
        $user = $this->buatKaryawan();

        $response = $this->actingAs($user)->postJson('/karyawan/presensi/pulang', [
            'foto'      => 'data:image/jpeg;base64,' . base64_encode('fake-image'),
            'latitude'  => -6.82342,
            'longitude' => 107.10583,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['message' => 'Belum absen masuk atau sudah absen pulang.']);
    }

    // ── GUEST TIDAK BISA AKSES ──
    public function test_guest_tidak_bisa_akses_presensi()
    {
        $response = $this->get('/karyawan/presensi');

        $response->assertRedirect('/login');
    }
}