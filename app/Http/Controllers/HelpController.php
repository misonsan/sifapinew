<?php

namespace App\Http\Controllers;

use App\Models\Register_confirmed;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class HelpController extends Controller
{
    public function getRegConfirmbyTokenEmailPass($token,$email,$password) {

        $res = [
            'data' =>[],
            'number' => 0,
            'message' => 'nessuna richiesta di conferma registrazione per questa email-password e token',
         //   'messParam' => 'token: ' + $token + ' email: ' + $email + ' password: ' + $password
                ];
            try{

                $res['data'] = DB::table('register_confirmeds')->where('token', '=', $token)->where('email', '=', $email)->where('password', '=', $password)->first();


/*
                $res['data'] = Register_confirmed::select('register_confirmeds.*')
                                                  ->where('token',$token)
                                                  ->where('email',$email)
                                                  ->where('password',$password)
                                                  ->first();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                                                  */
                $res['number'] = Register_confirmed::where('token',$token)
                                                    ->where('email',$email)
                                                    ->where('password',$password)
                                                    ->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                if($res['number'] > 0) {
                    $res['message'] = 'trovata richiesta conferma Registrazione con dati di conferma';
                }

            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }
}
