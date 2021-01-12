<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Box;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Service\Twilio;

class BoxController extends Controller {


    /**
     * Box list
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $boxes = Box::paginate(50);
        return view('admin.box.index')->with('boxes',$boxes);
    }

    /**
     * Attach box 
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function attach(Request $request)
    {
        $id = $request->input('id');
        $user_id = $request->input('user_id');

        $box = Box::find($id);
        $user = User::find($user_id);

        if(!$box || !$user) {
            return redirect()->route('box_index')->with('message','Utilisateur ou box introuvable');
        }

        $box->user()->associate($user);
        $box->save();

        return redirect()->route('box_index')->with('message','Box rattaché !');
    }

    /**
     * Ajouter une nouvelle box
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'nom' => 'required',
            'pass' => 'required',
            'telephone' => 'required|unique:App\Models\Box,telephone'
        ]);

        if($validator->fails()) {
            return redirect()->route('user_index')->withErrors($validator)->withInput();
        } else {
            $box = new Box();
            $box->pass = $request->input('pass');
            $box->telephone = $request->input('telephone');
            $box->nom = $request->input('nom');
            $box->save();
            return redirect()->route('box_index')->with('message','Box ajouté !');
        }
    }


    /**
     * Mot de passe box 
     * 
     * @param \Illuminate\Http\Request $request
     * @param Twilio $twilio
     * @return \Illuminate\Http\Response
     */
    public function pass(Request $request, Twilio $twilio)
    {
        $id = $request->input('id');
        $pass = $request->input('pass');

        $box = Box::find($id);

        if(!$box) {
            return redirect()->route('box_index')->with('message','Box introuvable');
        }

        $previous = $box->pass;
        $box->pass = $pass;
        $box->save();

        $twilio->setBoxPassword($box,$previous);

        return redirect()->route('box_index')->with('message','Mot de passe de la box modifié !');
    }
}