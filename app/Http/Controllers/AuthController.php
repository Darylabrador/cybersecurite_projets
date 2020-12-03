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
                'message' => $errors->first()
            ]);
        }

        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            $user->tentatives = $user->tentatives + 1;
            $user->save();

            if($user->tentatives > 3) {
                $user->tentatives = 3;
                $user->save();
                Log::channel('abuse')->info("L'utilisateur {$user->email} à atteint son nombre maximal de tentative de connexion ! ");
                return response()->json([
                    'success' => false,
                    'message' => "Vous avez dépasser le nombre de tentatives autorisé",
                ]);
            } 
            return response()->json([
                'success' => false,
                'message' => "Adresse email ou mot de passe invalide !",
                'tentative' => $user->tentatives
            ]);
        }

        $token = $user->createToken('Auth token')->accessToken;
        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    }
}
