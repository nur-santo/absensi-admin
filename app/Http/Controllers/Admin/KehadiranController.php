<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kehadiran;
use Carbon\Carbon;

class KehadiranController extends Controller
{
    public function generate()
    {
        $today = Carbon::today()->toDateString();

        $users = User::all();

        foreach ($users as $user) {

            $cek = Kehadiran::where('user_id', $user->id)
                ->where('tanggal', $today)
                ->first();

            if (!$cek) {
                Kehadiran::create([
                    'user_id' => $user->id,
                    'tanggal' => $today,
                    'status' => 'ALPA',
                    'jam_shift_masuk' => '08:00:00',
                    'shift' => optional($user->shift)->nama ?? 'NON SHIFT'
                ]);
            }
        }

        return back()->with('success','Kehadiran hari ini berhasil digenerate');
    }
}