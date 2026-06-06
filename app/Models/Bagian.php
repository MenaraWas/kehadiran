<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bagian extends Model
{
    use HasFactory;

    protected $fillable = ['nama_bagian'];

    public function anggotas(): HasMany
    {
        return $this->hasMany(Anggota::class);
    }

    public function kegiatans(): HasMany
    {
        return $this->hasMany(Kegiatan::class);
    }
}
