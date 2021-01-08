<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    use HasFactory;

    /**
    * Recupere l'utilisateur rattaché a ce box
    */
    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }
}
