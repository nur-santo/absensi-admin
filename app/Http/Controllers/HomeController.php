<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Perizinan;
use App\Models\Kehadiran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{
public function index()
{
    $tanggal = Carbon::today()->toDateString();

    // ======================
    // DATA USER (PUNYA KAMU)
    // ======================
    $users = User::with([
        'shift',
        'kehadiran' => function ($q) use ($tanggal) {
            $q->whereDate('tanggal', $tanggal);
        },
    ])
    ->orderBy('shift_id')
    ->orderBy('name')
    ->get()
    ->groupBy(fn ($user) => $user->shift->nama_shift ?? 'Tanpa Shift');

    // ======================
    // GLOBAL STATUS
    // ======================
    $summary = Kehadiran::select('status', DB::raw('COUNT(*) as total'))
        ->whereDate('tanggal', $tanggal)
        ->groupBy('status')
        ->pluck('total', 'status')
        ->toArray();

    // ======================
    // TERLAMBAT vs TEPAT
    // ======================
    $tepat = Kehadiran::whereDate('tanggal', $tanggal)
        ->where('status', 'HADIR')
        ->where('terlambat', 0)
        ->count();

    $telat = Kehadiran::whereDate('tanggal', $tanggal)
        ->where('status', 'HADIR')
        ->where('terlambat', 1)
        ->count();

    $attendanceDetail = [
        'TEPAT WAKTU' => $tepat,
        'TERLAMBAT' => $telat
    ];

    // ======================
    // PER SHIFT
    // ======================
    $shiftSummary = Kehadiran::select('shift', 'status', DB::raw('COUNT(*) as total'))
        ->whereDate('tanggal', $tanggal)
        ->groupBy('shift', 'status')
        ->get()
        ->groupBy('shift');

    return view('home', compact(
        'tanggal',
        'users',
        'summary',
        'shiftSummary',
        'attendanceDetail'
    ));
}}