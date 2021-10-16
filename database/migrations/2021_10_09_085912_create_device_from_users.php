<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceFromUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_from_users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('merk');
            $table->string('deskripsi');
            $table->string('mac_address');
            $table->string('ip_Address');
            $table->datetime('tgl_register');
            $table->string('umur_registrasi')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_from_users');
    }
}
