<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTFhmxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_fhmx', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('unit')->nullable()->comment('单位');
            $table->string('company')->nullable()->comment('公司');
            $table->string('year')->nullable()->comment('年份');
            $table->string('fee')->nullable()->comment('费用');
            $table->string('remark')->nullable()->comment('费用');
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
        Schema::dropIfExists('t_fhmx');
    }
}
