<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Service\Constante;
use App\Models\User;

class TwilioController extends Controller
{

    /**
     * Recuperation
     * 
     * @param Request $request
     * @return json
     */
    public function get(Request $request)
    {
    	print_r($request->all());
        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = [];
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;
        return response()->json($reponse);
    }

}