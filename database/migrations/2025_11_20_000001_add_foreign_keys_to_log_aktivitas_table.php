<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToLogAktivitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_aktivitas', function (Blueprint $table) {
            // Pastikan tabel tb_departemen dan tb_unit sudah ada sebelum menambahkan foreign key
            if (Schema::hasTable('tb_departemen')) {
                $table->foreign('departemen_id')
                    ->references('id')
                    ->on('tb_departemen')
                    ->onDelete('set null');
            }
            
            if (Schema::hasTable('tb_unit')) {
                $table->foreign('unit_id')
                    ->references('id')
                    ->on('tb_unit')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_aktivitas', function (Blueprint $table) {
            $table->dropForeign(['departemen_id']);
            $table->dropForeign(['unit_id']);
        });
    }
}

