<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HariKerja;
use App\Models\Kehadiran;
use App\Models\Perizinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PerizinanApprovalController extends Controller
{
    /**
     * List perizinan PENDING
     */
    public function index()
    {
        $perizinan = Perizinan::with('user')
            ->where('status', 'PENDING')
            ->orderBy('tanggal_mulai')
            ->get();

        return view('perizinan', compact('perizinan'));
    }

    /**
     * Approve perizinan.
     *
     * Logika update kehadiran:
     *   - Selalu update entry yang SUDAH ADA di tabel kehadiran
     *     (tanggal sudah di-generate via Mulai Shift)
     *   - Untuk tanggal yang BELUM di-generate:
     *     → buat entry baru HANYA jika bukan weekend
     *     → entry ini TIDAK dicatat di hari_kerja, sehingga
     *       tidak memengaruhi hitung hariKerja di laporan
     */
    public function approve(Perizinan $perizinan)
    {
        $perizinan->update([
            'status'      => 'DISETUJUI',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        $user   = $perizinan->user;
        $shift  = $user->shift;
        $jenis  = $perizinan->jenis; // IZIN | SAKIT | CUTI

        $period = CarbonPeriod::create(
            $perizinan->tanggal_mulai,
            $perizinan->tanggal_selesai
        );

        $updated  = 0;
        $inserted = 0;
        $skipped  = 0;

        foreach ($period as $date) {
            $tgl = $date->toDateString();

            // Cek apakah tanggal ini sudah digenerate (ada di tabel hari_kerja)
            $sudahGenerate = HariKerja::where('tanggal', $tgl)
                ->where('shift', $shift->nama_shift)
                ->exists();

            if ($sudahGenerate) {
                // Tanggal sudah digenerate lalu update entry yang ada
                Kehadiran::where('user_id', $perizinan->user_id)
                    ->where('tanggal', $tgl)
                    ->update(['status' => $jenis]);
                $updated++;

            } elseif ($date->isWeekend()) {
                // Weekend dan belum digenerate skip sepenuhnya
                $skipped++;

            } else {
                // Hari kerja tapi belum di-generate (misal: izin diajukan
                // sebelum shift di-generate, atau izin masa depan).
                // Buat entry kehadiran tapi JANGAN catat ke hari_kerja
                // supaya laporan tidak terganggu.
                Kehadiran::updateOrCreate(
                    [
                        'user_id' => $perizinan->user_id,
                        'tanggal' => $tgl,
                    ],
                    [
                        'status'          => $jenis,
                        'shift'           => $shift->nama_shift,
                        'jam_shift_masuk' => $shift->mulai,
                        'mode_kerja'      => $user->mode_kerja,
                    ]
                );
                $inserted++;
            }
        }

        // kirim pesan
        $parts = [];
        if ($updated)  $parts[] = "{$updated} hari diperbarui";
        if ($inserted) $parts[] = "{$inserted} hari ditambahkan (belum di-generate)";
        if ($skipped)  $parts[] = "{$skipped} hari weekend dilewati";

        $msg = 'Perizinan disetujui' . ($parts ? ': ' . implode(', ', $parts) : '');

        return back()->with('success', $msg);
    }

    /**
     * Reject perizinan.
     * Jika sebelumnya sudah pernah disetujui dan kehadiran sudah diubah,
     * kembalikan status kehadiran ke ALPA untuk tanggal yang sudah di-generate.
     */
    public function reject(Request $request, Perizinan $perizinan)
    {
        $request->validate([
            'keterangan' => 'nullable|string',
        ]);

        // Kalau sebelumnya DISETUJUI, rollback status kehadiran
        if ($perizinan->status === 'DISETUJUI') {
            $user  = $perizinan->user;
            $shift = $user->shift;

            $period = CarbonPeriod::create(
                $perizinan->tanggal_mulai,
                $perizinan->tanggal_selesai
            );

            foreach ($period as $date) {
                $tgl = $date->toDateString();

                // Hanya rollback yang memang sudah di-generate
                $sudahGenerate = HariKerja::where('tanggal', $tgl)
                    ->where('shift', $shift->nama_shift)
                    ->exists();

                if ($sudahGenerate) {
                    Kehadiran::where('user_id', $perizinan->user_id)
                        ->where('tanggal', $tgl)
                        ->update(['status' => 'ALPA']);
                } else {
                    // Entry dibuat saat approve tapi bukan dari generate 
                    // hapus saja supaya tidak mengotori data
                    Kehadiran::where('user_id', $perizinan->user_id)
                        ->where('tanggal', $tgl)
                        ->delete();
                }
            }
        }

        $perizinan->update([
            'status'      => 'DITOLAK',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'keterangan'  => $request->keterangan,
        ]);

        return back()->with('success', 'Perizinan ditolak.');
    }
}