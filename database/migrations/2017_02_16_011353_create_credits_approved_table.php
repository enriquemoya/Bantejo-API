<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditsApprovedTable extends Migration
{
    
    public function up()
    {
        Schema::create('credits_approved', function(Blueprint $table) {
            $table->increments('id');
            // Schema declaration
            // Schema declaration
            $table->integer('application')->unsigned()->nullable();
            $table->integer('type')->unsigned()->nullable(); //Tipo segun los definidos en la tabla de tipos
            $table->double('amount'); //monto inicial del credito
            $table->date('start_date');  // Fecha de inicio del Credito
            $table->integer('term'); // No. de meses del credito
            $table->double('interest'); // % de interes anual convertido a decimal
            $table->double('iva')->default(16); // iva actual
            $table->double('interest_arrear')->default(0); // Intereses moratorio
            $table->integer('grace_days')->default(0); // dias de periodo de gracia
            $table->string('currency')->default('MXN'); // tipo de cambio MXN/USD
            $table->string('todo'); // Para que se usara el capital
            $table->string('status'); // Status del credito
            $table->integer('extends')->unsigned()->nullable(); //Credito indica si es modificado y siempre hace referencia al creidito inicial
            // Constraints declaration
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('type')->references('id')->on('credits_available')->onDelete('set null');
            $table->foreign('application')->references('id')->on('applications')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::drop('credits_approved');
    }
}
