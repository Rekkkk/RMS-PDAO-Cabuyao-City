<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signatory extends Model
{
    use HasFactory;

    protected $table = 'signatory';

    protected $primaryKey = 'img_id';

    public $timestamps = false;

    protected $fillable = [
        'img_file',
        'signatory_type'

    ];
}
