<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class LaporanAbsensiExport implements
    FromCollection,
    WithHeadings,
    WithCustomStartCell,
    WithEvents
{
    protected $users;
    protected $awal;
    protected $akhir;
    protected $hariKerja;
    protected $tanggalArr; // tanggal yang resmi di-generate

    public function __construct($users, $awal, $akhir, $hariKerja, array $tanggalArr = [])
    {
        $this->users      = $users;
        $this->awal       = $awal;
        $this->akhir      = $akhir;
        $this->hariKerja  = $hariKerja;
        $this->tanggalArr = $tanggalArr;
    }

    public function startCell(): string
    {
        return 'A4';
    }

    public function collection()
    {
        return $this->users->map(function ($user) {
            $k = $user->kehadiran;

            // Filter ke tanggal yang di-generate — konsisten dengan LaporanController
            $kG = count($this->tanggalArr)
                ? $k->whereIn('tanggal', $this->tanggalArr)
                : $k;

            $hadir    = $kG->where('status', 'HADIR')->count();
            $izin     = $kG->where('status', 'IZIN')->count();
            $cuti     = $kG->where('status', 'CUTI')->count();
            $sakit    = $kG->where('status', 'SAKIT')->count();
            $alpa     = $kG->where('status', 'ALPA')->count();
            $telat    = $kG->where('terlambat', true)->count();
            $wfo      = $kG->where('mode_kerja', 'WFO')->count();
            $wfh      = $kG->where('mode_kerja', 'WFH')->count();
            $pagi     = $kG->where('shift', 'PAGI')->count();
            $siang    = $kG->where('shift', 'SIANG')->count();
            $fulltime = $kG->where('shift', 'FULLTIME')->count();

            $totalValid = $hadir + $izin + $cuti + $sakit;
            $persen     = $this->hariKerja > 0
                ? round($totalValid / $this->hariKerja * 100)
                : 0;

            return [
                $user->name,
                $user->status ?? '-',
                $wfo, $wfh,
                $pagi, $siang, $fulltime,
                $hadir, $izin, $cuti, $sakit, $alpa,
                $telat,
                $persen . '%',
            ];
        });
    }

    public function headings(): array
    {
        return [
            [
                'Nama', 'Status',
                'WFO', 'WFH',
                'Pagi', 'Siang', 'Fulltime',
                'Hadir', 'Izin', 'Cuti', 'Sakit', 'Alpa',
                'Terlambat', '% Kehadiran',
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                $sheet->setCellValue('A1', 'LAPORAN ABSENSI KARYAWAN');
                $sheet->setCellValue('A2', "Periode: {$this->awal} s/d {$this->akhir}");
                $sheet->setCellValue('A3', "Hari Kerja (di-generate): {$this->hariKerja} hari");

                $lastCol = 'N'; // 14 kolom (A–N)

                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->mergeCells("A3:{$lastCol}3");

                // Style judul
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => 'center'],
                ]);
                $sheet->getStyle('A2:A3')->applyFromArray([
                    'font'      => ['size' => 10],
                    'alignment' => ['horizontal' => 'center'],
                ]);

                // Style header kolom (baris 4)
                $sheet->getStyle("A4:{$lastCol}4")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '1a2130']],
                    'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                ]);

                // Auto-width semua kolom
                foreach (range('A', $lastCol) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Freeze baris header
                $sheet->freezePane('A5');

                // Warna zebra pada baris data (mulai baris 5)
                $lastRow = $sheet->getHighestRow();
                for ($row = 5; $row <= $lastRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F8FAFC']],
                        ]);
                    }
                }

                // Border seluruh tabel
                $sheet->getStyle("A4:{$lastCol}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                            'color'       => ['rgb' => 'E5E7EB'],
                        ],
                    ],
                ]);
            },
        ];
    }
}