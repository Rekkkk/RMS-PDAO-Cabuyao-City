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
        Schema::create('appointment_docs', function (Blueprint $table) {
            $table->bigIncrements('img_id');
            $table->unsignedBigInteger('appointment_id');
            $table->foreign('appointment_id')->references('appointment_id')->on('appointment');
            $table->string('img_name');
            $table->string('docs_type');
            $table->string('appointment')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('appointment_docs');
    }
};
