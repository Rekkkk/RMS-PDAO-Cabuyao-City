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
        Schema::create('program_status', function (Blueprint $table) {
            $table->unsignedBigInteger('program_id');
            $table->foreign('program_id')->references('program_id')->on('programs');
            $table->boolean('encoding_status')->default(0);
            $table->boolean('is_done')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_status');

    }
};
