<?php

namespace App\Http\Controllers;

use App\Models\Tabella_tw_dett;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Tabella_twDettController extends Controller
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
                $res['data'] = DB::table('Tabella_tw_detts')
                                  ->select('tabella_tw_detts.*')
                                  ->get();

                $res['number'] = Tabella_tw_dett::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato tabelle';
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
        $rc = 'Ko';
        try {
            $User = new Tabella_tw_dett();
            $success = true;
            $postData = $request->except('_method');  // id lo passo io e qui lo tolgo
            // $postData['password'] =  Hash::make($postData['password'] ?? 'password');     // nel caso ci siano campi da cripare
            $User->fill($postData);
            $success = $User->save();
            $data = $User;
            $message = 'Inserimento eseguito con successo !!';
            $rc = 'Ok';
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        return compact('data','message','success', 'rc');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tabella_tw_dett  $tabella_tw_dett
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
                           $res['message'] = 'trovato Tabella';
                           $res['data'] = Tabella_tw_dett::select('Tabella_tw_detts.*')
                                                            ->findOrFail($id);
                         } catch (\Exception $e){
                            $res['message'] =  'Tabella  Inesistente !! ';            // $e->getMessage();
                        }
                        return $res;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tabella_tw_dett  $tabella_tw_dett
     * @return \Illuminate\Http\Response
     */
    public function edit(Tabella_tw_dett $tabella_tw_dett)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tabella_tw_dett  $tabella_tw_dett
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
                                  //  eseguo aggiornamento della Tabella

        // inizializzo i parametri per l'aggiornamento
        $data = [];
        $message = '';
        $rc = 'KO';
        try {
            $User = Tabella_tw_dett::findOrFail($id);
            $success = true;
            // salva sulla variabile data i dati dalla richiesta (request)
            // ad eccezzione del campo id e del campo di comodo _method

            $postData = $request->except('id','_method');
            // imposto la crittografia alla password  (questo temporraneo)
            // $postData['password'] =  Hash::make($postData['password'] ?? 'password');  // bcrypt('test');   -->   non abbiamo password
            // eseguo l'aggiornamento
            $success = $User->update($postData);
            $data = $User;
            $rc = 'OK';
            $message = 'Aggiornamento eseguito con successo';

        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        return compact('data','message','success', 'rc');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tabella_tw_dett  $tabella_tw_dett
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = [];
        $message = 'Cancellazione eseguita con successo !!';
        $success = true;
        try {
            $User = Tabella_tw_dett::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'Tabella non trovata - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }




    public function getelemLastId(Request $request)

    {

       $last = 9999;
       $tappo = 'N';
       $res = [
            'data' =>[],
            'number' => 0,
            'message' => 'Non effettuata regolare lettura last id',
            'Rc' => 'ko'
                ];
            try{
               // $res['data'] = DB::table('Commandas')->orderBy('id', 'DESC')->where('id', '<', $last)->get();
                $res['data'] = DB::table('Tabella_tw_detts')->where('tappo', '=', $tappo)->orderBy('id','desc')->first();

                $res['message'] = 'trovato  id dell Ultimo elemento tabellare';
                $res['number'] = Tabella_tw_dett::All()->where('tappo', '=', $tappo)->count();
                $res['Rc'] = 'Ok';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }




}
