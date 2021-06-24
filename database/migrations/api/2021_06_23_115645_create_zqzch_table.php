<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZqzchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_zqzch', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('zgm')->nullable()->comment('总规模');
            $table->string('xmmc')->nullable()->comment('项目名称');
            $table->string('hxqy')->nullable()->comment('核心企业');
            $table->string('glgm')->nullable()->comment('管理规模');
            $table->string('ll')->nullable()->comment('利率');
            $table->string('fxbs')->nullable()->comment('发行笔数');
            $table->string('jhglr')->nullable()->comment('计划管理人');
            $table->string('jycs')->nullable()->comment('交易场所');
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
        Schema::dropIfExists('data_zqzch');
    }
}
