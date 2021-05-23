<?php

namespace App\Http\Controllers;

use App\Models\Register_confirmed;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RegisterConfirmedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = [
            'data' =>[],
            'number' => 0,
            'message' => ''
                ];
            try{
               // $res['data'] = Manifestazione::all();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
               // lettura con join a tabelle correlate
                $res['data'] = DB::table('register_confirmeds')
                                ->select('register_confirmeds.*')
                                ->get();

                $res['number'] = Register_confirmed::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'Richieste di conferma registrazione Trovate: ' + $res['number'];
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [];
        $message = '';
        try {
            $User = new Register_confirmed();
            $success = true;
            $postData = $request->except('_method');
           // $postData['password'] =  Hash::make($postData['password'] ?? 'password');     // nel caso ci siano campi da cripare
            $User->fill($postData);
            $success = $User->save();
            $data = $User;
            $message = 'Inserimento eseguito con successo';
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        return compact('data','message','success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Register_confirmed  $register_confirmed
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

                         // lettura con join a tabelle correlate
                         $res = [
                            'data' =>[],
                            'message' => ''
                                ];
                          try{
                           $res['message'] = 'trovato Richiesta di Conferma Registrazione';
                           $res['data'] = Register_confirmed::select('register_confirmeds.*')
                                                            ->findOrFail($id);
                         } catch (\Exception $e){
                            $res['message'] =  'richiesta inesistente Inesistente !! ';            // $e->getMessage();
                        }
                        return $res;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Register_confirmed  $register_confirmed
     * @return \Illuminate\Http\Response
     */
    public function edit(Register_confirmed $register_confirmed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Register_confirmed  $register_confirmed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $email)
    {
                                //  eseguo aggiornamento dell'utente

        // inizializzo i parametri per l'aggiornamento
        $data = [];
        $message = '';
        try {
            $User = Register_confirmed::findOrFail($email);
            $success = true;
            // salva sulla variabile data i dati dalla richiesta (request)
            // ad eccezzione del campo id e del campo di comodo _method

            $postData = $request->except('_method');
            // eseguo l'aggiornamento
            $success = $User->update($postData);
            $data = $User;
            $message = 'Aggiornamento eseguito con successo';

        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        return compact('data','message','success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Register_confirmed  $register_confirmed
     * @return \Illuminate\Http\Response
     */
    public function destroy($email)
    {
        $data = [];
        $message = 'Cancellazione eseguita con successo !!';
        $success = true;
        try {
            $User = Register_confirmed::findOrFail($email);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'richiesta Conferma registrazione non trovata - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }

    public function getbytoken($token) {

        $res = [
            'data' =>[],
            'number' => 0,
            'message' => 'nessuna richiesta di conferma registrazione per questa email'
                ];
            try{

                $res['data'] = Register_confirmed::select('register_confirmeds.*')
                                                  ->where('token',$token)->first();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();

                $res['number'] = Register_confirmed::where('token',$token)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                if($res['number'] > 0) {
                    $res['message'] = 'trovata richiesta conferma Registrazione per email';
                }

            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }

    public function getRegConfirmbyTokenEmailPass(Request $request) {

        $token = $request->token;
        $email = $request->email;
        $password = $request->password;

        $res = [
            'data' =>[],
            'number' => 0,
            'message' => 'nessuna richiesta di conferma registrazione per questa email-password e token',
            'messParam' => 'token: ' + $token + ' email: ' + $email + ' password: ' + $password
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
