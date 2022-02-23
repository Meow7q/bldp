<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLnzcTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_lnzc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('year')->nullable()->comment('年份');
            $table->string('yxwzc')->nullable()->comment('营业外支出');
            $table->string('cwfy')->nullable()->comment('财务费用');
            $table->string('gz')->nullable()->comment('工资');
            $table->string('pgzxf')->nullable()->comment('评估咨询费');
            $table->string('zj')->nullable()->comment('折旧');
            $table->string('bgf')->nullable()->comment('办公费');
            $table->string('ywzdf')->nullable()->comment('业务招待费');
            $table->string('clf')->nullable()->comment('差旅费');
            $table->string('qtywcb')->nullable()->comment('其他业务成本');
            $table->string('kgqt')->nullable()->comment('控股其他');
            $table->string('ggsjf')->nullable()->comment('广告设计费');
            $table->string('sds')->nullable()->comment('控股其他');
            $table->string('ctqt')->nullable()->comment('城投其他');
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
        Schema::dropIfExists('t_lnzc');
    }
}
