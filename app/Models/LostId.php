<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostId extends Model
{
    use HasFactory;

    
    protected $table = 'appointment_lost_id';

    public $timestamps = false;

    protected $fillable = [
        'appointment_id',
        'pwd_id'
    ];

    public function pwd()
    {
        return $this->belongsTo(Pwd::class, 'pwd_id');
    }

    public function appointments()
    {
        return $this->belongsTo(Appointment::class);
    }

   
    
}
