<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // ==========================
    // 🔹 Relasi
    // ==========================

    // Admin → bisa menambahkan banyak minuman
    public function minuman()
    {
        return $this->hasMany(Minuman::class, 'admin_id');
    }

    // Admin → bisa menambahkan banyak stock
    public function stocks()
    {
        return $this->hasMany(Stock::class, 'admin_id');
    }

    // Driver → membuat laporan penjualan
    public function laporanPenjualan()
    {
        return $this->hasMany(LaporanPenjualan::class, 'user_id');
    }

    // Produksi → membuat laporan produksi
    public function laporanProduksi()
    {
        return $this->hasMany(LaporanProduksi::class, 'produksi_id');
    }

    // Semua role bisa punya log aktivitas
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
    

    // ==========================
    // 🔹 Helper Method
    // ==========================

    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->join('');
    }

    // ==========================
    // 🔹 Role Checking
    // ==========================

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function isProduksi(): bool
    {
        return $this->role === 'produksi';
    }
}
