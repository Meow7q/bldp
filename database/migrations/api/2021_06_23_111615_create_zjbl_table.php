<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZjblTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_zjbl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('year')->nullable()->comment('年');
            $table->string('month')->nullable()->comment('月');
            $table->string('ypjlr')->nullable()->comment('月平均利润');
            $table->string('zpjlr')->nullable()->comment('总平均利润');
            $table->string('dysr')->nullable()->comment('当月收入');
            $table->string('zsr')->nullable()->comment('总收入');
            $table->string('tfl')->nullable()->comment('投放量');

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
        Schema::dropIfExists('data_zjbl');
    }
}
