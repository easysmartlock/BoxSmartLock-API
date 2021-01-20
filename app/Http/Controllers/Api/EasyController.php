<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Service\Constante;
use App\Models\Easy;
use App\Models\EasyTelephone;
use Carbon\Carbon;
use App\Service\Twilio;

class EasyController extends Controller
{

    public function __construct()
    {
        $this->middleware('easy',[
            'except' => [
                'get',
                'find'
            ]
        ]);
    }

    /**
     * Recuperation des serrures du profil
     * 
     * @param Request $request
     * @return json
     */
    public function get(Request $request)
    {
        $user = $request->user();
        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $user->easies->toArray();
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }

    /**
     * Recuperation detail d'une serrure
     * 
     * @param Request $request
     * @param String $id
     * @return json
     */
    public function find(Request $request, String $id)
    {
        $user = $request->user();
        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $user->easies()->where('id', $id)->first()->toArray();
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
        $e = Easy::find($id);
        $user = $request->user();
        $debut = $request->input('debut');
        $fin = $request->input('fin');
        $unlimited = $request->input('unlimited');
        $prefix = $request->input('prefix');
        $telephone = $request->input('telephone');

        $result = $twilio->addTelEasy(
            $e,
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
        $e = Easy::find($id);
        $result = $twilio->editEasyAccess($e,$action);

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
        $e = Easy::find($id);
        $result = $twilio->editEasyDuration($e,$duration);

        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $result;
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }

    /**
     * Récupération des téléphones
     * 
     * @param Request $request
     * @return json
     */
    public function getPhones(Request $request)
    {
        $id = $request->input('id');
        $e = Easy::find($id);
        
        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = (count($e->telephones) > 0) ? $e->telephones : [];
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }


    /**
     * Demande la liste des telephones
     * 
     * @param Request $request
     * @param Twilio $twilio
     * @return json
     */
    public function requestPhone(Request $request, Twilio $twilio)
    {
        $id = $request->input('id');
        $e = Easy::find($id);
        $result = $twilio->requestEasyPhone($e);

        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $result;
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }

    /**
     * Suppression numero telephone
     * 
     * @param Request $request
     * @param Twilio $twilio
     * @return json
     */
    public function delPhone(Request $request, Twilio $twilio)
    {
        $reponse = Constante::getReponse();
        
        $id = $request->input('id');
        $phoneId = $request->input('phoneId');

        $e = Easy::find($id);
        $phone = EasyTelephone::find($phoneId);
        $result = false;

        if($phone && $phone->easy->id == $e->id) {
            $result  = $twilio->delEasyPhone($e,$phone);
            if($result) {
                $phone->telephone = NULL;
                $phone->save();
                $e->refresh();
                $reponse[Constante::PROP_DATA] = $e->telephones;
            }
        } else {
            $reponse[Constante::PROP_MESSAGE] = 'Numéro introuvable' ;
        }
        
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }
}