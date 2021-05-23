<?php

namespace App\Http\Controllers;

use App\Models\Prenotazione;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PrenotazioneController extends Controller
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
                $res['data'] = DB::table('prenotaziones')
                                  ->join('T_stato_prenotaziones', 'prenotaziones.idstato', '=', 'T_stato_prenotaziones.id')
                                  ->select('prenotaziones.*', 'T_stato_prenotaziones.d_stato_prenotazione')
                                  ->get();
                $res['number'] = Prenotazione::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                if($res['number'] > 0) {
                    $res['message'] = 'trovato Prenotazioni Confermate';
                } else {
                    $res['message'] = 'nessuna Prenotazione Confermata';
                }

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
        $rc = 'KO';
        try {
            $User = new Prenotazione();
            $success = true;
            $postData = $request->except('_method', 'id', 'd_stato_prenotazione');  // id automatico
            // $postData['password'] =  Hash::make($postData['password'] ?? 'password');     // nel caso ci siano campi da cripare
            $User->fill($postData);
            $success = $User->save();
            $data = $User;
            $rc = 'OK';
            $message = 'Inserimento eseguito con successo';
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        return compact('data','message','success', 'rc');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prenotazione  $prenotazione
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
                         // lettura con join a tabelle correlate
                         $res = [
                            'data' =>[],
                            'message' => '',
                            'rc' => 'KO'
                                ];
                          try{
                           $res['message'] = 'trovato Prenotazione !!';
                           $res['data'] = Prenotazione::select('prenotaziones.*', 'T_stato_prenotaziones.d_stato_prenotazione')
                                                        ->join('T_stato_prenotaziones', 'prenotaziones.idstato', '=', 'T_stato_prenotaziones.id')
                                                        ->findOrFail($id);
                           $res['rc'] = 'OK';
                         } catch (\Exception $e){
                            $res['message'] =  'Richiesta Prenotazione Inesistente !! ';            // $e->getMessage();
                        }
                        return $res;

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prenotazione  $prenotazione
     * @return \Illuminate\Http\Response
     */
    public function edit(Prenotazione $prenotazione)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prenotazione  $prenotazione
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // inizializzo i parametri per l'aggiornamento
        $data = [];
        $message = '';
        try {
            $User = Prenotazione::findOrFail($id);
            $success = true;
            // salva sulla variabile data i dati dalla richiesta (request)
            // ad eccezzione del campo id e del campo di comodo _method

            $postData = $request->except('id','_method', 'd_stato_prenotazione');
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
     * @param  \App\Models\Prenotazione  $prenotazione
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = [];
        $message = 'Cancellazione eseguita con successo !!';
        $success = true;
        $rc = 'KO';
        try {
            $User = Prenotazione::findOrFail($id);
            $data = $User;
            $success = $User->delete();
            $rc = 'OK';
        } catch (\Exception $e) {
            $success = false;
            $message = 'Prenotazione non trovata - Cancellazione non possibile';
        }
        return compact('data','message','success', 'rc');
    }

    public function getPrenotazionidaEvadere(Request $request)

    {

       $stato = 0;
       $res = [
            'data' =>[],
            'message' => 'Nessuna Prenotazione da Evadere',
            'number' => 0,
            'Rc' => 'ko'
                ];
            try{
               // $res['data'] = DB::table('Commandas')->orderBy('id', 'DESC')->where('id', '<', $last)->get();

               $res['data'] = Prenotazione::select('prenotaziones.*', 'T_stato_prenotaziones.d_stato_prenotazione')
                                            ->join('T_stato_prenotaziones', 'prenotaziones.idstato', '=', 'T_stato_prenotaziones.id')
                                            ->where('idstato',$stato)->orderBy('cognome','asc')->get();
               $res['number'] = Prenotazione::select('prenotaziones.*', 'T_stato_prenotaziones.d_stato_prenotazione')
                                            ->join('T_stato_prenotaziones', 'prenotaziones.idstato', '=', 'T_stato_prenotaziones.id')
                                            ->where('idstato',$stato)->get()->count();

         //       $res['data'] = DB::table('Prenotaziones')->where('idStato',$stato)->orderBy('cognome','asc')->get();
         //       $res['number'] = DB::table('Prenotaziones')->where('idStato',$stato)->get()->count();
                $res['message'] = 'trovato  prenotazioni da evadere';
                $res['Rc'] = 'Ok';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }

    public function getPrenotazionidaEvaderebyday($day)

    {

       $stato = 0;
       $res = [
            'data' =>[],
            'message' => 'Nessuna Prenotazione da Evadere per la data selezionata',
            'number' => 0,
            'Rc' => 'KO'
                ];
            try{

        //        $res['data'] = DB::table('Prenotaziones')->where('idStato',$stato)->where('idgiornata',$day)->orderBy('cognome','asc')->get();
        //        $res['number'] = DB::table('Prenotaziones')->where('idStato',$stato)->where('idgiornata',$day)->get()->count();

                $res['data'] = Prenotazione::select('prenotaziones.*', 'T_stato_prenotaziones.d_stato_prenotazione')
                                            ->join('T_stato_prenotaziones', 'prenotaziones.idstato', '=', 'T_stato_prenotaziones.id')
                                            ->where('idstato',$stato)->where('idgiornata',$day)->orderBy('cognome','asc')->get();
                $res['number'] = Prenotazione::select('prenotaziones.*', 'T_stato_prenotaziones.d_stato_prenotazione')
                                              ->join('T_stato_prenotaziones', 'prenotaziones.idstato', '=', 'T_stato_prenotaziones.id')
                                              ->where('idstato',$stato)->where('idgiornata',$day)->get()->count();

                $res['message'] = 'trovato  prenotazioni da evadere';
                $res['Rc'] = 'OK';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }



    public function getPrenotazionibyStato($stato)

    {

        $res = [
            'data' =>[],
            'message' => 'Nessuna Prenotazione da Evadere',
            'number' => 0,
            'Rc' => 'ko'
                ];
            try{
               // $res['data'] = DB::table('Commandas')->orderBy('id', 'DESC')->where('id', '<', $last)->get();

               $res['data'] = Prenotazione::select('prenotaziones.*', 'T_stato_prenotaziones.d_stato_prenotazione')
                                            ->join('T_stato_prenotaziones', 'prenotaziones.idstato', '=', 'T_stato_prenotaziones.id')
                                            ->where('idstato', '=', $stato)
                                            ->orderBy('cognome','asc')->get();
               $res['number'] = Prenotazione::select('prenotaziones.*', 'T_stato_prenotaziones.d_stato_prenotazione')
                                            ->join('T_stato_prenotaziones', 'prenotaziones.idstato', '=', 'T_stato_prenotaziones.id')
                                            ->where('idstato', '=', $stato)
                                            ->orderBy('cognome','asc')->get()->count();

         //       $res['data'] = DB::table('Prenotaziones')->where('idStato',$stato)->orderBy('cognome','asc')->get();
         //       $res['number'] = DB::table('Prenotaziones')->where('idStato',$stato)->get()->count();
                $res['message'] = 'trovato  prenotazioni filtrate';
                $res['Rc'] = 'Ok';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }

    public function getPrenotazionibyemail($email)

    {

        $res = [
            'data' =>[],
            'message' => 'Nessuna Prenotazione da Evadere per la email selezionata',
            'number' => 0,
            'Rc' => 'ko'
                ];
            try{
               // $res['data'] = DB::table('Commandas')->orderBy('id', 'DESC')->where('id', '<', $last)->get();

               $res['data'] = Prenotazione::select('prenotaziones.*', 'T_stato_prenotaziones.d_stato_prenotazione')
                                            ->join('T_stato_prenotaziones', 'prenotaziones.idstato', '=', 'T_stato_prenotaziones.id')
                                            ->where('email', '=', $email)
                                            ->orderBy('cognome','asc')->get();
               $res['number'] = Prenotazione::select('prenotaziones.*', 'T_stato_prenotaziones.d_stato_prenotazione')
                                            ->join('T_stato_prenotaziones', 'prenotaziones.idstato', '=', 'T_stato_prenotaziones.id')
                                            ->where('email', '=', $email)
                                            ->orderBy('cognome','asc')->get()->count();

         //       $res['data'] = DB::table('Prenotaziones')->where('idStato',$stato)->orderBy('cognome','asc')->get();
         //       $res['number'] = DB::table('Prenotaziones')->where('idStato',$stato)->get()->count();
                $res['message'] = 'trovato  prenotazioni per email selezionata  xx';
                $res['Rc'] = 'Ok';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }



}
