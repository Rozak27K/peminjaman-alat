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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->foreignId('peminjaman_id')->unique()->constrained('peminjaman')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('petugas_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->date('tanggal_kembali');
            $table->enum('status', ['diproses', 'selesai'])->default('diproses');
            $table->decimal('denda', 12, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
