<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\User;
use App\Models\Kehadiran;

class ShiftsController extends Controller
{
    public function index()
    {
        $shifts = Shift::orderBy('nama_shift')->get();
        return view('settings.shifts', compact('shifts'));
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'nama_shift' => 'required|string',
            'mulai' => 'required',
            'selesai' => 'required|after:mulai'
        ]);

        $shift->update($request->only('nama_shift', 'mulai', 'selesai'));

        return back()->with('success', 'Shift berhasil diperbarui');
    }
    public function mulai(Request $request)
    {
        $shift = $request->shift;
        $tanggal = $request->tanggal;


        $users = User::whereHas('shift', function ($q) use ($shift) {
            $q->where('nama_shift', $shift);
        })->get();

        foreach ($users as $user) {
            $exists = Kehadiran::where('user_id', $user->id)
                ->whereDate('tanggal', $tanggal)
                ->exists();

            if (!$exists) {
                Kehadiran::create([
                    'user_id' => $user->id,
                    'shift' => $shift,
                    'tanggal' => $tanggal,
                    'status' => 'ALPA',
                    'jam_shift_masuk' => $user->shift->jam_masuk ?? '08:00:00'
                ]);
            }
        }

        return back()->with('success', "Shift $shift dimulai");
    }
}
