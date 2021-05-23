<?php

namespace App\Http\Controllers;

use App\Models\t_ruolo_web;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class T_RuoloWebController extends Controller
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
                $res['data'] = DB::table('t_ruolo_webs')
                                   ->select('t_ruolo_webs.*')
                                  ->get();

                $res['number'] = T_Ruolo_Web::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Ruoli Web';
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
            $User = new T_Ruolo_Web();
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
     * @param  \App\Models\t_ruolo_web  $t_ruolo_web
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $res = [
            'data' =>[],
            'message' => ''
                ];
          try{
           $res['message'] = 'trovato Ruolo Web';
           $res['data'] =  T_Ruolo_Web::select('t_ruolo_webs.*')
                                      ->findOrFail($id);
         } catch (\Exception $e){
            $res['message'] =  'Ruolo Web Inesistente !!! ';            // $e->getMessage();
        }
        return $res;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\t_ruolo_web  $t_ruolo_web
     * @return \Illuminate\Http\Response
     */
    public function edit(t_ruolo_web $t_ruolo_web)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\t_ruolo_web  $t_ruolo_web
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
                             //  eseguo aggiornamento dell'utente

        // inizializzo i parametri per l'aggiornamento
        $data = [];
        $message = '';
        try {
            $User = T_Ruolo_Web::findOrFail($id);
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
     * @param  \App\Models\t_ruolo_web  $t_ruolo_web
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = [];
        $message = 'Cancellazione eseguita con successo !!';
        $success = true;
        try {
            $User = T_Ruolo_Web::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'Ruolo Web non trovato - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }
}
