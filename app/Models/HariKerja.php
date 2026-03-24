<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HariKerja extends Model
{
    protected $table = 'hari_kerja';

    protected $fillable = [
        'tanggal',
        'shift',
        'generated_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    // ── Scope helpers ──────────────────────────────────────

    /**
     * Tanggal yang sudah di-generate dalam rentang tertentu.
     * Dipakai di laporan untuk hitung hariKerja.
     */
    public function scopeInPeriod($query, string $awal, string $akhir)
    {
        return $query->whereBetween('tanggal', [$awal, $akhir]);
    }

    /**
     * Cek apakah tanggal dan shift tertentu sudah di-generate.
     */
    public static function sudahDigenerate(string $tanggal, string $shift): bool
    {
        return static::where('tanggal', $tanggal)
            ->where('shift', $shift)
            ->exists();
    }
}