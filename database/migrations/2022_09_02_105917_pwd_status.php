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
        Schema::create('pwd_status', function (Blueprint $table) {
            $table->unsignedBigInteger('pwd_id');
            $table->foreign('pwd_id')->references('pwd_id')->on('pwd');
            $table->date('id_expiration');
            $table->boolean('cancelled')->default(0);
            $table->date('cancelled_date')->nullable();
            
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pwd_status');
    }
};
