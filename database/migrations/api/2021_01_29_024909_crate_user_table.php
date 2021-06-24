<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('staffcode', 255)->comment('openid');
            $table->string('avatar', 255)->nullable()->comment('头像');
            $table->string('nickname', 255)->nullable()->comment('昵称');
            $table->tinyInteger('gender')->nullable()->comment('性别');
            $table->string('country', 255)->nullable()->comment('国家');
            $table->string('province', 255)->nullable()->comment('省份');
            $table->string('city', 255)->nullable()->comment('城市');
            $table->tinyInteger('status')->nullable()->comment('状态');
            $table->tinyInteger('permission')->nullable()->comment('权限');
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
        Schema::dropIfExists('users');
    }
}
