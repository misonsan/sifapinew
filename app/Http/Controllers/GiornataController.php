<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Giornata;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GiornataController extends Controller
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
                $res['data'] = DB::table('Giornatas')
                                  ->join('T_Stato_Cassas', 'Giornatas.statoCassa', '=', 'T_Stato_Cassas.id')
                                  ->join('T_Stato_Giornatas', 'Giornatas.stato', '=', 'T_Stato_Giornatas.id')
                                  ->join('T_Stato_Magazzinos', 'Giornatas.statoMagazzino', '=', 'T_Stato_Magazzinos.id')
                                  ->join('T_Stato_Utentis', 'Giornatas.statoUtenti', '=', 'T_Stato_Utentis.id')
                                  ->join('T_Operation_Cassas', 'Giornatas.operationCassa', '=', 'T_Operation_Cassas.id')
                                  ->select('Giornatas.*', 'T_Stato_Cassas.d_stato_cassa', 'T_Stato_giornatas.d_stato_giornata', 'T_Stato_Magazzinos.d_stato_magazzino', 'T_Stato_Utentis.d_stato_utenti', 'T_Operation_Cassas.d_operation_cassa')
                                  ->get();

                $res['number'] = Giornata::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Giornte';
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
            $User = new Giornata();
            $success = true;
            $postData = $request->except('_method', 'd_stato_utenti', 'd_operation_cassa', 'd_stato_cassa', 'd_stato_giornata', 'd_stato_magazzino');
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
                   $res['message'] = 'trovato Giornata';
                   $res['data'] =  Giornata::select('Giornatas.*', 'T_Stato_Cassas.d_stato_cassa', 'T_Stato_giornatas.d_stato_giornata', 'T_Stato_Magazzinos.d_stato_magazzino', 'T_Stato_Utentis.d_stato_utenti', 'T_Operation_Cassas.d_operation_cassa')
                                                   ->join('T_Stato_Cassas', 'Giornatas.statoCassa', '=', 'T_Stato_Cassas.id')
                                                   ->join('T_Stato_Giornatas', 'Giornatas.stato', '=', 'T_Stato_Giornatas.id')
                                                   ->join('T_Stato_Magazzinos', 'Giornatas.statoMagazzino', '=', 'T_Stato_Magazzinos.id')
                                                   ->join('T_Stato_Utentis', 'Giornatas.statoUtenti', '=', 'T_Stato_Utentis.id')
                                                   ->join('T_Operation_Cassas', 'Giornatas.operationCassa', '=', 'T_Operation_Cassas.id')
                                                   ->findOrFail($id);
                 } catch (\Exception $e){
                    $res['message'] =  'Giornata  Inesistente !! per id selezionato ';            // $e->getMessage();
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
            $User = Giornata::findOrFail($id);
            $success = true;
            // salva sulla variabile data i dati dalla richiesta (request)
            // ad eccezzione del campo id e del campo di comodo _method

            $postData = $request->except('id','_method', 'd_stato_utenti', 'd_operation_cassa', 'd_stato_cassa', 'd_stato_giornata', 'd_stato_magazzino');
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
            $User = Giornata::findOrFail($id);
            $data = $User;
            $success = $User->delete();
            $rc = 'OK';
        } catch (\Exception $e) {
            $success = false;
            $message = 'Giornata non trovata - Cancellazione non possibile';
        }
        return compact('data','message','success', 'rc');
    }

       //   metodi di Moreno per gestire le giornate della manifestazione



       public function getGiornateByManifId($id)
       {

            $res = [
               'data' =>[],
               'number' => 0,
               'message' => 'Devo effettuare la ricerca delle giornate'
                   ];
               try{
                   $res['data'] = Giornata::select('Giornatas.*', 'T_Stato_Cassas.d_stato_cassa', 'T_Stato_giornatas.d_stato_giornata', 'T_Stato_Magazzinos.d_stato_magazzino', 'T_Stato_Utentis.d_stato_utenti', 'T_Operation_Cassas.d_operation_cassa')
                                           ->join('T_Stato_Cassas', 'Giornatas.statoCassa', '=', 'T_Stato_Cassas.id')
                                           ->join('T_Stato_Giornatas', 'Giornatas.stato', '=', 'T_Stato_Giornatas.id')
                                           ->join('T_Stato_Magazzinos', 'Giornatas.statoMagazzino', '=', 'T_Stato_Magazzinos.id')
                                           ->join('T_Stato_Utentis', 'Giornatas.statoUtenti', '=', 'T_Stato_Utentis.id')
                                           ->join('T_Operation_Cassas', 'Giornatas.operationCassa', '=', 'T_Operation_Cassas.id')
                                           ->where('idManifestazione',$id)->get();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                   $res['number'] = Giornata::where('idManifestazione',$id)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                   $res['message'] = 'trovato Giornate per Manifestazione filtrata';
               } catch (\Exception $e){
                   $res['message'] = $e->getMessage();
               }
               return $res;

      }

       public function getGiornateByManifIdFiltrato($id, $tipo)
       {

           $res = [
               'data' =>[],
               'number' => 0,
               'message' => ''
                   ];
               try{
                   $res['data'] = Giornata::select('Giornatas.*', 'T_Stato_Cassas.d_stato_cassa', 'T_Stato_giornatas.d_stato_giornata', 'T_Stato_Magazzinos.d_stato_magazzino', 'T_Stato_Utentis.d_stato_utenti', 'T_Operation_Cassas.d_operation_cassa')
                                            ->join('T_Stato_Cassas', 'Giornatas.statoCassa', '=', 'T_Stato_Cassas.id')
                                            ->join('T_Stato_Giornatas', 'Giornatas.stato', '=', 'T_Stato_Giornatas.id')
                                            ->join('T_Stato_Magazzinos', 'Giornatas.statoMagazzino', '=', 'T_Stato_Magazzinos.id')
                                            ->join('T_Stato_Utentis', 'Giornatas.statoUtenti', '=', 'T_Stato_Utentis.id')
                                            ->join('T_Operation_Cassas', 'Giornatas.operationCassa', '=', 'T_Operation_Cassas.id')
                                           ->where('idManifestazione',$id)->where('stato', '=', $tipo)->get();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                   $res['number'] = Giornata::where('idManifestazione',$id)->where('stato', '=', $tipo)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                   $res['message'] = 'trovato Giornate per Manifestazione filtrata';
               } catch (\Exception $e){
                   $res['message'] = $e->getMessage();
               }
               return $res;

      }

      public function getGiornataactive()
      {
          $day = now();
          $stato = 2;
          $res = [
              'data' =>[],
              'number' => 0,
              'message' => ''
                  ];
              try{
                /*
                $res['data'] = Giornata::select('Giornatas.*', 'T_Stato_Cassa.d_stato_cassa', 'T_Stato_giornata.d_stato_giornata', 'T_Stato_Magazzino.d_stato_magazzino', 'T_Stato_Utenti.d_stato_utenti', 'T_Operation_Cassa.d_operation_cassa')
                                          ->join('T_Stato_Cassa', 'Giornatas.statoCassa', '=', 'T_Stato_Cassa.id')
                                          ->join('T_Stato_Giornata', 'Giornatas.stato', '=', 'T_Stato_Giornata.id')
                                          ->join('T_Stato_Magazzino', 'Giornatas.statoMagazzino', '=', 'T_Stato_Magazzino.id')
                                          ->join('T_Stato_Utenti', 'Giornatas.statoUtenti', '=', 'T_Stato_Utenti.id')
                                          ->join('T_Operation_Cassa', 'Giornatas.operationCassa', '=', 'T_Operation_Cassa.id')
                                          ->where('stato','=',$stato)->whereDate('dtGiornata', '=',Carbon::now())->get();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                  */
                  $res['data'] = Giornata::select('Giornatas.*')
                                           ->where('stato','=',$stato)->whereDate('dtGiornata', '=',Carbon::now())->first();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                  $res['number'] = Giornata::where('stato','=', $stato)->whereDate('dtGiornata', '=',Carbon::now())->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                  $res['message'] = 'trovato Giornata attiva';
              } catch (\Exception $e){
                  $res['message'] = $e->getMessage();
              }
              return $res;

     }

     public function getGiornataLastId(Request $request)

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
                 $res['data'] = DB::table('Giornatas')->orderBy('id','desc')->first();
 
                 $res['message'] = 'trovato  id dell Ultima  Giornata';
                 $res['number'] = Giornata::All()->count();
                 $res['Rc'] = 'Ok';
             } catch (\Exception $e){
                 $res['message'] = $e->getMessage();
             }
             return $res;
     }

     // leggo l'ultima giornata inserita per controllo su prossimo inserimento
     public function getLastGiornataByManifId($id)
     {

          $res = [
             'data' =>[],
             'number' => 0,
             'message' => 'Devo effettuare la ricerca delle giornate'
                 ];
             try{
                 $res['data'] = Giornata::select('Giornatas.*', 'T_Stato_Cassas.d_stato_cassa', 'T_Stato_giornatas.d_stato_giornata', 'T_Stato_Magazzinos.d_stato_magazzino', 'T_Stato_Utentis.d_stato_utenti', 'T_Operation_Cassas.d_operation_cassa')
                                         ->join('T_Stato_Cassas', 'Giornatas.statoCassa', '=', 'T_Stato_Cassas.id')
                                         ->join('T_Stato_Giornatas', 'Giornatas.stato', '=', 'T_Stato_Giornatas.id')
                                         ->join('T_Stato_Magazzinos', 'Giornatas.statoMagazzino', '=', 'T_Stato_Magazzinos.id')
                                         ->join('T_Stato_Utentis', 'Giornatas.statoUtenti', '=', 'T_Stato_Utentis.id')
                                         ->join('T_Operation_Cassas', 'Giornatas.operationCassa', '=', 'T_Operation_Cassas.id')
                                         ->where('idManifestazione',$id)->orderBy('id','desc')->first();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                 $res['number'] = Giornata::where('idManifestazione',$id)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                 $res['message'] = 'trovato ultima giornata inserita per Manifestazione selezionata';
             } catch (\Exception $e){
                 $res['message'] = $e->getMessage();
             }
             return $res;

    }


     
}
