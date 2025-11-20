<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogAktivitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal');
            $table->time('waktu_awal');
            $table->time('waktu_akhir');
            $table->text('aktivitas');
            $table->unsignedBigInteger('departemen_id')->nullable();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->enum('status', ['menunggu', 'tervalidasi', 'ditolak'])->default('menunggu');
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->text('catatan_validasi')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('validated_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['user_id', 'tanggal']);
            $table->index('status');
            $table->index('departemen_id');
            $table->index('unit_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_aktivitas');
    }
}
