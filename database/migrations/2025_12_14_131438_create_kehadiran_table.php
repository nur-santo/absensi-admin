<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kehadiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained();

            $table->string('shift');
            $table->time('jam_shift_masuk');
            $table->time('jam_masuk')->nullable();
            $table->string('mode_kerja')->nullable();

            $table->date('tanggal');

            $table->enum('status', ['ALPA', 'HADIR', 'IZIN', 'CUTI', 'SAKIT'])
                ->default('ALPA');

            $table->boolean('terlambat')->default(false);
            $table->integer('menit_telat')->nullable();
            $table->string('keterlambatan')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadiran');
    }
};
