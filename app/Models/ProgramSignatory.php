<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramSignatory extends Model
{
    use HasFactory;

    protected $table = 'program_signatory';

    protected $primaryKey = 'signatory_id';

    public $timestamps = false;

    protected $fillable = [
        'program_id',
        'barangay_id',
        'file_name',
        
    ];


    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id');
    }
}
