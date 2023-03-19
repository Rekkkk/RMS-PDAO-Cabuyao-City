<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;
    
    protected $table = 'barangays';

    public $timestamps = false;

    protected $primaryKey = 'barangay_id';

    protected $fillable = [
        'barangay_name'
    ];  

    public function appointment()
    {
        return $this->hasOne(Appointment::class, 'appointment_id', 'barangay_id');
    }

    public function applicant()
    {
        return $this->hasOne(NewApplicant::class);
    }
    
    public function account()
    {
        return $this->hasOne(Account::class, 'account_id', 'barangay_id');
    }
    public function pwd(){
        return $this->hasOne(Pwd::class, 'pwd_id', 'barangay_id');
    }
    
}
