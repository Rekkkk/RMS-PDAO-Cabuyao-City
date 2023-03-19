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
        Schema::create('appointment_remarks', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id');
            $table->foreign('appointment_id')->references('appointment_id')->on('appointment');
            $table->longText('remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_remarks');
    }
};
