<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePCheckTableListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_check_table_list', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('table_name')->nullable()->comment('表名');
            $table->string('tmp_path')->nullable()->comment('模版地址');
            $table->string('file_path')->nullable()->comment('最新文件地址');
            $table->string('file_name')->nullable()->comment('文件名称');
            $table->string('month')->nullable()->comment('定稿月份');
            $table->tinyInteger('status')->default(0)->comment('状态');
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
        Schema::dropIfExists('p_check_table_list');
    }
}
