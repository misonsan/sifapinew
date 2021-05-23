<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Register_confirmed;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ConfirmedAccountController extends Controller
{
      /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup(Request $request)
    {
        $credentials = request(['cognome', 'name', 'email', 'password', 'username']);

        $credentials['password'] = Hash::make($credentials['password']);

        $res = User::create($credentials);

        if(!$res){
            return response()->json(['error' => 'Error creating user'], 500);
        }

        if (! $token = auth()->login($res)) {

            return response()->json(['error' => 'Unauthorized2'], 401);
        }

        $this->getPasswordResetTableRow($credentials['email']);

        return $this->respondWithToken($token);

        $response['status'] = 1;
        $response['code'] = 200;
        $response['data'] = $res;
        $response['message'] = 'Utente Creato con successo';
        return response()->json($response);
    }


    private function getPasswordResetTableRow($email)

       {
         // cancellazione funziona

              DB::table('register_confirmeds')->where(['email' => $email])->delete();

              $response['status'] = 8;
              $response['code'] = 200;
              $response['data'] = null;
              $response['message'] = 'richiesta di nuovo utente cancellata';
              return response()->json($response);

       }




/**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
             // salvo dei parametri aggiuntivi dell'utente
            'username' => auth()->user()->username,
            'cognome' => auth()->user()->cognome,
            'email'  => auth()->user()->email,
            'level' => auth()->user()->idRuolo,
            'id' => auth()->user()->id
        ]);
    }

}
