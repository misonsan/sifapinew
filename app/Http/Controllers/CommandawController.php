<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commandaw;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CommandawController extends Controller
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
                $res['data'] = DB::table('Commandaws')
                                  ->select('Commandaws.*', 'T_Stato_Commandas.d_stato_commanda')
                                  ->join('T_Stato_Commandas', 'Commandaws.stato', '=', 'T_Stato_Commandas.id')
                                  ->get();

                $res['number'] = Commandaw::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Commande';
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
            $User = new Commandaw();
            $success = true;
            $postData = $request->except('_method', 'd_stato_commanda', 'numProdotti', 'importoProdotti', 'importoPagato', 'resto', 'noteCommanda', 'stampaEseguita', 'created_at', 'updated_at', 'dtCommanda');   // id gestito a mano devo valorizzarlo io quindi lo tolgo dai campi esclusi
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
                           $res['message'] = 'trovato Commanda';
                           $res['data'] = Commandaw::select('Commandaws.*', 'T_Stato_Commandas.d_stato_commanda')
                                                     ->join('T_Stato_Commandas', 'Commandaws.stato', '=', 'T_Stato_Commandas.id')
                                                     ->findOrFail($id);
                         } catch (\Exception $e){
                            $res['message'] =  'Commanda  Inesistente !! ';            // $e->getMessage();
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
            $User = Commandaw::findOrFail($id);
            $success = true;
            // salva sulla variabile data i dati dalla richiesta (request)
            // ad eccezzione del campo id e del campo di comodo _method

            $postData = $request->except('id','_method',  'd_stato_commanda');
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
            $User = Commandaw::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'Commanda non trovata - Cancellazione non possibile';
        }
        return compact('data','message','success');


        /*
        {
            $sql = 'DELETE FROM commandaws where id= :id';
            return DB::delete($sql, ['id' => $id]):
       } */


    }
}
