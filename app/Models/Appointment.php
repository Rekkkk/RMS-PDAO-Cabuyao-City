<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'appointment_id';

    protected $table = 'appointment';

    protected $fillable = [
        'transaction',
        'appointment_date',
        'barangay_id',
        'is_reschedule'
    ];  


    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');
    }

    public function applicant()
    {
        return $this->hasOne(NewApplicant::class, 'appointment_id');
    }
    
    public function renewal()
    {
        return $this->hasOne(Renewal::class, 'appointment_id');
    }

    public function lostId()
    {
        return $this->hasOne(LostId::class, 'appointment_id');
    }

    public function cancellation()
    {
        return $this->hasOne(Cancellation::class, 'appointment_id');
    }
    

    public function remark()
    {
        return $this->hasOne(AppointmentRemarks::class, 'appointment_id');
    }

    
    public function pictures()
    {
        return $this->hasMany(AppointmentDocs::class, 'appointment_id');
    }


}

