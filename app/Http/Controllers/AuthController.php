<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;



class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);    // email   originaria

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['data'=> $credentials,'error' => 'Unauthorized1'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function signup()
    {
        $credentials = request(['cognome', 'username', 'password']);

        $data = $credentials;
        $credentials['password'] = Hash::make($credentials['password']);
        $res = User::create($credentials);

        if(!$res) {
            return response()->json(['error' => 'errore in creazione utente'], 500);

        }
        // se creato utente faccio login per passare il token
        if (! $token = auth()->login($res)) {
            return response()->json(['error' => 'Unauthorized2'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
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
            'level' => auth()->user()->idRuolo_Day,
            'id' => auth()->user()->id,
            'idruoloweb' => auth()->user()->idruoloweb
        ]);
    }
}

