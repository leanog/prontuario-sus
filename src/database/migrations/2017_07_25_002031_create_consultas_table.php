<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->increments('id');

            $table->datetime('horario');

            $table->text('obs')->nullable();

            $table->enum('status', ['Primeira', 'Retorno', 'Nova']);

            $table->integer('medico_id')->unsigned();

            $table->foreign('medico_id')
              ->references('usuario_id')
              ->on('medicos')
            ->onDelete('cascade');

            $table->integer('paciente_id')->unsigned();

            $table->foreign('paciente_id')
              ->references('id')
              ->on('pacientes')
            ->onDelete('cascade');
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
        Schema::dropIfExists('consultas');
    }
}
