<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Service\Constante;
use App\Models\User;

class UserController extends Controller
{

    /**
     * Recuperation profil
     * 
     * @param Request $request
     * @return json
     */
    public function get(Request $request)
    {
        $user = $request->user();
        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $user->toArray();
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }

    /**
     * Modifier nom et prenom
     * @param Request $request
     * @return json
     */
    public function edit(Request $request)
    {
        $user = $request->user();
        $nom = $request->input('nom');
        $prenom = $request->input('prenom');

        if(!empty($nom) && !empty($prenom)) {
            $user->nom = $nom;
            $user->prenom = $prenom;
            $user->save();
            $user->refresh();
        }

        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $user->toArray();
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }

}