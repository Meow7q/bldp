<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJyzlTable extends Migration
{
    /**
     * Run the migrations.
     *经营质量
     * @return void
     */
    public function up()
    {
        Schema::create('data_jyzl', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('year')->nullable()->comment('年');
            $table->string('month')->nullable()->comment('月');
            $table->string('ljtf')->nullable()->comment('累计投放');
            $table->string('yue')->nullable()->comment('余额');
            $table->string('zyzjzb')->nullable()->comment('自由资金占比');
            $table->string('sxsr')->nullable()->comment('实现收入');
            $table->string('sxlr')->nullable()->comment('实现利润');
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
        Schema::dropIfExists('jyzl');
    }
}
