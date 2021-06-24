<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBldpFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_bldp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('year')->nullable()->comment('年');
            $table->string('month')->nullable()->comment('月');
            $table->string('file_url')->nullable()->comment('文件地址');
            $table->string('title')->nullable()->comment('标题');
            $table->string('status')->nullable()->comment('审核状态');
            $table->string('import_status')->nullable()->comment('导入状态');
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
        Schema::dropIfExists('file_bldp');
    }
}
