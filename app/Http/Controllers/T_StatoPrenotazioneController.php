<?php

namespace App\Http\Controllers;

use App\Models\t_stato_prenotazione;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class T_StatoPrenotazioneController extends Controller
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
                $res['data'] = DB::table('t_stato_prenotaziones')
                                   ->select('t_stato_prenotaziones.*')
                                  ->get();

                $res['number'] = t_stato_prenotazione::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Stati Prenotazione';
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
             $User = new t_stato_prenotazione();
             $success = true;
             $postData = $request->except('id','_method');
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
     * @param  \App\Models\t_stato_prenotazione  $t_stato_prenotazione
     * @return \Illuminate\Http\Response
     */
     public function show($id)
    {
        $res = [
            'data' =>[],
            'message' => ''
                ];
          try{
           $res['message'] = 'trovato Stato Prenotazione';
           $res['data'] =  t_stato_prenotazione::select('t_stato_prenotaziones.*')
                                      ->findOrFail($id);
         } catch (\Exception $e){
            $res['message'] =  'Stato Prenotazione Inesistente  !!! ';            // $e->getMessage();
        }
        return $res;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\t_stato_prenotazione  $t_stato_prenotazione
     * @return \Illuminate\Http\Response
     */
    public function edit(t_stato_prenotazione $t_stato_prenotazione)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\t_stato_prenotazione  $t_stato_prenotazione
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request, $id)
     {
                              //  eseguo aggiornamento dell'utente
 
         // inizializzo i parametri per l'aggiornamento
         $data = [];
         $message = '';
         try {
             $User = t_stato_prenotazione::findOrFail($id);
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
     * @param  \App\Models\t_stato_prenotazione  $t_stato_prenotazione
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
     {
         $data = [];
         $message = 'Cancellazione eseguita con successo !!';
         $success = true;
         try {
             $User = t_stato_prenotazione::findOrFail($id);
             $data = $User;
             $success = $User->delete();
         } catch (\Exception $e) {
             $success = false;
             $message = 'Stato Prenotazione non trovato - Cancellazione non possibile';
         }
         return compact('data','message','success');
     }
}
