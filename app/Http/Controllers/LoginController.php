<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return view('login.login');
    }

    /**
     * Reinitialisation mot de passe
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function password(Request $request)
    {
        $token_password = $request->input('token_password');
        $user = User::where('token_password',$token_password)->first();
        if ($request->isMethod('post') && $user) {
            $validator = Validator::make($request->all(),[
                'password' => 'required',
                'confpassword' => 'required',
            ]);
            if(!$validator->fails()) {
                $password = $request->input('password');
                $user->password = Hash::make($password);
                $user->token_password = Hash::make($password);
                $user->save();
                return redirect()->route('password_ok');
            }
        }
        return view('login.password')->with('user', $user);
    }

    /**
     * OK mot de passe
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function passwordOk(Request $request)
    {
        return view('login.password_ok');
    }
}