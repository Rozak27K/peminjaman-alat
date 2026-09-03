<?php

namespace App\Models;

use Database\Factories\KategoriAlatFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriAlat extends Model
{
    /** @use HasFactory<KategoriAlatFactory> */
    use HasFactory;

    protected $table = 'kategori_alat';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
        ];
    }

    public function alat(): HasMany
    {
        return $this->hasMany(Alat::class);
    }
}
