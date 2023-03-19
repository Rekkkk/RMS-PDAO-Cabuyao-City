<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalkIn extends Model
{
    use HasFactory;

    protected $table = 'walk_in';

    // public $timestamps = false;

    protected $fillable = [
        'pwd_id',
        'barangay_id',
        'transaction'
    ];

    public function pwd()
    {
        return $this->belongsTo(Pwd::class, 'pwd_id');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');
    }
}
