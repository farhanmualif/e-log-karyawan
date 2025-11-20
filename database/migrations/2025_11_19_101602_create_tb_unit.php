<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_unit', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('departemen_id');
            $table->string('nama', 150);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('departemen_id')
                ->references('id')
                ->on('tb_departemen')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_unit');
    }
}
