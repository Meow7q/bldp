<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTZbqkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_zbqk', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable()->comment('单位类型');
            $table->string('rzbj')->nullable()->comment('融资本金');
            $table->string('rzpjcb')->nullable()->comment('融资平均成本');
            $table->string('zycyzj')->nullable()->comment('占用产业资金');
            $table->string('hjtr')->nullable()->comment('合计投入');
            $table->string('lnfyzc')->nullable()->comment('历年费用支出');
            $table->string('zyzj')->nullable()->comment('自有资金');
            $table->string('cqgqtz')->nullable()->comment('长期股权投资');
            $table->string('gdzc')->nullable()->comment('固定资产');
            $table->string('xj')->nullable()->comment('小计');
            $table->string('zmjzc')->nullable()->comment('账面净资产');
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
        Schema::dropIfExists('t_zbqk');
    }
}
