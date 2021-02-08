<?php

namespace App\Http\Controllers;

use App\Mail\NotificationUnlockedAccount;
use App\Mail\NotificationLockedAccount;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Jobs\ResetTentatives;
use Carbon\Carbon;

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

        $email      = $validator->validated()['email'];
        $password   = $validator->validated()['password'];
        $email      = $validator->validated()['email'];
        $password   = $validator->validated()['password'];
        $userExist  = User::where(["email" => $email])->first();

        if (!$userExist) {
            return response()->json([
                'success' => false,
                'message' => "Adresse email ou mot de passe incorrecte"
            ]);
        }

        $oldTentative = $userExist->tentatives;

        switch ($oldTentative) {
            case 3:
                $userExist->tentatives = 4;
                $userExist->save();

                Mail::to($userExist->email)->later(now()->addMinutes(30), new NotificationUnlockedAccount());
                $resetJob = (new ResetTentatives($userExist->id, $userExist->email))->delay(Carbon::now()->addMinutes(30));
                dispatch($resetJob);
                openlog('TEMAAS_AUTH', LOG_NDELAY, LOG_USER);
                syslog(LOG_INFO, "L'utilisateur {$userExist->email} à atteint son nombre maximal de tentative de connexion ! ");
                Mail::to($userExist->email)->send(new NotificationLockedAccount());

                return response()->json([
                    'success' => false,
                    'message' => "Veuillez réessayer dans 30 minutes",
                ]);
                break;
            case 4:
                return response()->json([
                    'success' => false,
                    'message' => "Veuillez réessayer dans quelques minutes",
                ]);
                break;
            default:
                if (Hash::check($password, $userExist->password)) {
                    $userExist->tentatives = 0;
                    $userExist->save();
                    $token = $userExist->createToken('AuthToken')->accessToken;
                    return response()->json([
                        "success" => true,
                        "message" => "Vous êtes connecté !",
                        "token"   => $token
                    ]);
                } else {
                    $tentative    = $userExist->tentatives + 1;
                    $userExist->tentatives = $tentative;
                    $userExist->save();
                    return response()->json([
                        'success' => false,
                        'message' => "Adresse email ou mot de passe incorrecte",
                    ]);
                }
                break;
        }
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
