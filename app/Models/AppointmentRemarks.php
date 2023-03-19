<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentRemarks extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'appointment_remarks';

    protected $fillable = [
        'appointment_id',
        'remark'
    ];  

    public function appointments()
    {
        return $this->belongsTo(Appointment::class);
    }
}
