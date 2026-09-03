<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KegiatanRw extends Model
{
    protected $table = 'kegiatan_rw';

    protected $fillable = [
        'kegiatan_id',
        'rw',
    ];

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }
}
