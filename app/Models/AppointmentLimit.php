<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentLimit extends Model
{
    use HasFactory;

    protected $table = 'appointment_limit_months';

    protected $primaryKey = 'limit_id';

    // public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'appointment_month',
        'limits'
    ];
}
