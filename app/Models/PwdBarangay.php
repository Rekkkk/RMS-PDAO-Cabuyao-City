<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PwdBarangay extends Model
{
    protected $table = 'pwd_barangay';

    public $timestamps = false;

    public function pwd()
    {
        return $this->belongsTo(Pwd::class);
    }

  
}


