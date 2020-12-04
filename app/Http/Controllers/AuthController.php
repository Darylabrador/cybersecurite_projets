<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Handle user connection
     * @param  \Illuminate\Http\Request  $request
     */
    public function postLogin(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required',
                'password' => 'required',
            ],
            [
                'required' => 'Le champ :attribute est requis',
            ]
        );

        $errors = $validator->errors();
        if (count($errors) != 0) {
            return response()->json([
                'success' => false,
                'type' => 'danger',
                'message' => $errors->first()
            ]);
        }

        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){

            if($user) {
                $user->tentatives = $user->tentatives + 1;
                $user->save();
                if($user->tentatives > 3) {
                    $user->tentatives = 3;
                    $user->save();
                    Log::channel('abuse')->info("L'utilisateur {$user->email} à atteint son nombre maximal de tentative de connexion ! ");
                    return response()->json([
                        'success' => false,
                        'type'    => 'info',
                        'message' => "Veuillez réessayer dans 5 minutes",
                    ]);
                } 
            }

            return response()->json([
                'success' => false,
                'type'    => 'danger',
                'message' => "Adresse email ou mot de passe invalide !",
                'tentative' => $user->tentatives
            ]);
        }

        $token = $user->createToken('Auth token')->accessToken;
        return response()->json([
            'success' => true,
            'type'    => 'success',
            'message' => 'Vous êtes connecté(e)',
            'token' => $token
        ]);
    }


    /**
     * Handle register
     * @param  \Illuminate\Http\Request  $request
     */
    public function inscription(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'email'           => 'required',
                'password'        => 'required',
                'passwordConfirm' => 'required'
            ],
            [
                'required' => 'Le champ :attribute est requis',
            ]
        );

        $errors = $validator->errors();
        if (count($errors) != 0) {
            return response()->json([
                'success' => false,
                'message' => $errors->first()
            ]);
        }

        $email           = $validator->validated()['email'];
        $password        = $validator->validated()['password'];
        $passwordConfirm = $validator->validated()['passwordConfirm'];

        if($password != $passwordConfirm) {
            return response()->json([
                'success' => false,
                'type' => 'danger',
                'message' => "Les mots passes ne sont pas identiques"
            ]);
        }

        $user = User::where(['email' => $email])->first();
        if(!$user) {
            $user = User::create([
                "email" => $email,
                "password" => Hash::make($password),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Inscription validée'
            ]);
        } 
        
        return response()->json([
            'success' => false,
            'type' => 'danger',
            'message' => 'Vous ne pouvez pas vous inscrire !'
        ]);
        
    }
}
