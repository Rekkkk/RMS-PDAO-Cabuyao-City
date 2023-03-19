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
        Schema::create('pwd_docs', function (Blueprint $table) {
            $table->bigIncrements('img_id');
            $table->unsignedBigInteger('pwd_id');
            $table->foreign('pwd_id')->references('pwd_id')->on('pwd');
            $table->string('img_name');
            $table->string('docs_type');
            $table->string('appointment')->nullable();
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
        Schema::drop('pwd_docs');
    }
};
