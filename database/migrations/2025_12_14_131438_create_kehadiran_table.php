<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kehadiran extends Model
{
    use HasFactory;

    protected $table = 'kehadiran';

    protected $fillable = [
        'user_id',
        'shift',
        'jam_shift_masuk',
        'jam_masuk',
        'mode_kerja',
        'tanggal',
        'status',
        'terlambat',
        'keterlambatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'terlambat' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}