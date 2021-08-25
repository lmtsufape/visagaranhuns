<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspecoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspecoes', function (Blueprint $table) {
            $table->id();
            $table->date('data');
            $table->string('status');
            $table->string('motivo');

            $table->string('nome_empresa')->nullable();
            $table->string('endereco')->nullable();
            $table->string('cpfcnpj')->nullable();

            $table->bigInteger("inspetor_id")->nullable();
            $table->foreign("inspetor_id")->references("id")->on("inspetor");

            $table->bigInteger("requerimento_id")->nullable();
            $table->foreign("requerimento_id")->references("id")->on("requerimentos");

            $table->bigInteger("empresas_id")->nullable();
            $table->foreign("empresas_id")->references("id")->on("empresas");

            $table->bigInteger("denuncias_id")->nullable();
            $table->foreign("denuncias_id")->references("id")->on("denuncias");

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
        Schema::dropIfExists('inspecoes');
    }
}
