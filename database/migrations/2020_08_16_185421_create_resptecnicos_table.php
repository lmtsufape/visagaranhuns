<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResptecnicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resptecnicos', function (Blueprint $table) {
            $table->id();
            $table->string('formacao');
            $table->string('especializacao')->nullable();
            $table->string('cpf')->unique();
            $table->string('telefone');
            $table->string('conselho');
            $table->string('num_conselho');

            $table->bigInteger("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users");

            // $table->bigInteger("area_id")->nullable();
            // $table->foreign("area_id")->references("id")->on("areas");

            // $table->bigInteger("empresa_id")->nullable();
            // $table->foreign("empresa_id")->references("id")->on("empresas");
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
        Schema::dropIfExists('resptecnicos');
    }
}
