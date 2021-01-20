<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EasyTelephone extends Model
{
    use HasFactory;

    /**
    * Recupere serrure
    */
    public function Easy()
    {
        return $this->belongsTo('App\Models\Easy');
    }
}
