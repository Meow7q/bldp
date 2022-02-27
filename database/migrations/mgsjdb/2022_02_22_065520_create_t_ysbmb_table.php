<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTYsbmbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_ysbmb', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable()->comment('类别');
            $table->string('fee_d')->nullable()->comment('全年累计执行数');
            $table->string('fee_e')->nullable()->comment('1-X月累计预算数');
            $table->string('de')->nullable()->comment('d/e%');
            $table->string('df')->nullable()->comment('d/f%');
            $table->string('fee_f')->nullable()->comment('全年预算数');
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
        Schema::dropIfExists('t_ysbmb');
    }
}
