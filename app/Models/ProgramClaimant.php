<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramClaimant extends Model
{
    use HasFactory;

    protected $table = 'program_claimants';

    protected $primaryKey = 'reference_no';
    
    protected $fillable = [
        'program_id',
        'pwd_id',
        'is_unclaim'
    ];
}
