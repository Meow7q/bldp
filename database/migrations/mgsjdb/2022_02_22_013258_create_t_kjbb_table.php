<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTKjbbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_kjbb', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('year')->nullable()->comment('年份');
            $table->string('type')->nullable()->comment('单位类型');
            $table->string('yysr')->nullable()->comment('营业收入');
            $table->string('tzss')->nullable()->comment('投资收益');
            $table->string('qtsy')->nullable()->comment('其他收益');
            $table->string('yywsr')->nullable()->comment('营业外收入');
            $table->string('yyzc')->nullable()->comment('营业支出');
            $table->string('glfy')->nullable()->comment('管理费用');
            $table->string('cwfy')->nullable()->comment('财务费用');
            $table->string('sjjfj')->nullable()->comment('税金及附加');
            $table->string('yywjqtzc')->nullable()->comment('营业外及其他支出');
            $table->string('sds')->nullable()->comment('所得税');
            $table->string('dnjlr')->nullable()->comment('当年净利润');
            $table->string('qmwfplr')->nullable()->comment('期末未分配利润');
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
        Schema::dropIfExists('t_kjbb');
    }
}
