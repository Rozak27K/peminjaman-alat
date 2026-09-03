<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->foreignId('peminjam_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_pinjam')->nullable();
            $table->date('tanggal_rencana_kembali');
            $table->date('tanggal_disetujui')->nullable();
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'dipinjam', 'dikembalikan', 'dibatalkan'])->default('diajukan');
            $table->text('keperluan');
            $table->text('catatan_peminjam')->nullable();
            $table->text('catatan_petugas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
