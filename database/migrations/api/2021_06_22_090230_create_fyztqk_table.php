<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFyztqkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_fyztqk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('year')->nullable()->comment('年');
            $table->string('month')->nullable()->comment('月');
            $table->string('area_id')->nullable()->comment('所在城市id');
            $table->string('lx75_1')->nullable()->comment('利信7号5期-1');
            $table->string('lx75_2')->nullable()->comment('利信7号5期-2');
            $table->string('lx7_6')->nullable()->comment('利信7号6期');
            $table->string('lx7_7')->nullable()->comment('利信7号7期');
            $table->string('pjpgj')->nullable()->comment('平均评估价');
            $table->string('hjs')->nullable()->comment('户均数');
            $table->string('hkbj')->nullable()->comment('回款本金');
            $table->string('tfl')->nullable()->comment('投放量');
            $table->string('tfbs')->nullable()->comment('投放笔数');
            $table->string('bsny')->nullable()->comment('比上年/月');
            $table->string('zhdyl')->nullable()->comment('总和抵押率');
            $table->string('yybl')->nullable()->comment('一押比例');
            $table->string('eybl')->nullable()->comment('二押比例');
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
        Schema::dropIfExists('fyztqk');
    }
}
