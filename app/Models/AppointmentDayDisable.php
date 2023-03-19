<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentDayDisable extends Model
{
    use HasFactory;

    
    protected $table = 'appointment_disable_day';

    public $timestamps = false;

 
    protected $fillable = [
        'date'
    ];

    // protected $casts = [
    //     'date' => 'datetime:m/d/Y'
    // ];

}
