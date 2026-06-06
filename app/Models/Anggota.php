<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Anggota extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'foto',
        'kategori_pegawai',
        'jabatan',
        'nrp_nip',
    ];

    public function kehadirans(): HasMany
    {
        return $this->hasMany(Kehadiran::class);
    }
}
