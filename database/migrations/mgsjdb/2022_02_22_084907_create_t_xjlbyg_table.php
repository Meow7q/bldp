<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTXjlbygTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_xjlbyg', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('project')->nullable()->comment('名称');
            $table->string('fee_1')->nullable()->comment('');
            $table->string('fee_2')->nullable()->comment('');
            $table->string('fee_3')->nullable()->comment('');
            $table->string('fee_4')->nullable()->comment('');
            $table->string('fee_5')->nullable()->comment('');
            $table->string('fee_6')->nullable()->comment('');
            $table->string('fee_7')->nullable()->comment('');
            $table->string('fee_8')->nullable()->comment('');
            $table->string('fee_9')->nullable()->comment('');
            $table->string('fee_10')->nullable()->comment('');
            $table->string('fee_11')->nullable()->comment('');
            $table->string('fee_12')->nullable()->comment('');
            $table->string('hj')->nullable()->comment('合计');
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
        Schema::dropIfExists('t_xjlbyg');
    }
}
