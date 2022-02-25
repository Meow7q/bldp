<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTDwtzqkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_dwtzqk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unit')->nullable()->comment('单位');
            $table->string('gqbj')->nullable()->comment('股权本金');
            $table->string('ysgx')->nullable()->comment('应收股息');
            $table->string('zq')->nullable()->comment('债券');
            $table->string('yszx')->nullable()->comment('应收债息');
            $table->string('lcsy')->nullable()->comment('留存收益');
            $table->string('hj')->nullable()->comment('合计');
            $table->string('gxzj')->nullable()->comment('贡献资金');
            $table->string('yfhhysj')->nullable()->comment('已分红或已上交');
            $table->string('tzhbl')->nullable()->comment('投资回报率(年化)');
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
        Schema::dropIfExists('t_dwtzqk');
    }
}
