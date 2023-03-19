<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_memo', function (Blueprint $table) {
            $table->bigIncrements('memo_id');
            $table->unsignedBigInteger('program_id');
            $table->foreign('program_id')->references('program_id')->on('programs');
            $table->string('img_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_memo');

    }
};
