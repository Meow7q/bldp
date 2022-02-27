<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTSrhzNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_srhz_new', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unit')->nullable()->comment('');
            $table->string('type')->nullable()->comment('');
            $table->string('hz')->nullable()->comment('');
            $table->string('fee_2022')->nullable()->comment('');
            $table->string('fee_2021')->nullable()->comment('');
            $table->string('fee_2020')->nullable()->comment('');
            $table->string('fee_2019')->nullable()->comment('');
            $table->string('fee_2018')->nullable()->comment('');
            $table->string('fee_2017')->nullable()->comment('');
            $table->string('fee_2016')->nullable()->comment('');
            $table->string('fee_2015')->nullable()->comment('');
            $table->string('fee_2014')->nullable()->comment('');
            $table->string('fee_2013')->nullable()->comment('');
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
        Schema::dropIfExists('t_srhz_new');
    }
}
