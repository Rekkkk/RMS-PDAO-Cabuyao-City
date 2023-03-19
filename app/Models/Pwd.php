<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pwd extends Model
{
    use HasFactory;

    protected $table = 'pwd';

    protected $primaryKey = 'pwd_id';

    protected $fillable = [
        'pwd_id',
        'pwd_number',
        'last_name',
        'first_name' ,
        'middle_name' ,
        'suffix',
        'age',
        'birthday',
        'religion',
        'ethnic_group',
        'sex',
        'civil_status',
        'blood_type',
        'disability_type' ,
        'disability_cause',
        'disability_name',
        'address',
        'barangay_id',
        'phone_number',
        'telephone_number',
        'email',
        'educational_attainment',
        'employment_status',
        'employment_category',
        'employment_type',
        'occupation' ,
        'organization_affliated',
        'organization_contact_person',
        'organization_office_address',
        'organization_telephone_number',
        'sss_number',
        'gsis_number',
        'pagibig_number',
        'philhealth_number',
        'father_last_name',   
        'father_first_name',
        'father_middle_name',     
        'mother_last_name',
        'mother_first_name',
        'mother_middle_name',
        'guardian_last_name',
        'guardian_first_name',
        'guardian_middle_name'
    ];
    
    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');
    }

    public function program()
    {
        return $this->belongsToMany(Program::class, 'program_claimants', 'program_id', 'pwd_id');
    }

    public function pwd_status()
    {
        return $this->hasOne(PwdStatus::class, 'pwd_id');
    }
    
    public function pictures()
    {
        return $this->hasMany(PwdDocs::class, 'pwd_id');
    }

    

}
