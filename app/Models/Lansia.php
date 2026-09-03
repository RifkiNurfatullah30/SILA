<?php

namespace App\Models;

use App\Helpers\KampungHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lansia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nik',
        'rw',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'nomor_telepon',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function kehadirans(): HasMany
    {
        return $this->hasMany(Kehadiran::class);
    }

    public function healthRecords(): HasMany
    {
        return $this->hasMany(HealthRecord::class);
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmergencyContact::class);
    }

    public function primaryContact()
    {
        return $this->hasOne(EmergencyContact::class)->where('is_primary', true);
    }

    public function latestHealthRecord()
    {
        return $this->hasOne(HealthRecord::class)->latestOfMany('tanggal_pemeriksaan');
    }

    public function getUsiaAttribute(): ?int
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }

    public function getKampungAttribute(): ?string
    {
        return KampungHelper::getKampungByRw($this->rw);
    }

    public function getPersentaseKeaktifanAttribute(): float
    {
        // Hitung total kegiatan yang ditujukan untuk RW lansia ini ATAU untuk semua RW
        $totalKegiatan = Kegiatan::where(function ($q) {
            $q->whereDoesntHave('rwList') // Semua RW
              ->orWhereHas('rwList', function ($sq) {
                  $sq->where('rw', $this->rw); // Khusus RW ini
              });
        })->count();
        
        if ($totalKegiatan === 0) return 0;

        $totalHadir = $this->kehadirans()->where('status', 'Hadir')->count();
        return round(($totalHadir / $totalKegiatan) * 100, 2);
    }

    public function getKategoriKeaktifanAttribute(): string
    {
        $persentase = $this->persentase_keaktifan;
        return match (true) {
            $persentase >= 80 => 'Sangat Aktif',
            $persentase >= 60 => 'Aktif',
            $persentase >= 40 => 'Cukup Aktif',
            default => 'Kurang Aktif',
        };
    }

    public function getBadgeKeaktifanAttribute(): string
    {
        return match ($this->kategori_keaktifan) {
            'Sangat Aktif' => 'success',
            'Aktif' => 'primary',
            'Cukup Aktif' => 'warning',
            default => 'danger',
        };
    }
}
