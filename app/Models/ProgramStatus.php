<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramStatus extends Model
{
    use HasFactory;

    protected $table = 'program_status';

    public $timestamps = false;

    protected $fillable = [
        'program_id',
        'encoding_status',
        'is_done'
    ];


    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
