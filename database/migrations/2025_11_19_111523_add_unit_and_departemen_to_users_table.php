<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitAndDepartemenToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('unit_id')->nullable()->after('role');
            $table->unsignedBigInteger('departemen_id')->nullable()->after('unit_id');

            $table->foreign('unit_id')
                ->references('id')
                ->on('tb_unit')
                ->onDelete('set null');

            $table->foreign('departemen_id')
                ->references('id')
                ->on('tb_departemen')
                ->onDelete('set null');

            $table->index('unit_id');
            $table->index('departemen_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['departemen_id']);
            $table->dropIndex(['unit_id']);
            $table->dropIndex(['departemen_id']);
            $table->dropColumn(['unit_id', 'departemen_id']);
        });
    }
}
