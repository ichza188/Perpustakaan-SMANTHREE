<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_create_buku_table.php
public function up()
{
    Schema::create('buku', function (Blueprint $table) {
        $table->id();
        $table->string('kode_buku', 20)->unique();
        $table->string('judul');
        $table->string('pengarang');
        $table->integer('stok')->default(1);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};
