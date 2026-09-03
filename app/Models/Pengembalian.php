<?php

namespace App\Models;

use Database\Factories\PengembalianFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengembalian extends Model
{
    /** @use HasFactory<PengembalianFactory> */
    use HasFactory;

    protected $table = 'pengembalian';

    protected $fillable = [
        'kode',
        'peminjaman_id',
        'petugas_id',
        'tanggal_kembali',
        'status',
        'denda',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kembali' => 'date',
            'denda' => 'decimal:2',
        ];
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
