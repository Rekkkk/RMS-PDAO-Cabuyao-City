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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->boolean('is_new_account')->default('1');
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('temp_password')->nullable();
            $table->unsignedBigInteger('barangay_id')->nullable();
            $table->foreign('barangay_id')->references('barangay_id')->on('barangays');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->date('birthday')->nullable();
            $table->string('sex')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            //0 = staff, 1 = admin, 2 = OIC, 3 = system admin
            $table->tinyInteger('user_role');
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
        Schema::dropIfExists('users');
    }
};
