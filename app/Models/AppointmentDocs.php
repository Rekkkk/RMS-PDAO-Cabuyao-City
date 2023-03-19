<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentDocs extends Model
{
    use HasFactory;

    
    protected $table = 'appointment_docs';

    protected $primaryKey = 'img_id';

    public $timestamps = false;

    protected $fillable = [
        'appointment_id',
        'img_name',
        'docs_type'
    ];

    public function appointment()
    {
        return $this->belongsTo(appointment::class, 'appointment_id');
    }



}
