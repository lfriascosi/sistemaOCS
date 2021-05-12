<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleActasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_actas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idacta')->unsigned();
            $table->foreign('idacta')->references('id')->on('actas')->onDelete('cascade');
            $table->integer('idordendia')->unsigned();
            $table->foreign('idordendia')->references('id')->on('orden_dias')->onDelete('cascade');
            $table->string('nombrealternativo',100)->nullable();
            $table->string('descripcion',1000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_actas');
    }
}
