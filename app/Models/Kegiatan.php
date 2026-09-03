<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kegiatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kegiatan',
        'tanggal_kegiatan',
        'lokasi',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kegiatan' => 'date',
        ];
    }

    public function kehadirans(): HasMany
    {
        return $this->hasMany(Kehadiran::class);
    }

    public function rwList()
    {
        return $this->hasMany(\App\Models\KegiatanRw::class);
    }

    public function getRwArrayAttribute(): array
    {
        return $this->rwList->pluck('rw')->toArray();
    }

    public function getRwLabelAttribute(): string
    {
        $rwArr = $this->rw_array;
        if (empty($rwArr)) {
            return 'Semua RW';
        }
        sort($rwArr);
        return implode(', ', array_map(fn($rw) => 'RW ' . $rw, $rwArr));
    }
}
