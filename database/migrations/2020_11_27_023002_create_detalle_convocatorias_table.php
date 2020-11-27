<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleConvocatoriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_convocatorias', function (Blueprint $table) {
            $table->integer('idconvocatoria')->unsigned();
            $table->foreign('idconvocatoria')->references('id')->on('convocatorias')->onDelete('cascade');
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
        Schema::dropIfExists('detalle_convocatorias');
    }
}
