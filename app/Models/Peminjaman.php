<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $guarded = [];
   // ATAU GUNAKAN $casts (Laravel 8+ lebih disarankan)
   protected $casts = [
    'tanggal_pinjam' => 'datetime',
    'tanggal_pengajuan_kembali' => 'datetime',
    'tanggal_kembali' => 'datetime',
];

public function siswa()   { return $this->belongsTo(Siswa::class); }
public function buku()    { return $this->belongsTo(Buku::class); }
public function admin()   { return $this->belongsTo(User::class, 'admin_id'); }
}
