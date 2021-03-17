<?php

namespace App\Http\Controllers;

use App\Models\Moneypayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class MoneypaymentController extends Controller
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
                $res['data'] = DB::table('Moneypayments')
                                  ->join('T_Taglias', 'Moneypayments.taglia', '=', 'T_Taglias.id')
                                  ->select('Moneypayments.*', 'T_Taglias.d_taglia')
                                  ->get();

                $res['number'] = Moneypayment::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Log Pagamenti';
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
        $Rc = 'ko';
        try {
            $User = new Moneypayment();
            $success = true;
            $postData = $request->except('id','_method', 'd_taglia');
            // $postData['password'] =  Hash::make($postData['password'] ?? 'password');     // nel caso ci siano campi da cripare
            $User->fill($postData);
            $success = $User->save();
            $data = $User;
            $message = 'Inserimento eseguito con successo';
            $Rc = 'Ok';
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        return compact('data','message','success', 'Rc');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Moneypayment  $moneypayment
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
                   $res['data'] =  Moneypayment::select('Moneypayments.*', 'T_Taglias.d_taglia')
                                                   ->join('T_Taglias', 'Moneypayments.taglia', '=', 'T_Taglias.id')
                                                   ->findOrFail($id);
                 } catch (\Exception $e){
                    $res['message'] =  'Moneypayment  Inesistente !! ';            // $e->getMessage();
                }
                return $res;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Moneypayment  $moneypayment
     * @return \Illuminate\Http\Response
     */
    public function edit(Moneypayment $moneypayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Moneypayment  $moneypayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
                            //  eseguo aggiornamento dell'utente

        // inizializzo i parametri per l'aggiornamento
        $data = [];
        $message = '';
        try {
            $User = Moneypayment::findOrFail($id);
            $success = true;
            // salva sulla variabile data i dati dalla richiesta (request)
            // ad eccezzione del campo id e del campo di comodo _method

            $postData = $request->except('id','_method', 'd_taglia');
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
     * @param  \App\Models\Moneypayment  $moneypayment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = [];
        $message = 'Cancellazione eseguita con successo !!';
        $success = true;
        try {
            $User = Moneypayment::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'Moneypayment non trovato - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }

}
