<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTKjbbNewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_kjbb_new', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('project')->nullable()->comment('科目');
            $table->string('kg_2022')->nullable()->comment('控股2022');
            $table->string('kg_2021')->nullable()->comment('控股2021');
            $table->string('kg_tb')->nullable()->comment('控股同比');
            $table->string('ct_2022')->nullable()->comment('城投2022');
            $table->string('ct_2021')->nullable()->comment('城投2021');
            $table->string('ct_tb')->nullable()->comment('城投同比');
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
        Schema::dropIfExists('t_kjbb_new');
    }
}
