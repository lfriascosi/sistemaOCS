<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirmaActasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firma_actas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idacta')->unsigned();
            $table->foreign('idacta')->references('id')->on('actas')->onDelete('cascade');
            $table->string('iduser',11);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('firma_actas');
    }
}
