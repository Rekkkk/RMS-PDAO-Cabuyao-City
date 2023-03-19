<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';

    protected $primaryKey = 'account_id';

    public $timestamps = false;

    protected $fillable = [
        'email',
        'password',
        'is_super_admin',
        'user_id',
        'barangay_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');

    }
    public function accountStatus(){
        return $this->hasOne(AccountStatus::class, 'account_id');
    }
}   