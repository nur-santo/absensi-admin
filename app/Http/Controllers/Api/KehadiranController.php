<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kehadiran;
use App\Models\Wajah;
use App\Models\User;
use App\Services\FaceService;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | GENERATE KEHADIRAN (UNTUK ADMIN)
    |--------------------------------------------------------------------------
    */
    public function generate()
    {
        $today = Carbon::today()->toDateString();

        $users = User::all();

        foreach ($users as $user) {

            Kehadiran::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'tanggal' => $today
                ],
                [
                    'status' => 'BELUM_ABSEN',
                    'jam_shift_masuk' => '08:00:00',
                    'terlambat' => false
                ]
            );
        }

        return response()->json([
            'message' => 'Kehadiran hari ini berhasil dibuat'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ABSEN MASUK
    |--------------------------------------------------------------------------
    */
    public function absenMasuk(Request $request)
    {
        $request->validate([
            'data' => ['required', 'array'],
            'mode_kerja' => ['nullable', 'in:WFO,WFH'],
        ]);

        $user = $request->user();
        $today = Carbon::today()->toDateString();
        $now = Carbon::now();

        $wajah = Wajah::where('user_id', $user->id)->first();

        if (!$wajah) {
            return response()->json([
                'message' => 'Wajah belum terdaftar'
            ], 403);
        }

        $storedEmbedding = $wajah->data;

        if (!is_array($storedEmbedding) || !is_array($request->data)) {
            return response()->json([
                'message' => 'Data wajah tidak valid'
            ], 422);
        }

        if (count($storedEmbedding) !== count($request->data)) {
            return response()->json([
                'message' => 'Data wajah tidak cocok (format berbeda)'
            ], 422);
        }

        $distance = FaceService::cosineDistance(
            $storedEmbedding,
            $request->data
        );

        $threshold = config('face.threshold', 0.4);

        if ($distance > $threshold) {
            return response()->json([
                'message' => 'Wajah tidak cocok'
            ], 403);
        }

        $kehadiran = Kehadiran::where('user_id', $user->id)
            ->where('tanggal', $today)
            ->first();

        if (!$kehadiran) {
            return response()->json([
                'message' => 'Data kehadiran hari ini belum dibuat'
            ], 404);
        }

        if (in_array($kehadiran->status, ['IZIN', 'CUTI', 'SAKIT'])) {
            return response()->json([
                'message' => 'Sedang izin'
            ], 403);
        }

        if ($kehadiran->jam_masuk) {
            return response()->json([
                'message' => 'Sudah absen masuk'
            ], 422);
        }

        $jamShift = Carbon::createFromFormat('H:i:s', $kehadiran->jam_shift_masuk);
        $terlambat = $now->gt($jamShift);

        $kehadiran->update([
            'jam_masuk' => $now->format('H:i:s'),
            'status' => 'HADIR',
            'mode_kerja' => $request->mode_kerja ?? 'WFO',
            'terlambat' => $terlambat,
            'keterlambatan' => $terlambat
                ? gmdate('H:i:s', $jamShift->diffInSeconds($now))
                : null,
        ]);

        return response()->json([
            'message' => 'Absen masuk berhasil'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LIST KEHADIRAN BULANAN
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $user = $request->user();

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
            'data' => $kehadiran
        ]);
    }
}
