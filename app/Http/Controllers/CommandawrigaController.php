<?php

namespace App\Http\Controllers;

use App\Models\Commandawriga;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CommandawrigaController extends Controller
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
                $res['data'] = DB::table('Commandawrigas')
                                  ->select('Commandawrigas.*', 'T_Categoria_Prodottos.d_Categoria')
                                  ->join('T_Categoria_Prodottos', 'Commandawrigas.categoria', '=', 'T_Categoria_Prodottos.id')
                                  ->orderby('d_Categoria', 'asc')
                                  ->orderby('descrizione_prodotto', 'asc')
                                  ->get();

                $res['number'] = Commandawriga::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
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
            $User = new Commandawriga();
            $success = true;
            $postData = $request->except('_method', 'd_Categoria');   // id lo imposto io e quindi deve essere visibile
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
     * @param  \App\Models\Commandawriga  $commandawriga
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
                           $res['data'] = Commandawriga::select('Commandawrigas.*', 'T_Categoria_Prodottos.d_Categoria')
                                                        ->join('T_Categoria_Prodottos', 'Commandawrigas.categoria', '=', 'T_Categoria_Prodottos.id')
                                                        ->findOrFail($id);
                         } catch (\Exception $e){
                            $res['message'] =  'Commanda  Inesistente !! ';            // $e->getMessage();
                        }
                        return $res;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Commandawriga  $commandawriga
     * @return \Illuminate\Http\Response
     */
    public function edit(Commandawriga $commandawriga)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Commandawriga  $commandawriga
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
                                  //  eseguo aggiornamento dell'utente

        // inizializzo i parametri per l'aggiornamento
        $data = [];
        $message = '';
        try {
            $User = Commandawriga::findOrFail($id);
            $success = true;
            // salva sulla variabile data i dati dalla richiesta (request)
            // ad eccezzione del campo id e del campo di comodo _method

            $postData = $request->except('id','_method', 'd_categoria');
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
     * @param  \App\Models\Commandawriga  $commandawriga
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = [];
        $message = 'Cancellazione eseguita con successo !!';
        $success = true;
        try {
            $User = Commandawriga::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'riga zCommanda non trovata - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }

    public function getRigheCommandaw($id)
    {
         $res = [
            'data' =>[],
            'number' => 0,
            'message' => 'Devo effettuare la ricerca delle righe commande'
                ];
            try{
                $res['data'] = Commandawriga::select('Commandawrigas.*', 'T_Categoria_Prodottos.d_Categoria')
                                              ->join('T_Categoria_Prodottos', 'Commandawrigas.categoria', '=', 'T_Categoria_Prodottos.id')
                                              ->orderby('d_Categoria', 'asc')
                                              ->orderby('descrizione_prodotto', 'asc')
                                              ->where('idCommanda',$id)->get();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                $res['number'] = Commandawriga::where('idCommanda',$id)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato righe prodotti per commanda selezionata';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;

   }

   public function getProdottiforTipologia($tipo)
   {
       //
           $qta = 0;
           $res = [
           'data' =>[],
           'number' => 0,
           'message' => ''
               ];
           try{
           // $res['data'] = Manifestazione::all();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
           // lettura con join a tabelle correlate
               $res['data'] = DB::table('Commandawrigas')
                                ->join('T_Categoria_Prodottos', 'Commandawrigas.categoria', '=', 'T_Categoria_Prodottos.id')
                                ->select('Commandawrigas.*', 'T_Categoria_Prodottos.d_Categoria')
                                ->where('tipologia',$tipo)->where('disponibile_Day', '>', $qta)
                                ->orderby('d_Categoria','asc')
                                ->orderby('descrizione_prodotto', 'asc')
                                ->get();
               $res['number'] = Commandawriga::where('tipologia',$tipo)->where('disponibile_Day', '>', $qta)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
               $res['message'] = 'trovato Prodotti per la categoria Selezionata';
           } catch (\Exception $e){
               $res['message'] = $e->getMessage();
           }
           return $res;
   }

   public function getProdottiOrdinati($id)
   {

    $qta = 0;
    $res = [
        'data' =>[],
        'number' => 0,
        'message' => 'Devo effettuare la ricerca dei prodotti Ordinati'
            ];
        try{
            $res['data'] = Commandawriga::select('Commandawrigas.*', 'T_Categoria_Prodottos.d_Categoria')
                                          ->join('T_Categoria_Prodottos', 'Commandawrigas.categoria', '=', 'T_Categoria_Prodottos.id')
                                          ->orderby('d_Categoria', 'asc')
                                          ->orderby('descrizione_prodotto', 'asc')
                                          ->where('idCommanda',$id)->where('qta', '>', $qta)->get();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
            $res['number'] = Commandawriga::where('idCommanda',$id)->where('qta', '>', $qta)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
            $res['message'] = 'trovato righe prodotti ordinati per commanda selezionata';
        } catch (\Exception $e){
            $res['message'] = $e->getMessage();
        }
        return $res;
   }


   public function getProdottibyTipologiacomma($tipo, $id) {

      //
      $qta = 0;
      $res = [
      'data' =>[],
      'number' => 0,
      'message' => ''
          ];
      try{
      // $res['data'] = Manifestazione::all();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
      // lettura con join a tabelle correlate
          $res['data'] = DB::table('Commandawrigas')
                           ->join('T_Categoria_Prodottos', 'Commandawrigas.categoria', '=', 'T_Categoria_Prodottos.id')
                           ->select('Commandawrigas.*', 'T_Categoria_Prodottos.d_Categoria')
                           ->where('tipologia',$tipo)->where('disponibile_Day', '>', $qta)->where('idCommanda',$id)
                           ->orderby('d_Categoria','asc')
                           ->orderby('descrizione_prodotto', 'asc')
                           ->get();
          $res['number'] = Commandawriga::where('tipologia',$tipo)->where('disponibile_Day', '>', $qta)->where('idCommanda',$id)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
          $res['message'] = 'trovato Prodotti per la categoria Selezionata e fattura';
      } catch (\Exception $e){
          $res['message'] = $e->getMessage();
      }
      return $res;




   }





}
