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
        Schema::create('program_claimants', function (Blueprint $table) {
            $table->bigIncrements('reference_no');
            $table->unsignedBigInteger('program_id');
            $table->foreign('program_id')->references('program_id')->on('programs');
            $table->unsignedBigInteger('pwd_id');
            $table->foreign('pwd_id')->references('pwd_id')->on('pwd')->onDelete('cascade');
            $table->boolean('is_unclaim')->default('0');
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
        Schema::drop('program_claimants');
    }
};
