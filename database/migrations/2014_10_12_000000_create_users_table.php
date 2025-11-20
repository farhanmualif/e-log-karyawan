<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 150)->nullable();
            $table->string('username', 50)->unique();
            $table->string('email', 150)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            // $table->boolean('is_default_password')->default(true);
            $table->boolean('password_changed')->default(false);
            $table->string('role', 20)->default('karyawan');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
