<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\HariKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanAbsensiExport;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggalAwal  = $request->tanggal_awal
            ?? Carbon::now()->startOfMonth()->toDateString();
        $tanggalAkhir = $request->tanggal_akhir
            ?? Carbon::now()->endOfMonth()->toDateString();

        // ── 1. Tanggal yang resmi di-generate ────────────────────────
        $tanggalGenerated = HariKerja::inPeriod($tanggalAwal, $tanggalAkhir)
            ->distinct('tanggal')
            ->pluck('tanggal')
            ->unique()
            ->sort()
            ->values();

        $hariKerja  = $tanggalGenerated->count();
        $tanggalArr = $tanggalGenerated->map->toDateString()->toArray();

        // ── 2. Users + kehadiran ──────────────────────────────────────
        $users = User::with([
            'kehadiran' => fn($q) => $q
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->orderBy('tanggal'),
        ])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->get();

        // ── 3. Flatten + filter ke tanggal yang di-generate ──────────
        $allK  = $users->flatMap->kehadiran;

        // FIX: gunakan Carbon::parse()->toDateString() untuk compare
        $allKG = $allK->filter(
            fn($k) => in_array(Carbon::parse($k->tanggal)->toDateString(), $tanggalArr)
        );

        // ── 4. Summary ────────────────────────────────────────────────
        $summary = [
            'total_hadir' => $allKG->where('status', 'HADIR')->count(),
            'total_izin'  => $allKG->where('status', 'IZIN')->count(),
            'total_cuti'  => $allKG->where('status', 'CUTI')->count(),
            'total_sakit' => $allKG->where('status', 'SAKIT')->count(),
            'total_alpa'  => $allKG->where('status', 'ALPA')->count(),
            'total_telat' => $allKG->where('terlambat', true)->count(),
            'avg_telat'   => $allKG->where('terlambat', true)->avg('menit_telat') ?? 0,
            'total_wfo'   => $allKG->where('mode_kerja', 'WFO')->count(),
            'total_wfh'   => $allKG->where('mode_kerja', 'WFH')->count(),
        ];

        // ── 5. Tren harian ────────────────────────────────────────────
        $trenHarian = $tanggalGenerated->map(function ($tgl) use ($allKG) {
            $tglStr = $tgl->toDateString();

            // FIX: filter pakai Carbon::parse() karena $k->tanggal bisa Carbon/string
            $hari = $allKG->filter(
                fn($k) => Carbon::parse($k->tanggal)->toDateString() === $tglStr
            );

            return [
                'tanggal' => $tglStr,
                'hadir'   => $hari->where('status', 'HADIR')->count(),
                'alpa'    => $hari->where('status', 'ALPA')->count(),
                'izin'    => $hari->whereIn('status', ['IZIN', 'CUTI', 'SAKIT'])->count(),
            ];
        })->values();

        // ── 6. Per-user stats ─────────────────────────────────────────
        $usersData = $users->map(function ($user) use ($hariKerja, $tanggalArr) {
            $k = $user->kehadiran;

            // FIX: filter pakai Carbon::parse() bukan langsung whereIn
            $kG = $k->filter(
                fn($row) => in_array(Carbon::parse($row->tanggal)->toDateString(), $tanggalArr)
            );

            $hadir    = $kG->where('status', 'HADIR')->count();
            $izin     = $kG->where('status', 'IZIN')->count();
            $cuti     = $kG->where('status', 'CUTI')->count();
            $sakit    = $kG->where('status', 'SAKIT')->count();
            $alpa     = $kG->where('status', 'ALPA')->count();
            $telat    = $kG->where('terlambat', true)->count();
            $avgTelat = $kG->where('terlambat', true)->avg('menit_telat') ?? 0;
            $wfo      = $kG->where('mode_kerja', 'WFO')->count();
            $wfh      = $kG->where('mode_kerja', 'WFH')->count();
            $pagi     = $kG->where('shift', 'PAGI')->count();
            $siang    = $kG->where('shift', 'SIANG')->count();
            $fulltime = $kG->where('shift', 'FULLTIME')->count();

            $totalValid = $kG->whereIn('status', ['HADIR', 'IZIN', 'CUTI', 'SAKIT'])->count();
            $persen     = $hariKerja > 0 ? round($totalValid / $hariKerja * 100) : 0;

            // FIX: format tanggal di log agar blade dapat string bersih
            $logHarian = $k->map(fn($row) => [
                'tanggal'     => Carbon::parse($row->tanggal)->toDateString(),
                'shift'       => $row->shift,
                'jam_masuk'   => $row->jam_masuk
                    ? Carbon::parse($row->jam_masuk)->format('H:i')
                    : null,
                'status'      => $row->status,
                'terlambat'   => (bool) $row->terlambat,
                'menit_telat' => $row->menit_telat,
                'mode_kerja'  => $row->mode_kerja,
            ])->values();

            $perBulan = $k->groupBy(
                fn($r) => Carbon::parse($r->tanggal)->format('Y-m')
            )->map(fn($g) => [
                'hadir' => $g->where('status', 'HADIR')->count(),
                'alpa'  => $g->where('status', 'ALPA')->count(),
                'izin'  => $g->whereIn('status', ['IZIN', 'CUTI', 'SAKIT'])->count(),
            ]);

            return [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'status'    => $user->status,
                'hadir'     => $hadir,
                'izin'      => $izin,
                'cuti'      => $cuti,
                'sakit'     => $sakit,
                'alpa'      => $alpa,
                'telat'     => $telat,
                'avg_telat' => round($avgTelat),
                'persen'    => $persen,
                'log'       => $logHarian,
                'per_bulan' => $perBulan,
                'wfo'       => $wfo,
                'wfh'       => $wfh,
                'pagi'      => $pagi,
                'siang'     => $siang,
                'fulltime'  => $fulltime,
            ];
        });

        return view('laporan', compact(
            'usersData',
            'tanggalAwal',
            'tanggalAkhir',
            'hariKerja',
            'summary',
            'trenHarian',
        ));
    }

    public function export(Request $request)
    {
        $tanggalAwal  = $request->tanggal_awal
            ?? Carbon::now()->startOfMonth()->toDateString();
        $tanggalAkhir = $request->tanggal_akhir
            ?? Carbon::now()->endOfMonth()->toDateString();

        // hariKerja dari hari_kerja, konsisten dengan index()
        $tanggalArr = HariKerja::inPeriod($tanggalAwal, $tanggalAkhir)
            ->distinct('tanggal')
            ->pluck('tanggal')
            ->unique()
            ->map->toDateString()
            ->toArray();

        $hariKerja = count($tanggalArr);

        $users = User::with([
            'kehadiran' => fn($q) => $q
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->orderBy('tanggal'),
        ])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->get();

        // PDF view juga hanya hitung dari tanggal yang di-generate
        // — pass $tanggalArr supaya pdf.blade bisa filter sendiri
        $now = now()->format('Ymd_His');

        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView(
                'laporan.pdf',
                compact('users', 'tanggalAwal', 'tanggalAkhir', 'hariKerja', 'tanggalArr')
            )->setPaper('a4', 'landscape');

            return $pdf->download("laporan-absensi_{$now}.pdf");
        }

        return Excel::download(
            new LaporanAbsensiExport($users, $tanggalAwal, $tanggalAkhir, $hariKerja, $tanggalArr),
            "laporan-absensi_{$now}.xlsx"
        );
    }
}
