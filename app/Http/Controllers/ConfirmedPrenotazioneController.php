<?php

namespace App\Http\Controllers;


use App\Models\Prenotazione_confirmed;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ConfirmedPrenotazioneController extends Controller
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
                $res['data'] = DB::table('prenotazione_confirmeds')
                                  ->select('prenotazione_confirmeds.*')
                                  ->get();

                $res['number'] = Prenotazione_confirmed::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Richieste Prenotazioni da Confermare';
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
            $User = new Prenotazione_confirmed();
            $success = true;
            $postData = $request->except('_method', 'id');  // id automatico
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
     * @param  int  $id
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
                           $res['message'] = 'trovato Richieste Prenotazioni serate da confermare';
                           $res['data'] = Prenotazione_confirmed::select('prenotazione_confirmeds.*')
                                                                ->findOrFail($id);
                         } catch (\Exception $e){
                            $res['message'] =  'Richiesta Prenotazione Inesistente !! ';            // $e->getMessage();
                        }
                        return $res;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
                             //  eseguo aggiornamento dell'utente

        // inizializzo i parametri per l'aggiornamento
        $data = [];
        $message = '';
        try {
            $User = Prenotazione_confirmed::findOrFail($id);
            $success = true;
            // salva sulla variabile data i dati dalla richiesta (request)
            // ad eccezzione del campo id e del campo di comodo _method

            $postData = $request->except('id','_method');
            // imposto la crittografia alla password  (questo temporraneo)
            // $postData['password'] =  Hash::make($postData['password'] ?? 'password');  // bcrypt('test');   -->   non abbiamo password
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = [];
        $message = 'Cancellazione eseguita con successo !!';
        $success = true;
        try {
            $User = Prenotazione_confirmed::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'Richiesta Prenotazione non trovata - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }


    // metodo che non funziona - chiedere a hidran
    public function getbyemaildatapre(Request $request) {

        $email = $request->email;
        $datapren = $request->datapren;

        $res = [
            'data' =>[],
            'number' => 0,
            'message' => 'nessuna richiesta di prenotazione per questa email e data'

                ];
            try{

           /*     $res['data'] = DB::table('prenotazione_confirmeds')->where('email', '=', $email)->where('datapren', '<=',Carbon::now($datapren)->timezone('Europe/Stockholm'))->first();  */

                $res['data'] = DB::table('prenotazione_confirmeds')->where('email', '=', $email)->where('datapren', '<=',Carbon::now('Y-m-d H:i:s', $datapren, 'Europe/Stockholm'))->first();




/*
                $res['number'] = Prenotazione_corfirmed::where('email',$email)
                                                        ->where('datapren','<=',Carbon::now($datapren))
                                                        ->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                if($res['number'] > 0) {
                    $res['message'] = 'trovata richiesta conferma prenotazione con dati di impostati';
                }
                */

            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }


    public function getbyemail(Request $request) {

        $email = $request->email;

        $res = [
            'data' =>[],
            'number' => 0,
            'message' => 'nessuna richiesta di prenotazione per questa email'

                ];
            try{

                $res['data'] = DB::table('prenotazione_confirmeds')->where('email', '=', $email)->get();
                $res['number'] = DB::table('prenotazione_confirmeds')->where('email', '=', $email)->count();
/*
                $res['number'] = Prenotazione_corfirmed::where('email',$email)
                                                       ->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                if($res['number'] > 0) {
                    $res['message'] = 'trovate richieste prenotazione ';
                }  */

            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }

    public function getbytoken(Request $request) {

        $token = $request->token;

        $res = [
            'data' =>[],
            'number' => 0,
            'message' => 'nessuna richiesta di prenotazione per questo token'

                ];
            try{

                $res['data'] = DB::table('prenotazione_confirmeds')->where('token', '=', $token)->first();
                $res['number'] = DB::table('prenotazione_confirmeds')->where('token', '=', $token)->count();
                if($res['number'] > 0) {
                    $res['message'] = 'trovata richiesta prenotazione ';
                }
/*
                $res['number'] = Prenotazione_corfirmed::where('email',$email)
                                                       ->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                if($res['number'] > 0) {
                    $res['message'] = 'trovate richieste prenotazione ';
                }  */

            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }

    public function getbytokencodpre(Request $request) {

        $token = $request->token;
        $codpren = $request->codpren;

        $res = [
            'data' =>[],
            'number' => 0,
            'message' => 'nessuna richiesta di prenotazione per questo token e codice conferma'

                ];
            try{

                $res['data'] = DB::table('prenotazione_confirmeds')->where('token', '=', $token)->where('codpren', '=', $codpren)->first();
                $res['number'] = DB::table('prenotazione_confirmeds')->where('token', '=', $token)->where('codpren', '=', $codpren)->count();
                if($res['number'] > 0) {
                    $res['message'] = 'trovata richiesta prenotazione ';
                }
/*
                $res['number'] = Prenotazione_corfirmed::where('email',$email)
                                                       ->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                if($res['number'] > 0) {
                    $res['message'] = 'trovate richieste prenotazione ';
                }  */

            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }

    // funziona
    public function destroyToken($token)
    {
        $data = [];
        $message = 'Cancellazione Token Prenotazione eseguita con successo !!';
        $success = true;
        $rc = 'OK';
        $User = 'xx';
        try {



            $User = DB::table('prenotazione_confirmeds')->where(['token' => $token])->delete();
            $data = $User;
            if($User === 0) {
                $success = false;
                $message = 'Richiesta Prenotazione inesistente !! - Cancellazione non possibile';
                $rc = 'KO';
            }

      //      $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'Richiesta Prenotazione non trovata - Cancellazione non possibile';
        }
        return compact('data','message','success', 'rc', 'User');
    }

}
