<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Service\Constante;
use App\Models\Box;
use Carbon\Carbon;
use App\Service\Twilio;

class BoxController extends Controller
{

    public function __construct()
    {
        $this->middleware('box',[
            'except' => [
                'get',
                'find'
            ]
        ]);
    }


    /**
     * Recuperation des boxes du profil
     * 
     * @param Request $request
     * @return json
     */
    public function get(Request $request)
    {
        $user = $request->user();
        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $user->boxes->toArray();
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }

    /**
     * Recuperation detail d'un box
     * 
     * @param Request $request
     * @param String $id
     * @return json
     */
    public function find(Request $request, String $id)
    {
        $user = $request->user();
        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $user->boxes()->where('id', $id)->first()->toArray();
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }

    /**
     * ajout numero de tel
     * 
     * @param Request $request
     * @param Twilio $twilio
     * @return json
     */
    public function addPhone(Request $request,Twilio $twilio)
    {
        $id = $request->input('id');
        $box = Box::find($id);
        $user = $request->user();
        $debut = $request->input('debut');
        $fin = $request->input('fin');
        $unlimited = $request->input('unlimited');
        $prefix = $request->input('prefix');
        $telephone = $request->input('telephone');

        $result = $twilio->addTelBox(
            $box,
            $prefix.$telephone,
            $debut,
            $fin,
            $unlimited
        );

        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $result;
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }


    /**
     * Modifier les access
     * @param Request $request 
     * @param Twilio $twilio
     * @return json
     */
    public function editAccess(Request $request, Twilio $twilio)
    {
        $id = $request->input('id');
        $action = $request->input('action');
        $box = Box::find($id);
        $result = $twilio->editAccess($box,$action);

        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $result;
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }

    /**
     * Modification durée ouverture
     * 
     * @param Request $request
     * @param Twilio $twilio
     * @return json
     */
    public function editDuration(Request $request, Twilio $twilio)
    {
        $id = $request->input('id');
        $duration = $request->input('duration');
        $box = Box::find($id);
        $result = $twilio->editDuration($box,$duration);

        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $result;
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }

}