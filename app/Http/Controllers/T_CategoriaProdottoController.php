<?php

namespace App\Http\Controllers;

use App\Models\t_categoria_prodotto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class t_categoriaprodottoController extends Controller
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
                $res['data'] = DB::table('t_categoria_prodottos')
                                   ->select('t_categoria_prodottos.*')
                                  ->get();

                $res['number'] = t_categoria_prodotto::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Categorie Prodotti';
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
            $User = new t_categoria_prodotto();
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
     * @param  \App\Models\t_categoria_prodotto  $t_Categoria_Prodotto
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $res = [
            'data' =>[],
            'message' => ''
                ];
          try{
           $res['message'] = 'trovato Categoria Prodotto';
           $res['data'] =  t_categoria_prodotto::select('t_categoria_prodottos.*')
                                                ->findOrFail($id);
         } catch (\Exception $e){
            $res['message'] =  'Categoria Prodotto Inesistente  !!! ';            // $e->getMessage();
        }
        return $res;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\t_categoria_prodotto  $t_Categoria_Prodotto
     * @return \Illuminate\Http\Response
     */
    public function edit(t_categoria_prodotto $t_Categoria_Prodotto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\t_categoria_prodotto  $t_Categoria_Prodotto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
                             //  eseguo aggiornamento dell'utente

        // inizializzo i parametri per l'aggiornamento
        $data = [];
        $message = '';
        try {
            $User = t_categoria_prodotto::findOrFail($id);
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
     * @param  \App\Models\t_categoria_prodotto  $t_Categoria_Prodotto
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = [];
        $message = 'Cancellazione eseguita con successo !!';
        $success = true;
        try {
            $User = t_categoria_prodotto::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'Categoria Prodotto non trovato - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }

    public function getCategoriaLastId(Request $request)

    {

       $last = 9999;
       $res = [
            'data' =>[],
            'message' => 'Non effettuate regolare lettura last id',
            'Rc' => 'ko'
                ];
            try{
               // $res['data'] = DB::table('Commandas')->orderBy('id', 'DESC')->where('id', '<', $last)->get();
                $res['data'] = DB::table('t_categoria_prodottos')->orderBy('id','desc')->first();
                $res['message'] = 'trovato  id dell Ultima Categoria Prodotto';
                $res['Rc'] = 'Ok';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }
}
