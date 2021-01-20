<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Easy extends Model
{
    use HasFactory;

    /**
    * Recupere l'utilisateur rattaché a cette serrures
    */
    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }

     /**
     * Recupération des téléphones
     */
    public function Telephones()
    {
        return $this->hasMany('App\Models\EasyTelephone','easy_id');
    }
}
