<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTFhmx1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_fhmx_new', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unit')->nullable()->comment('主体单位');
            $table->string('name')->nullable()->comment('客商');
            $table->string('hj')->nullable()->comment('合计');
            $table->string('remark')->nullable()->comment('备注');
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
        Schema::dropIfExists('t_fhmx_new');
    }
}
