<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\t_tipologia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class t_tipologiaController extends Controller
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
                $res['data'] = DB::table('t_tipologias')
                                  ->join('t_stato_tipologias', 't_tipologias.stato', '=', 't_stato_tipologias.id')
                                  ->select('t_tipologias.*', 't_stato_tipologias.d_stato_tipologia')
                                  ->get();

             
                $res['number'] = t_tipologia::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
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
            $User = new t_tipologia();
            $success = true;
            $postData = $request->except('id','_method', 'd_stato_tipologia');
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
     * @param  \App\Models\t_tipologia  $t_Tipologia
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

                   $res['data'] =  t_tipologia::select('t_tipologias.*', 't_stato_tipologias.d_stato_tipologia')
                                              ->join('t_stato_tipologias', 't_tipologias.stato', '=', 't_stato_tipologias.id')
                                              ->findOrFail($id);
                 } catch (\Exception $e){
                    $res['message'] =  'Tipologia  Inesistente !!  ';            // $e->getMessage();
                }
                return $res;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\T_Tipologia  $t_Tipologia
     * @return \Illuminate\Http\Response
     */
    public function edit(T_Tipologia $t_Tipologia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\T_Tipologia  $t_Tipologia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
               // inizializzo i parametri per l'aggiornamento
               $data = [];
               $message = '';
               try {
                   $User = T_Tipologia::findOrFail($id);
                   $success = true;
                   // salva sulla variabile data i dati dalla richiesta (request)
                   // ad eccezzione del campo id e del campo di comodo _method

                   $postData = $request->except('id','_method', 'd_stato_tipologia');
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
     * @param  \App\Models\T_Tipologia  $t_Tipologia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = [];
        $message = 'Cancellazione eseguita con successo !!';
        $success = true;
        try {
            $User = T_Tipologia::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'Tipologia non trovata - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }

        // metodi creati da moreno

        public function getTipologieforStato($stato)
        {
            //
                $res = [
                'data' =>[],
                'number' => 0,
                'message' => ''
                    ];
                try{
                // $res['data'] = Manifestazione::all();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                // lettura con join a tabelle correlate
                $res['data'] = DB::table('t_tipologias')
                                    ->join('t_stato_tipologias', 't_tipologias.stato', '=', 't_stato_tipologias.id')
                                    ->select('t_tipologias.*', 't_stato_tipologias.d_stato_tipologia')
                                    ->where('stato',$stato)
                                    ->get();

                    $res['number'] = t_tipologia::where('stato',$stato)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                    $res['message'] = 'trovato tipologie per lo stato Selezionato';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;
        }

}
