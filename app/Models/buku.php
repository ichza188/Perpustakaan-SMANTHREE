<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class buku extends Model
{
    use HasFactory;
    protected $table = 'buku';

    protected $fillable = [
        'kode_buku', 'judul', 'pengarang', 'stok'
    ];
    public function peminjaman()
{
    return $this->hasMany(Peminjaman::class);
}

public function stokTersedia()
{
    return $this->stok - $this->peminjaman()
        ->whereIn('status', ['dipinjam', 'pengajuan_kembali'])
        ->count();
}
}
