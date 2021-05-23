<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manifestazione;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ManifestazioneController extends Controller
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
                $res['data'] = DB::table('Manifestaziones')
                                ->join('T_Stato_Manifestaziones', 'Manifestaziones.statoManifestazione', '=', 'T_Stato_Manifestaziones.id')
                                ->select('Manifestaziones.*', 'T_Stato_Manifestaziones.d_stato_manifestazione')
                                ->get();


                $res['number'] = Manifestazione::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Messe';
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
            $User = new Manifestazione();
            $success = true;
            $postData = $request->except('_method', 'd_stato_manifestazione', 'dtInizio', 'dtFine');
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
               $res['message'] = 'trovato Manifestazione';
               $res['data'] =  Manifestazione::select('Manifestaziones.*', 'T_Stato_Manifestaziones.d_stato_manifestazione')
                                               ->join('T_Stato_Manifestaziones', 'T_Stato_Manifestaziones.id', '=', 'Manifestaziones.statoManifestazione')
                                               ->findOrFail($id);
             } catch (\Exception $e){
                $res['message'] =  'Manifestazione  Inesistente !! per id selezionato ';            // $e->getMessage();
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
            $User = Manifestazione::findOrFail($id);
            $success = true;
            // salva sulla variabile data i dati dalla richiesta (request)
            // ad eccezzione del campo id e del campo di comodo _method

            $postData = $request->except('id','_method', 'd_stato_manifestazione');
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
        $rc = 'KO';
        try {
            $User = Manifestazione::findOrFail($id);
            $data = $User;
            $success = $User->delete();
            $rc = 'OK';
        } catch (\Exception $e) {
            $success = false;
            $message = 'Manifestazione non trovata - Cancellazione non possibile';
        }
        return compact('data','message','success', 'rc');
    }


    public function getManifestazioneLastId(Request $request)

    {

       $last = 9999;
       $res = [
            'data' =>[],
            'number' => 0,
            'message' => 'Non effettuata regolare lettura last id',
            'Rc' => 'ko'
                ];
            try{
               // $res['data'] = DB::table('Commandas')->orderBy('id', 'DESC')->where('id', '<', $last)->get();
                $res['data'] = DB::table('Manifestaziones')->orderBy('id','desc')->first();

                $res['message'] = 'trovato  id dell Ultima  Manifestazione';
                $res['number'] = Manifestazione::All()->count();
                $res['Rc'] = 'Ok';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }


    public function getManifestazionebyStato($stato)
    {

        $res = [
            'data' =>[],
            'number' => 0,
            'message' => ''
                ];
            try{

                $res['data'] =  Manifestazione::select('Manifestaziones.*', 'T_Stato_Manifestaziones.d_stato_manifestazione')
                                                ->join('T_Stato_Manifestaziones', 'T_Stato_Manifestaziones.id', '=', 'Manifestaziones.statoManifestazione')
                                                ->where('statoManifestazione', '=', $stato)->get();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                $res['number'] = Manifestazione::where('statoManifestazione', '=', $stato)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Manifestazioni per stato filtrato';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
   }

   public function getManifestazioneActive()
   {

       $stato = 1;   // Imposto lo stato di attivo per determinare la manifestaione attiva
       $res = [
           'data' =>[],
           'number' => 0,
           'message' => 'Nessuna Manifestazione Attiva',
           'rc' => 'KO'
               ];
           try{

               $res['data'] =  Manifestazione::select('Manifestaziones.*', 'T_Stato_Manifestaziones.d_stato_manifestazione')
                                               ->join('T_Stato_Manifestaziones', 'T_Stato_Manifestaziones.id', '=', 'Manifestaziones.statoManifestazione')
                                               ->where('statoManifestazione', '=', $stato)->first();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
               $res['number'] = Manifestazione::where('statoManifestazione', '=', $stato)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
               $res['message'] = 'trovato Manifestazioni per stato filtrato';
               $res['rc'] = 'OK';
           } catch (\Exception $e){
               $res['message'] = $e->getMessage();
           }
           return $res;
  }




}
