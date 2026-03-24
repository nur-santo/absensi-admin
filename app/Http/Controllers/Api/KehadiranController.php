<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kehadiran;
use App\Models\Wajah;
use App\Services\FaceService;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GENERATE KEHADIRAN
    |--------------------------------------------------------------------------
    | Tidak dipakai lagi. Generate kehadiran sekarang dilakukan oleh admin
    | melalui tombol "Mulai Shift" di dashboard (ShiftController@mulai).
    | Entry kehadiran dibuat otomatis untuk semua user di shift tersebut
    | dengan status ALPA, dan dicatat di tabel hari_kerja.
    |
    | Flutter tidak perlu memanggil endpoint ini.
    |--------------------------------------------------------------------------
    */

    // public function generate()
    // {
    //     $today = Carbon::today()->toDateString();
    //     $users = User::all();
    //     foreach ($users as $user) {
    //         Kehadiran::firstOrCreate(
    //             ['user_id' => $user->id, 'tanggal' => $today],
    //             ['status' => 'BELUM_ABSEN', 'jam_shift_masuk' => '08:00:00', 'terlambat' => false]
    //         );
    //     }
    //     return response()->json(['message' => 'Kehadiran hari ini berhasil dibuat']);
    // }

    /*
    |--------------------------------------------------------------------------
    | ABSEN MASUK
    |--------------------------------------------------------------------------
    | Alur:
    |   1. Verifikasi wajah via cosine distance
    |   2. Cek entry kehadiran hari ini sudah di-generate admin (via Mulai Shift)
    |   3. Cek belum absen / tidak sedang izin
    |   4. Update jam_masuk, status HADIR, hitung keterlambatan
    |--------------------------------------------------------------------------
    */
    public function absenMasuk(Request $request)
    {
        $request->validate([
            'data'       => ['required', 'array'],
            'mode_kerja' => ['nullable', 'in:WFO,WFH'],
        ]);

        $user  = $request->user();
        $today = Carbon::today()->toDateString();
        $now   = Carbon::now();

        // ── 1. Cek wajah terdaftar ──────────────────────────────────────
        $wajah = Wajah::where('user_id', $user->id)->first();

        if (!$wajah) {
            return response()->json([
                'message' => 'Wajah belum terdaftar. Hubungi admin.',
            ], 403);
        }

        $storedEmbedding = $wajah->data;

        if (!is_array($storedEmbedding) || !is_array($request->data)) {
            return response()->json([
                'message' => 'Format data wajah tidak valid.',
            ], 422);
        }

        if (count($storedEmbedding) !== count($request->data)) {
            return response()->json([
                'message' => 'Dimensi data wajah tidak sesuai.',
            ], 422);
        }

        // ── 2. Verifikasi cosine distance ───────────────────────────────
        $distance  = FaceService::cosineDistance($storedEmbedding, $request->data);
        $threshold = config('face.threshold', 0.4);

        if ($distance > $threshold) {
            return response()->json([
                'message' => 'Wajah tidak dikenali. Coba lagi.',
            ], 403);
        }

        // ── 3. Cek entry kehadiran hari ini ─────────────────────────────
        // Entry dibuat oleh admin via "Mulai Shift". Kalau null berarti
        // admin belum mulai shift hari ini.
        $kehadiran = Kehadiran::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if (!$kehadiran) {
            return response()->json([
                'message' => 'Shift hari ini belum dimulai oleh admin.',
            ], 404);
        }

        // ── 4. Cek status — tidak bisa absen kalau sedang izin/sakit/cuti ─
        if (in_array($kehadiran->status, ['IZIN', 'CUTI', 'SAKIT'])) {
            return response()->json([
                'message' => 'Tidak dapat absen. Status Anda: ' . $kehadiran->status . '.',
            ], 403);
        }

        // ── 5. Cek sudah absen sebelumnya ───────────────────────────────
        if ($kehadiran->jam_masuk) {
            return response()->json([
                'message' => 'Anda sudah absen masuk hari ini pada ' . $kehadiran->jam_masuk . '.',
            ], 422);
        }

        // ── 6. Hitung keterlambatan ─────────────────────────────────────
        $jamShift  = Carbon::createFromFormat('H:i:s', $kehadiran->jam_shift_masuk);
        $terlambat = $now->gt($jamShift);
        $menitTelat = $terlambat ? (int) $jamShift->diffInMinutes($now) : null;

        // ── 7. Update kehadiran ─────────────────────────────────────────
        $kehadiran->update([
            'jam_masuk'      => $now->format('H:i:s'),
            'status'         => 'HADIR',
            'mode_kerja'     => $request->mode_kerja ?? $user->mode_kerja ?? 'WFO',
            'terlambat'      => $terlambat,
            'menit_telat'    => $menitTelat,
            'keterlambatan'  => $terlambat
                ? gmdate('H:i:s', $jamShift->diffInSeconds($now))
                : null,
        ]);

        return response()->json([
            'message'    => 'Absen masuk berhasil.',
            'data'       => [
                'jam_masuk'   => $kehadiran->jam_masuk,
                'status'      => $kehadiran->status,
                'terlambat'   => $terlambat,
                'menit_telat' => $menitTelat,
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LIST KEHADIRAN BULANAN
    |--------------------------------------------------------------------------
    | Dipakai Flutter untuk tampilkan riwayat absensi user per bulan.
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $user  = $request->user();
        $bulan = $request->query('bulan', now()->month);
        $tahun = $request->query('tahun', now()->year);

        $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $endDate   = Carbon::create($tahun, $bulan, 1)->endOfMonth();

        $kehadiran = Kehadiran::where('user_id', $user->id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->paginate(10);

        return response()->json([
            'meta' => [
                'bulan' => (int) $bulan,
                'tahun' => (int) $tahun,
            ],
            'data' => $kehadiran,
        ]);
    }
}
