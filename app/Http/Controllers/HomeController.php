<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perizinan;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $tanggal = Carbon::today()->toDateString();

        $users = User::with([
            'shift',
            'kehadiran' => function ($q) use ($tanggal) {
                $q->whereDate('tanggal', $tanggal);
            },
        ])
        ->orderBy('shift_id')
        ->orderBy('name')
        ->get()
        ->map(function ($user) {

            $kehadiran = $user->kehadiran->first();

            if ($kehadiran && $kehadiran->jam_masuk && $kehadiran->jam_shift_masuk) {

                $jamMasuk = Carbon::parse($kehadiran->jam_masuk);
                $jamShift = Carbon::parse($kehadiran->jam_shift_masuk);

                if ($jamMasuk->gt($jamShift)) {
                    $kehadiran->keterlambatan =
                        $jamShift->diff($jamMasuk)->format('%H:%I:%S');
                } else {
                    $kehadiran->keterlambatan = '00:00:00';
                }
            } else {
                if ($kehadiran) {
                    $kehadiran->keterlambatan = '-';
                }
            }

            return $user;
        })
        ->groupBy(fn ($user) => $user->shift->nama_shift ?? 'Tanpa Shift');

        $jumlahPerizinanPending = Perizinan::where('status', 'PENDING')->count();

        return view('home', compact(
            'tanggal',
            'users',
            'jumlahPerizinanPending'
        ));
    }
}