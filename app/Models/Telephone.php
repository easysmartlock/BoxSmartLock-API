<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telephone extends Model
{
    use HasFactory;


    const LIMIT = 6 ;


    /**
    * Recupere box
    */
    public function Box()
    {
        return $this->belongsTo('App\Models\Box');
    }
}
