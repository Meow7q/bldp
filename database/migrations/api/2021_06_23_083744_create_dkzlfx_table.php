<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDkzlfxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_dkzlfx', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('year')->nullable()->comment('年');
            $table->string('month')->nullable()->comment('月');
            $table->string('area_id')->nullable()->comment('地域id');
            $table->string('type1')->nullable()->comment('一级分类id');
            $table->string('type1_name')->nullable()->comment('一级分类名称');
            $table->string('type2')->nullable()->comment('二级分类');
            $table->string('type2_name')->nullable()->comment('二级分类名称');
            $table->string('tfbs')->nullable()->comment('投放笔数');
            $table->string('bszb')->nullable()->comment('笔数占比');
            $table->string('fkje')->nullable()->comment('放款金额');
            $table->string('jezb')->nullable()->comment('金额占比');
            $table->string('eybl')->nullable()->comment('二押比例');
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
        Schema::dropIfExists('data_dkzlfx');
    }
}
