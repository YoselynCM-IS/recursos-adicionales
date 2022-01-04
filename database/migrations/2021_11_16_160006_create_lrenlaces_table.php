<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLrenlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lrenlaces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lr_id');
            $table->foreign('lr_id')->references('id')->on('libro_recurso');
            $table->string('nombre');
            $table->string('link');
            $table->enum('tipo', ['sitio', 'track']);
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
        Schema::dropIfExists('lrenlaces');
    }
}
