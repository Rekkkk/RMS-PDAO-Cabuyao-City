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
        Schema::create('appointment_new_applicant', function (Blueprint $table) {
            $table->unsignedBigInteger('appointment_id');
            $table->foreign('appointment_id')->references('appointment_id')->on('appointment')->onDelete('cascade');
            $table->string('last_name'); 
            $table->string('first_name');
            $table->string('middle_name')->nullable();;
            $table->string('suffix')->nullable();
            $table->integer('age');
            $table->date('birthday');
            $table->string('religion');
            $table->string('ethnic_group')->nullable();
            $table->string('sex');
            $table->string('civil_status');
            $table->string('blood_type');
            $table->string('disability_type');
            $table->string('disability_cause');
            $table->string('disability_name');
            $table->string('address');
            $table->unsignedBigInteger('barangay_id');
            $table->foreign('barangay_id')->references('barangay_id')->on('barangays');
            $table->string('telephone_number')->nullable();
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->string('educational_attainment');
            $table->string('employment_status')->nullable();
            $table->string('employment_category')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('occupation')->nullable();
            $table->string('organization_affliated')->nullable();
            $table->string('organization_contact_person')->nullable();
            $table->string('organization_office_address')->nullable();
            $table->string('organization_telephone_number')->nullable();
            $table->string('sss_number')->nullable();
            $table->string('gsis_number')->nullable();
            $table->string('pagibig_number')->nullable();
            $table->string('philhealth_number')->nullable();
            $table->string('father_last_name');
            $table->string('father_first_name');
            $table->string('father_middle_name')->nullable();
            $table->string('mother_last_name');
            $table->string('mother_first_name');
            $table->string('mother_middle_name')->nullable();
            $table->string('guardian_last_name')->nullable();
            $table->string('guardian_first_name')->nullable();
            $table->string('guardian_middle_name')->nullable();
        });
    }

    /**
     * 
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointment_new_applicant');
    }
};
