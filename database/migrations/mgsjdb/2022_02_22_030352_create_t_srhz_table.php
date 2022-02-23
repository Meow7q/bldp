<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTSrhzTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_srhz', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('year')->nullable()->comment('年费');
            $table->string('dbfdw')->nullable()->comment('担保费对外');
            $table->string('lx')->nullable()->comment('利息');
            $table->string('glf')->nullable()->comment('管理费');
            $table->string('fh')->nullable()->comment('分红');
            $table->string('tzly')->nullable()->comment('投资利益');
            $table->string('ssjl')->nullable()->comment('税收奖励');
            $table->string('gpdx')->nullable()->comment('高抛低吸');
            $table->string('zj')->nullable()->comment('租金');
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
        Schema::dropIfExists('t_srhz');
    }
}
