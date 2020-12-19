<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleResolucionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_resoluciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('iddetalleacta')->unsigned();
            $table->foreign('iddetalleacta')->references('id')->on('detalle_actas')->onDelete('cascade');
            $table->text('descripcion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_resoluciones');
    }
}
