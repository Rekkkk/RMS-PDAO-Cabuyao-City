<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'programs';
    
    protected $primaryKey = 'program_id';

    protected $fillable = [
        'program_title',
        'program_type',
        'barangay_id',
        'disability_involve',
        'program_description'
    ];


    public function programStatus()
    {
        return $this->hasOne(ProgramStatus::class, 'program_id');
    }

    public function pictures()
    {
        return $this->hasMany(ProgramImage::class, 'program_id');
    }

    public function signatory()
    {
        return $this->hasMany(ProgramSignatory::class, 'program_id');
    }

    public function memorandum()
    {
        return $this->hasMany(ProgramMemo::class, 'program_id');
    }
    
    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');
    }

    public function pwd()
    {
        return $this->belongsToMany(Pwd::class, 'program_claimants', 'program_id', 'pwd_id')->withPivot('is_unclaim','reference_no','created_at');

    }



}
