<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Kehadiran;
use App\Models\Libur;
use Carbon\Carbon;

class GenerateDailyKehadiran extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kehadiran:generate {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate kehadiran harian untuk semua user berdasarkan shift, skip weekend & hari libur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ambil tanggal target
        $tanggal = $this->argument('date')
            ? Carbon::parse($this->argument('date'))->toDateString()
            : Carbon::today()->toDateString();

        // Skip weekend
        $hari = Carbon::parse($tanggal)->dayOfWeek; // 0=Sun, 6=Sat
        if ($hari == Carbon::SATURDAY || $hari == Carbon::SUNDAY) {
            $this->info("Tanggal {$tanggal} adalah weekend, kehadiran tidak digenerate.");
            return 0;
        }

        // Skip hari libur
        if (Libur::where('tanggal', $tanggal)->exists()) {
            $this->info("Tanggal {$tanggal} adalah hari libur, kehadiran tidak digenerate.");
            return 0;
        }

        // Ambil semua user beserta shift
        $users = User::with('shift')->get();

        foreach ($users as $user) {
            $shift = $user->shift;

            if (!$shift) {
                $this->error("User ID {$user->id} tidak memiliki shift");
                continue;
            }

            Kehadiran::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'tanggal' => $tanggal,
                ],
                [
                    'shift' => $shift->nama_shift,
                    'jam_shift_masuk' => $shift->mulai,
                    'mode_kerja' => $user->mode_kerja,
                    'status' => 'ALPA',
                ]
            );
        }

        $this->info("Kehadiran tanggal {$tanggal} berhasil digenerate untuk semua user.");
        return 0;
    }
}
