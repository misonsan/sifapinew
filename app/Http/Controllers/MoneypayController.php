<?php

namespace App\Http\Controllers;

use App\Models\Moneypay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MoneypayController extends Controller
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
                $res['data'] = DB::table('Moneypays')
                                  ->join('Commandas', 'Moneypays.idCommanda', '=', 'Commandas.id')
                                  ->join('Giornatas', 'Moneypays.idGiornata', '=', 'Giornatas.id')
                                  ->join('T_Taglias', 'Moneypays.idTaglia', '=', 'T_Taglias.id')
                                  ->select('Moneypays.*', 'T_taglias.d_taglia')
                                  ->get();

                $res['number'] = Moneypay::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Dettaglio di Pagamento';
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
            $User = new Moneypay();
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
        return compact('data','message','success');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Moneypay  $moneypay
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
                   $res['message'] = 'trovato Dettagli di pagamento';
                   $res['data'] =  Moneypay::select('Moneypays.*', 'T_taglias.d_taglia')
                                            ->join('Commandas', 'Moneypays.idCommanda', '=', 'Commandas.id')
                                            ->join('Giornatas', 'Moneypays.idGiornata', '=', 'Giornatas.id')
                                            ->join('T_Taglias', 'Moneypays.idTaglia', '=', 'T_Taglias.id')
                                            ->findOrFail($id);
                 } catch (\Exception $e){
                    $res['message'] =  'Dettaglio Pagamento Inesistente !! ';            // $e->getMessage();
                }
                return $res;
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Moneypay  $moneypay
     * @return \Illuminate\Http\Response
     */
    public function edit(Moneypay $moneypay)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Moneypay  $moneypay
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
                            //  eseguo aggiornamento dell'utente

        // inizializzo i parametri per l'aggiornamento
        $data = [];
        $message = '';
        try {
            $User = Moneypay::findOrFail($id);
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
     * @param  \App\Models\Moneypay  $moneypay
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = [];
        $message = 'Cancellazione eseguita con successo !!';
        $success = true;
        try {
            $User = Moneypay::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'Dettaglio Pagamento non trovato - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }

    public function getMoneyforCommanda($id)

    {
        $res = [
            'data' =>[],
            'number' => 0,
            'message' => ''
                ];
            try{

                $res['data'] =  Moneypay::select('Moneypays.*', 'T_taglias.d_taglia')
                                            ->join('Commandas', 'Moneypays.idCommanda', '=', 'Commandas.id')
                                            ->join('Giornatas', 'Moneypays.idGiornata', '=', 'Giornatas.id')
                                            ->join('T_Taglias', 'Moneypays.idTaglia', '=', 'T_Taglias.id')
                                            ->where('idCommanda',$id)->get();
                $res['number'] = Moneypay::where('idCommanda',$id)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato movimento denaro per commanda';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;

    }


}
