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
        Schema::create('appointment', function (Blueprint $table) {
            $table->bigIncrements('appointment_id');
            $table->string('transaction');
            $table->date('appointment_date');
            $table->unsignedBigInteger('barangay_id');
            $table->foreign('barangay_id')->references('barangay_id')->on('barangays');
            $table->boolean('is_reschedule')->default('0');
            $table->string('appointment_status')->default("Pending");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment');
    }
};
