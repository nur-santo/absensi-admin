<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hari_kerja', function (Blueprint $table) {
            $table->id();

            // Tanggal + shift yang di-generate (unik per kombinasi)
            $table->date('tanggal');
            $table->string('shift');           // nama_shift string, sama seperti kehadiran

            $table->foreignId('generated_by')  // admin yang klik "Mulai Shift"
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['tanggal', 'shift']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hari_kerja');
    }
};