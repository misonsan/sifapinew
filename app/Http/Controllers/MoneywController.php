<?php

namespace App\Http\Controllers;

use App\Models\Moneyw;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
// use Carbon\Carbon;

class MoneywController extends Controller
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
                $res['data'] = DB::table('Moneyws')
                                  ->select('Moneyws.*')
                                  ->get();

                $res['number'] = Moneyw::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Cassa Moneyw';
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
        $rc = 'ko';
        try {
            $User = new Moneyw();
            $success = true;
            $postData = $request->except('_method');
            // $postData['password'] =  Hash::make($postData['password'] ?? 'password');     // nel caso ci siano campi da cripare
            $User->fill($postData);
            $success = $User->save();
            $data = $User;
            $message = 'Inserimento eseguito con successo';
            $rc = 'ok';
        } catch (\Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        return compact('data','message','success', 'rc');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Moneyw  $moneyw
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
                   $res['message'] = 'trovato Moneyw';
                   $res['data'] =  Moneyw::select('Moneyws.*')
                                         ->findOrFail($id);
                 } catch (\Exception $e){
                    $res['message'] =  'Moneyw  Inesistente !! ';            // $e->getMessage();
                }
                return $res;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Moneyw  $moneyw
     * @return \Illuminate\Http\Response
     */
    public function edit(Moneyw $moneyw)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Moneyw  $moneyw
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
                            //  eseguo aggiornamento dell'utente

        // inizializzo i parametri per l'aggiornamento
        $data = [];
        $message = '';
        try {
            $User = Moneyw::findOrFail($id);
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
     * @param  \App\Models\Moneyw  $moneyw
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = [];
        $message = 'Cancellazione eseguita con successo !!';
        $success = true;
        try {
            $User = Moneyw::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'Moneyw non trovata - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }

}
