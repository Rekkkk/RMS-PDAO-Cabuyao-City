<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    use HasFactory;

    protected $table = 'user_status';
    
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'is_disable',
        'is_suspend'
    ];

    public function account(){
        return $this->belongTo(User::class, 'user_id');
    }
}
