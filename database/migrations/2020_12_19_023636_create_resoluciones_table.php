<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResolucionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resoluciones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idordendia')->unsigned();
            $table->foreign('idordendia')->references('id')->on('orden_dias')->onDelete('cascade');
            $table->integer('idacta')->unsigned();
            $table->foreign('idacta')->references('id')->on('actas')->onDelete('cascade');
            $table->string('codigo',50);
            $table->string('nombrealternativo',200)->nullable();
            $table->text('descripcion');
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
        Schema::dropIfExists('resoluciones');
    }
}
