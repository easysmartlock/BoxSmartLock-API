<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Service\Constante;
use App\Models\Box;
use App\Models\Telephone;
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
        $ordre = $request->input('ordre');
        $nom = $request->input('nom');
         

        $result = $twilio->addTelBox(
            $box,
            $prefix.$telephone,
            $debut,
            $fin,
            $unlimited,
            $ordre,
            $request->user()
        );

        if($result == true) {
            /**
             * Ajout telephone
             */
            $phone = new Telephone();
            $phone->nom = $nom;
            $phone->box()->associate($box);
            $phone->telephone = $telephone;
            $phone->debut = $debut;
            $phone->fin = $fin;
            $phone->unlimited = $unlimited;
            $phone->ordre = ((strlen($ordre) < 2) ? '00' : '0' ) . $ordre;
            $phone->save();
        }
        
        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $result;
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }

    /**
    * Ajout message
    */
    public function addMessage(Request $request)
    {
        $marque = $request->input('marque','');
        $telephone = $request->input('telephone','');
        $pass = $request->input('pass', '');

        return response()->json([]);
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
        $result = $twilio->editAccess($box,$action,$request->user());

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
        $result = $twilio->editDuration($box,$duration,$request->user());

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
        $box = Box::find($id);
        
        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = (count($box->telephones) > 0) ? $box->telephones : [];
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
        $box = Box::find($id);
        $result = $twilio->requestPhone($box,$request->user());

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

        $box = Box::find($id);
        $phone = Telephone::find($phoneId);
        $result = false;

        if($phone && $phone->box->id == $box->id) {
            $result  = $twilio->delPhone($box,$phone,$request->user());
            if($result) {
                $phone->delete();
                $box->refresh();
                $reponse[Constante::PROP_DATA] = $box->telephones;
            }
        } else {
            $reponse[Constante::PROP_MESSAGE] = 'Numéro introuvable' ;
        }
        
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }

    /**
     * SMS ouverture et fermeture
     * 
     * @param Request $request
     * @param Twilio $twilio
     * @return json
     */
    public function editSMS(Request $request, Twilio $twilio)
    {
        $id = $request->input('id');
        $action = $request->input('action');
        $box = Box::find($id);

        $result = $twilio->editSMS($box,$action,$request->user());

        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $result;
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }

    /**
     * get valid ordre
     * 
     * @param Request $request 
     * @return json
     */
    public function getOrdre(Request $request)
    {
        $ordres = [];
    
        $id = $request->input('id');
        $box = Box::find($id);

        $array = $box->telephones()->get()->map(function($telephone) {
            return (int) $telephone->ordre;
        })->toArray();

        for($i = 1 ; $i <= 20 - Telephone::LIMIT ; $i++) {
            if(!in_array($i,$array)) $ordres[] = $i;
        }

        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $ordres;
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);       
    }

    /**
    *   Récupération
    * @param Request $request
    * @param Twilio $twilio
    * @return json
    */
    public function recup(Request $request, Twilio $twilio)
    {
        $id = $request->input('id');
        $box = Box::find($id);

        if(!$box) {
            return response()->status(404)->json([]);
        }

        $result = $twilio->recup($box);
        $reponse = Constante::getReponse();
        $reponse[Constante::PROP_DATA] = $result;
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;
        return response()->json($reponse);
    }

}