<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramImage extends Model
{
    use HasFactory;

    protected $table = 'program_img';

    protected $primaryKey = 'img_id';

    public $timestamps = false;

    protected $fillable = [
        'program_id',
        'img_name'
    ];


    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }


}
