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
        Schema::create('walk_in', function (Blueprint $table) {
            $table->unsignedBigInteger('pwd_id');
            $table->foreign('pwd_id')->references('pwd_id')->on('pwd');
            $table->unsignedBigInteger('barangay_id');
            $table->foreign('barangay_id')->references('barangay_id')->on('barangays');
            $table->string('transaction');
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
        Schema::dropIfExists('walk_in');

    }
};
