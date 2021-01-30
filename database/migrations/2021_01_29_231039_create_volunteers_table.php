<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVolunteersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->string('NIK');
            $table->string('namaLengkap');
            $table->string('namaPanggilan');
            $table->string('tempatLahir');
            $table->date('tanggalLahir');
            $table->integer('umur');
            $table->string('status')->nullable();
            $table->integer('jumlahSaudara')->nullable();
            $table->string('jenisKelamin')->nullable();
            $table->integer('anakKe')->nullable();
            $table->string('alamat')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('noHp');
            $table->string('whatsapp')->nullable();
            $table->string('email');
            $table->string('tempatMengaji')->nullable();
            $table->string('motivasi')->nullable();
            $table->string('harapan')->nullable();
            $table->string('komitmen')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('volunteers');
    }
}
