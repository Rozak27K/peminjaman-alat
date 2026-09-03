<?php

namespace App\Models;

use Database\Factories\AlatFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alat extends Model
{
    /** @use HasFactory<AlatFactory> */
    use HasFactory;

    protected $table = 'alat';

    protected $fillable = [
        'kategori_alat_id',
        'kode',
        'nama',
        'merk',
        'model',
        'stok_total',
        'stok_tersedia',
        'kondisi',
        'status',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'stok_total' => 'integer',
            'stok_tersedia' => 'integer',
        ];
    }

    public function kategoriAlat(): BelongsTo
    {
        return $this->belongsTo(KategoriAlat::class);
    }

    public function detailPeminjaman(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }
}
