<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_pengajuan_kembali')->nullable();
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['pending', 'dipinjam', 'pengajuan_kembali', 'dikembalikan', 'ditolak'])
                  ->default('pending');
            $table->text('catatan_siswa')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
