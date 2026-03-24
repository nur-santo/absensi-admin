<?php

namespace App\Http\Controllers;

use App\Models\HariKerja;
use App\Models\Kehadiran;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShiftController extends Controller
{
    /**
     * Tampilkan daftar shift untuk halaman pengaturan.
     */
    public function index()
    {
        $shifts = Shift::orderBy('mulai')->get();

        return view('settings.shifts', compact('shifts'));
    }

    /**
     * Update nama/jam shift.
     * Perubahan hanya berlaku untuk generate berikutnya —
     * data kehadiran yang sudah di-generate tidak berubah.
     */
    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'nama_shift' => 'required|string|max:50',
            'mulai'      => 'required|date_format:H:i',
            'selesai'    => 'required|date_format:H:i',
        ]);

        $shift->update([
            'nama_shift' => strtoupper(trim($request->nama_shift)),
            'mulai'      => $request->mulai,
            'selesai'    => $request->selesai,
        ]);

        return back()->with('success', "Shift {$shift->nama_shift} berhasil diperbarui.");
    }

    /**
     * Mulai shift: generate entry kehadiran ALPA untuk semua user
     * di shift tersebut, dan catat tanggal+shift ke tabel hari_kerja.
     */
    public function mulai(Request $request)
    {
        $request->validate([
            'shift'   => 'required|string',
            'tanggal' => 'required|date',
        ]);

        $shift   = $request->shift;
        $tanggal = $request->tanggal;

        // Guard: jangan generate ulang shift yang sama di hari yang sama
        if (HariKerja::sudahDigenerate($tanggal, $shift)) {
            return back()->with('error', "Shift {$shift} pada {$tanggal} sudah pernah di-generate.");
        }

        DB::transaction(function () use ($shift, $tanggal) {

            // Catat ke tabel hari_kerja
            HariKerja::create([
                'tanggal'      => $tanggal,
                'shift'        => $shift,
                'generated_by' => Auth::id(),
            ]);

            // Ambil semua user yang punya shift ini (via relasi shift)
            $users = User::whereHas('shift', fn($q) => $q->where('nama_shift', $shift))
                ->with('shift')
                ->get();

            // Generate entry kehadiran ALPA sebagai default
            foreach ($users as $user) {
                Kehadiran::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'tanggal' => $tanggal,
                    ],
                    [
                        'status'          => 'ALPA',
                        'shift'           => $shift,
                        'jam_shift_masuk' => $user->shift->mulai,
                        'mode_kerja'      => $user->mode_kerja,
                    ]
                );
            }
        });

        return back()->with('success', "Shift {$shift} berhasil dimulai.");
    }
}