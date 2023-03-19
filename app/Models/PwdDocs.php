<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PwdDocs extends Model
{
    use HasFactory;

    protected $table = 'pwd_docs';

    protected $primaryKey = 'img_id';

    // public $timestamps = false;

    protected $fillable = [
        'pwd_id',
        'img_name',
        'docs_type',
        'appointment'
    ];

    public function pwd()
    {
        return $this->belongsTo(Pwd::class, 'pwd_id');
    }
    

}
