<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {


    /**
     * Clients list
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::where('role',User::USER)->get();
        return view('admin.user.index')->with('users',$users);
    }

    /**
     * Clients json list
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function indexjson(Request $request)
    {
        $users = User::where('role',User::USER)->get();
        return response()->json($users);
    }

    /**
     * Ajouter un nouveau client
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email|unique:App\Models\User,email',
            'nom' => 'required',
            'prenom' => 'required',
            'telephone' => 'unique:App\Models\User,telephone'
        ]);

        if(!$validator->fails()) {
            $password = uniqid();
            $user = new User();
            $user->role = User::USER;
            $user->nom = $request->input('nom');
            $user->prenom = $request->input('prenom');
            $user->email = $request->input('email');
            $user->telephone = $request->input('telephone');
            $user->password = Hash::make($password);
            $user->is_active = USER::ACTIVE;
            $user->save();
            return redirect()->route('user_index')->with('message','Client ajouté !');
        } else {
            return redirect()->route('user_index')->withErrors($validator)->withInput();
        }
    }

    /**
     * Supprimer utilisateur
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request,int $id)
    {
        $message = 'Impossible de supprimer !';
        $user = User::find($id);
        if($user) {
            $user->delete();
            $message = 'Suppression faite !' ;
        }

        return redirect()->route('user_index')->with('message',$message);
    }


    /**
     * View utilisateur
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request,int $id)
    {
        $user = User::find($id);
        
        if(!$user) {
            return redirect()->route('user_index')->with('message', 'Client introuvable');    
        }

        return view('admin.user.view')->with('user', $user);       
    }

    /**
     * Edit utilisateur
     * 
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,int $id)
    {
        $user = User::find($id);
        
        if(!$user) {
            return redirect()->route('user_index')->with('message', 'Client introuvable');    
        }

        if($request->isMethod('POST')) {
            $email = $request->input('email');
            $telephone = $request->input('telephone');
            $nom = $request->input('nom');
            $prenom = $request->input('prenom');

            $exist = User::where('email', $email)->where('id','!=', $user->id)->count();

            if($exist == 0) {
                $user->email = $email;
                $user->telephone = $telephone;
                $user->nom = $nom;
                $user->prenom = $prenom;
                $user->save();
                return redirect()->route('user_index')->with('message', 'Client modifié');
            } else {
                return redirect()->route('user_index')->with('message', 'Email déja utilisé');
            }
        }

        return view('admin.user.edit')->with('user', $user);       
    }

}