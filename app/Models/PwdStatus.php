<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PwdStatus extends Model
{
    use HasFactory;

    protected $table = 'pwd_status';

    public $timestamps = false;

    protected $fillable = [
        'pwd_id',
        'id_expiration',
        'cancelled',
        'cancelled_date'
    ];

    // public function pwd()
    // {
    //     return $this->belongsTo(Appointment::class);
    // }
}
