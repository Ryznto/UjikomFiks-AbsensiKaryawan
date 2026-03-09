@extends('layouts.karyawan')

@section('title', 'Presensi')

@section('content')

@if(session('success'))
<div class="alert alert-success">✅ {{ session('success') }}</div>
@endif

{{-- ABSEN SECTION --}}
<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">
            <div class="card-title-dot"></div>
            Absensi Hari Ini
        </div>
        <div style="font-family: var(--mono); font-size: 11px; color: var(--mid);" id="jam-sekarang"></div>
    </div>
    <div class="card-body">

        {{-- STATUS --}}
        <div style="margin-bottom: 16px;">
            <div class="info-row">
                <span class="info-label">Shift</span>
                <span class="info-value">{{ $karyawan->shift->nama_shift }}
                    <span style="color:var(--mid);font-size:11px;font-family:var(--mono);">
                        {{ $karyawan->shift->jam_masuk }} - {{ $karyawan->shift->jam_pulang }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">🌅 Jam Masuk</span>
                <span class="info-value mono" style="color: {{ $presensiHariIni?->jam_masuk ? 'var(--green)' : 'var(--mid)' }}">
                    {{ $presensiHariIni?->jam_masuk ?? '—' }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">🌆 Jam Pulang</span>
                <span class="info-value mono" style="color: {{ $presensiHariIni?->jam_pulang ? 'var(--amber)' : 'var(--mid)' }}">
                    {{ $presensiHariIni?->jam_pulang ?? '—' }}
                </span>
            </div>
        </div>

        {{-- LOKASI --}}
        <div id="lokasi-bar" style="display:flex; align-items:center; gap:10px; padding: 10px 14px;
            background: var(--surface-2); border: 1px solid var(--border); border-radius: var(--radius-sm);
            font-size: 12px; color: var(--mid); margin-bottom: 16px;">
            <div id="lokasi-dot" style="width:8px;height:8px;border-radius:50%;background:var(--mid);flex-shrink:0;"></div>
            <span id="lokasi-text">Mengambil lokasi...</span>
        </div>

        {{-- KAMERA --}}
        <div id="camera-wrap" style="display:none; margin-bottom: 16px;">
            <video id="video" autoplay playsinline
                style="width:100%; border-radius: var(--radius); background:#000; max-height: 280px; object-fit: cover;"></video>
            <canvas id="canvas" style="display:none;"></canvas>
            <div id="preview-wrap" style="display:none; margin-top: 10px;">
                <img id="preview" style="width:100%; border-radius: var(--radius); max-height: 280px; object-fit: cover;">
                <div style="display:flex; gap:8px; margin-top:10px;">
                    <button onclick="retake()" class="btn btn-secondary btn-inline">📷 Ulang</button>
                    <button onclick="submitAbsen()" id="btn-submit" class="btn btn-primary btn-inline" style="flex:1;">
                        ✅ Konfirmasi
                    </button>
                </div>
            </div>
            <button id="btn-capture" onclick="capture()" class="btn btn-primary" style="margin-top:10px;">
                📸 Ambil Foto
            </button>
        </div>

        {{-- TOMBOL ABSEN --}}
        @if(!$presensiHariIni || !$presensiHariIni->jam_masuk)
        <button onclick="startAbsen('masuk')" id="btn-absen-masuk" class="btn btn-primary">
            📷 Absen Masuk
        </button>
        @elseif(!$presensiHariIni->jam_pulang)
        <button onclick="startAbsen('pulang')" id="btn-absen-pulang" class="btn btn-success">
            🏠 Absen Pulang
        </button>
        @else
        <div class="btn btn-secondary" style="cursor:default; justify-content:center;">
            ✅ Absensi Hari Ini Selesai
        </div>
        @endif

    </div>
</div>

<div style="display: flex; justify-content: flex-end; margin-bottom: 16px;">
    <a href="{{ route('karyawan.koreksi-absen.create') }}" class="btn btn-secondary btn-inline">
        ✏️ Ajukan Koreksi Absen
    </a>
</div>

{{-- RIWAYAT --}}
<div class="card fade-in">
    <div class="card-header">
        <div class="card-title">Riwayat Presensi</div>
    </div>
    <div style="padding: 12px 18px; border-bottom: 1px solid var(--border);">
        <form method="GET" action="{{ route('karyawan.presensi.index') }}" style="display: flex; gap: 8px; flex-wrap: wrap;">
            <select name="bulan" class="form-control" style="width: auto; flex: 1;">
                @foreach(range(1, 12) as $m)
                <option value="{{ $m }}" {{ request('bulan', date('n')) == $m ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
                @endforeach
            </select>
            <select name="tahun" class="form-control" style="width: auto; flex: 1;">
                @foreach(range(date('Y'), date('Y') - 2) as $y)
                <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>
                    {{ $y }}
                </option>
                @endforeach
            </select>
            <select name="status_absen" class="form-control" style="width: auto; flex: 1;">
                <option value="">Semua</option>
                <option value="tepat_waktu"  {{ request('status_absen') == 'tepat_waktu'  ? 'selected' : '' }}>Tepat Waktu</option>
                <option value="terlambat"    {{ request('status_absen') == 'terlambat'    ? 'selected' : '' }}>Terlambat</option>
                <option value="pulang_cepat" {{ request('status_absen') == 'pulang_cepat' ? 'selected' : '' }}>Pulang Cepat</option>
                <option value="alfa"         {{ request('status_absen') == 'alfa'         ? 'selected' : '' }}>Alfa</option>
            </select>
            <button type="submit" class="btn btn-primary btn-inline">Filter</button>
        </form>
    </div>

    <div class="table-wrap">
          <table>
          <thead>
    <tr>
        <th>Tanggal</th>
        <th>Masuk</th>
        <th>Pulang</th>
        <th>Status Masuk</th>
        <th>Status Pulang</th>
    </tr>
</thead>
<tbody>
    @forelse($riwayat as $p)
    <tr>
        <td style="font-family:var(--mono);font-size:11px;">
            {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
        </td>
        <td style="font-family:var(--mono);color:var(--green);font-size:12px;">
            {{ $p->jam_masuk ?? '—' }}
        </td>
        <td style="font-family:var(--mono);color:var(--amber);font-size:12px;">
            {{ $p->jam_pulang ?? '—' }}
        </td>
        <td>
            @php
                $badgeMasuk = [
                    'tepat_waktu' => ['class' => 'badge-green', 'label' => 'Tepat'],
                    'terlambat'   => ['class' => 'badge-amber', 'label' => 'Lambat'],
                    'alfa'        => ['class' => 'badge-red',   'label' => 'Alfa'],
                ];
                $bm = $badgeMasuk[$p->status_absen] ?? ['class' => 'badge-gray', 'label' => '-'];
            @endphp
            <span class="badge {{ $bm['class'] }}" style="font-size:10px;padding:2px 7px;">
                {{ $bm['label'] }}
            </span>
        </td>
        <td>
            @if($p->jam_pulang)
                @php
                    $badgePulang = [
                        'tepat_waktu'  => ['class' => 'badge-green', 'label' => 'Tepat'],
                        'pulang_cepat' => ['class' => 'badge-amber', 'label' => 'P.Cepat'],
                    ];
                    $bp = $badgePulang[$p->status_pulang] ?? ['class' => 'badge-gray', 'label' => '-'];
                @endphp
                <span class="badge {{ $bp['class'] }}" style="font-size:10px;padding:2px 7px;">
                    {{ $bp['label'] }}
                </span>
            @else
                <span style="color:var(--mid);font-size:12px;">—</span>
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="5">
            <div class="empty-state"><p>Belum ada riwayat presensi</p></div>
        </td>
    </tr>
    @endforelse
</tbody>
        </table>
    </div>
    @if($riwayat->hasPages())
    <div style="padding: 14px 18px; border-top: 1px solid var(--border);">
        {{ $riwayat->links() }}
    </div>
    @endif
</div>
 

@endsection

@push('scripts')
<script>
    // ── JAM REALTIME ──
    function updateJam() {
        const now = new Date();
        const h = String(now.getHours()).padStart(2,'0');
        const m = String(now.getMinutes()).padStart(2,'0');
        const s = String(now.getSeconds()).padStart(2,'0');
        const el = document.getElementById('jam-sekarang');
        if (el) el.textContent = `${h}:${m}:${s}`;
    }
    updateJam();
    setInterval(updateJam, 1000);

    // ── GPS ──
    let latitude  = null;
    let longitude = null;

    function getLocation() {
        if (!navigator.geolocation) {
            setLokasi(false, 'GPS tidak didukung browser ini');
            return;
        }
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                latitude  = pos.coords.latitude;
                longitude = pos.coords.longitude;
                setLokasi(true, `${latitude.toFixed(5)}, ${longitude.toFixed(5)}`);
            },
            () => setLokasi(false, 'Gagal mengambil lokasi, izinkan akses GPS')
        );
    }

    function setLokasi(ok, text) {
        document.getElementById('lokasi-dot').style.background = ok ? 'var(--green)' : 'var(--red)';
        document.getElementById('lokasi-text').textContent = ok ? `📍 ${text}` : `⚠️ ${text}`;
    }

    getLocation();

    // ── KAMERA ──
    let stream      = null;
    let fotoBase64  = null;
    let absenType   = null;

    async function startAbsen(type) {
        if (!latitude || !longitude) {
            alert('Lokasi belum didapat, aktifkan GPS dan coba lagi.');
            return;
        }

        absenType = type;

        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user' }
            });
            document.getElementById('video').srcObject = stream;
            document.getElementById('camera-wrap').style.display = 'block';

            // Sembunyikan tombol absen
            const btnMasuk  = document.getElementById('btn-absen-masuk');
            const btnPulang = document.getElementById('btn-absen-pulang');
            if (btnMasuk)  btnMasuk.style.display  = 'none';
            if (btnPulang) btnPulang.style.display  = 'none';

        } catch (err) {
            alert('Gagal mengakses kamera: ' + err.message);
        }
    }

    function capture() {
        const video   = document.getElementById('video');
        const canvas  = document.getElementById('canvas');
        canvas.width  = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);

        fotoBase64 = canvas.toDataURL('image/jpeg', 0.8);

        document.getElementById('preview').src = fotoBase64;
        document.getElementById('preview-wrap').style.display = 'block';
        document.getElementById('btn-capture').style.display  = 'none';
        document.getElementById('video').style.display        = 'none';
    }

    function retake() {
        fotoBase64 = null;
        document.getElementById('preview-wrap').style.display = 'none';
        document.getElementById('btn-capture').style.display  = 'block';
        document.getElementById('video').style.display        = 'block';
    }

    async function submitAbsen() {
        if (!fotoBase64 || !latitude || !longitude) return;

        const btn = document.getElementById('btn-submit');
        btn.textContent = 'Menyimpan...';
        btn.disabled    = true;

        const url = absenType === 'masuk'
            ? '{{ route("karyawan.presensi.masuk") }}'
            : '{{ route("karyawan.presensi.pulang") }}';

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    foto:      fotoBase64,
                    latitude:  latitude,
                    longitude: longitude,
                })
            });

            const data = await res.json();

            if (res.ok) {
                // Stop kamera
                if (stream) stream.getTracks().forEach(t => t.stop());
                alert('✅ ' + data.message);
                window.location.reload();
            } else {
                alert('❌ ' + data.message);
                btn.textContent = '✅ Konfirmasi';
                btn.disabled    = false;
            }
        } catch (err) {
            alert('Terjadi kesalahan, coba lagi.');
            btn.textContent = '✅ Konfirmasi';
            btn.disabled    = false;
        }
    }
</script>
@endpush