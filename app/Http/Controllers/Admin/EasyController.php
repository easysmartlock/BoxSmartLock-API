<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Easy;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Service\Twilio;

class EasyController extends Controller {

    /**
     * Easy list
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $easies = Easy::paginate(50);
        return view('admin.easy.index')->with('easies',$easies);
    }

    /**
     * Attach EasySmart 
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function attach(Request $request)
    {
        $id = $request->input('id');
        $user_id = $request->input('user_id');

        $e = Easy::find($id);
        $user = User::find($user_id);

        if(!$e || !$user) {
            return redirect()->route('easy_index')->with('message','Utilisateur ou easysmart introuvable');
        }

        $e->user()->associate($user);
        $e->save();

        return redirect()->route('easy_index')->with('message','EasySmart rattaché !');
    }

    /**
     * Ajouter une nouvelle easy
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'identifiant' => 'required|unique:App\Models\Easy,identifiant',
            'pass' => 'required',
            'telephone' => 'required|unique:App\Models\Easy,telephone'
        ]);

        if($validator->fails()) {
            return redirect()->route('easy_index')->withErrors($validator)->withInput();
        } else {
            $e = new Easy();
            $e->pass = $request->input('pass');
            $e->telephone = $request->input('telephone');
            $e->nom = $request->input('nom');
            $e->identifiant = $request->input('identifiant');
            $e->hebergement = $request->input('hebergement','');
            $e->save();
            return redirect()->route('easy_index')->with('message','EasySmart ajouté !');
        }
    }

    /**
     * Mot de passe easy 
     * 
     * @param \Illuminate\Http\Request $request
     * @param Twilio $twilio
     * @return \Illuminate\Http\Response
     */
    public function pass(Request $request, Twilio $twilio)
    {
        $id = $request->input('id');
        $pass = $request->input('pass');

        $easy = Easy::find($id);

        if(!$easy) {
            return redirect()->route('easy_index')->with('message','Easy introuvable');
        }

        $previous = $easy->pass;
        $easy->pass = $pass;
        $easy->save();

        $twilio->setEasyPassword($easy,$previous,auth()->user());

        return redirect()->route('easy_index')->with('message','Mot de passe de la serrure a été modifié !');
    }
	
	public function nom(Request $request)
    {
        $id = $request->input('nom_id');
        $nom = $request->input('nom');
		
		if(!$request->input('nom')) {
            return redirect()->route('easy_index')->with('message','Nom vide');
        }
		
        $easy = Easy::find($id);

        if(!$easy) {
            return redirect()->route('easy_index')->with('message','Easy introuvable');
        }

        $previous = $easy->nom;
        $easy->nom = $nom;
        $easy->save();

        return redirect()->route('easy_index')->with('message','Nom de la serrure a été modifié !');
    }

}