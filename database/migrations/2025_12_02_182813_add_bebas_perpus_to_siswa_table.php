<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->enum('status_bebas_perpus', ['belum_ajukan', 'diajukan', 'disetujui', 'ditolak'])
                  ->default('belum_ajukan');
            $table->date('tanggal_pengajuan_bebas')->nullable();
            $table->date('tanggal_persetujuan_bebas')->nullable();
            $table->text('catatan_bebas_perpus')->nullable();
            $table->foreignId('admin_bebas_perpus_id')->nullable()->constrained('users');
        });
    }

    public function down()
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn([
                'status_bebas_perpus', 'tanggal_pengajuan_bebas',
                'tanggal_persetujuan_bebas', 'catatan_bebas_perpus', 'admin_bebas_perpus_id'
            ]);
        });
    }
};
