<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenDiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_dias', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idconvocatoria')->unsigned();
            $table->foreign('idconvocatoria')->references('id')->on('convocatorias')->onDelete('cascade');
            $table->string('nombre',11);
            $table->boolean('condicion')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orden_dias');
    }
}
