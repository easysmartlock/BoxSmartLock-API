<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    use HasFactory;

    const ajoutTel = 'ajout_tel';
    const suppressionTel = 'suppression_tel';
    const access = 'access';
    const duration = 'duration';
    const listeTel = 'liste_tel';
    const modifPass = 'modif_pass';

    const modelBox = 'App\Models\Box' ;
    const modelEasy = 'App\Models\Easy' ;

    /**
    * Recupere l'utilisateur rattaché a cette action
    */
    public function User()
    {
        return $this->belongsTo('App\Models\User');
    }
}
