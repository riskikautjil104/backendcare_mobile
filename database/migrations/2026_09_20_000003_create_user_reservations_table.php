<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('kode_booking', 50)->unique()->index();
            $table->string('id_poli', 20);
            $table->string('nama_poli', 100);
            $table->string('id_dokter', 20);
            $table->string('nama_dokter', 150);
            $table->date('tanggal_praktik');
            $table->string('jam_slot', 30);
            $table->string('estimasi_kedatangan', 30)->nullable();
            $table->enum('tipe_pasien', ['BARU', 'LAMA'])->default('LAMA');
            $table->enum('jenis_penjamin', ['UMUM', 'BPJS'])->default('UMUM');
            $table->decimal('total_biaya', 10, 2)->default(0);
            $table->string('status_pembayaran', 40)->default('MENUNGGU_KASIR');
            $table->string('status_antrean', 30)->default('RESERVED');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reservations');
    }
};
