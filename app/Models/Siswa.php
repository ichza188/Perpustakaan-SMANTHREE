<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $fillable = [
        'user_id',
        'nama',
        'tanggal_lahir',
        'angkatan',
        'nisn',
        'kelas',
        'status_bebas_perpus',
        'tanggal_pengajuan_bebas',
        'tanggal_persetujuan_bebas',
        'catatan_bebas_perpus',
        'admin_bebas_perpus_id',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_pengajuan_bebas' => 'datetime',
        'tanggal_persetujuan_bebas' => 'datetime',
        'tanggal_kembali' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
    // app/Models/Siswa.php
    public function isBebasPerpus()
    {
        return $this->status_bebas_perpus === 'disetujui';
    }

    public function scopeKelasXII($query)
    {
        return $query->where('kelas', 'like', 'XII%');
    }
}
