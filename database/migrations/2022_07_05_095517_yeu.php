<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('role');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('verifikasi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_admin');
            $table->unsignedBigInteger('id_relawan');
            $table->integer('status_verifikasi');

            $table->foreign('id_admin') -> references('id_user') -> on('users');
            $table->foreign('id_relawan')-> references('id_user') -> on('users');
            $table->primary(['id_admin', 'id_relawan']);
            $table->timestamps();
        });

        Schema::create('data_diri', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('id_user');
            $table->enum('jenis_kelamin',['Laki-laki', 'Perempuan']);
            $table->date('tanggal_lahir');
            $table->string('no_telepon',20);
            $table->text('alamat_ktp');
            $table->text('alamat_domisili');
            $table->enum('gol_darah',['A', 'B', 'AB', 'O']);
            $table->enum('kondisi_disabilitas',['Tidak Ada','Disabilitas Fisik','Disabilitas Intelektual','Disabilitas Mental','Disabilitas Sensorik-Netra','Disabilitas Sensorik-Rungu','Disabilitas Sensorik-Wicara']);
            $table->string('penyakit',255);
            $table->enum('status_vaksin', ['Belum','Dosis 1','Dosis 2','Booster']);
            $table->boolean('pernah_bekerja_sama');
            $table->boolean('bpjs_kesehatan');
            $table->boolean('bpjs_ketenagakerjaan');
            $table->string('instansi', 255);
            $table->string('file_pelatihan', 255);
            $table->foreign('id_user') -> references('id_user') -> on('users');
            $table->primary('id_user');
            $table->timestamps();
        });

        Schema::create('keahlian', function (Blueprint $table) {
            $table->id('id_keahlian');
            $table->string('jenis_keahlian',25);
            $table->timestamps();
        });

        Schema::create('relawan_keahlian', function (Blueprint $table) {
            $table->unsignedBigInteger('id_relawan');
            $table->unsignedBigInteger('id_keahlian');
            $table->foreign('id_relawan')->references('id_user')->on('users');
            $table->foreign('id_keahlian')->references('id_keahlian')->on('keahlian');
            $table->timestamps();
        });

        Schema::create('pengalaman', function (Blueprint $table) {
            $table->id('id_pengalaman');
            $table->string('nama_instansi',50);
            $table->string('durasi_waktu',20);
            $table->timestamps();
        });

        Schema::create('relawan_pengalaman', function (Blueprint $table) {
            $table->unsignedBigInteger('id_relawan');
            $table->unsignedBigInteger('id_pengalaman');
            $table->foreign('id_relawan')->references('id_user')->on('users');
            $table->foreign('id_pengalaman')->references('id_pengalaman')->on('pengalaman');
            $table->timestamps();
        });

        Schema::create('project', function (Blueprint $table) {
            $table->id('id_project');
            $table->string('nama_project',50);
            $table->string('foto',255);
            $table->timestamps();
        });

        Schema::create('relawan_project', function (Blueprint $table) {
            $table->unsignedBigInteger('id_relawan');
            $table->unsignedBigInteger('id_project');
            $table->foreign('id_relawan')->references('id_user')->on('users');
            $table->foreign('id_project')->references('id_project')->on('project');
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
        if (Schema::hasTable('verifikasi')) {
            Schema::drop('verifikasi');
        }
        if (Schema::hasTable('data_diri')) {
            Schema::drop('data_diri');
        }
        if (Schema::hasTable('relawan_keahlian')) {
            Schema::drop('relawan_keahlian');
        }
        if (Schema::hasTable('relawan_pengalaman')) {
            Schema::drop('relawan_pengalaman');
        }
        if (Schema::hasTable('relawan_project')) {
            Schema::drop('relawan_project');
        }
        if (Schema::hasTable('keahlian')) {
            Schema::drop('keahlian');
        }
        if (Schema::hasTable('pengalaman')) {
            Schema::drop('pengalaman');
        }
        if (Schema::hasTable('project')) {
            Schema::drop('project');
        }
        if (Schema::hasTable('users')) {
            Schema::drop('users');
        }
    }
};
