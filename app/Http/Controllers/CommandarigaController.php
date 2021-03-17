<?php

namespace App\Http\Controllers;

use App\Models\Commandariga;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class CommandarigaController extends Controller
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
                $res['data'] = DB::table('Commandarigas')
                                  ->select('Commandarigas.*', 'T_stato_rigacommandas.d_stato_riga_commanda', 'T_Categoria_Prodottos.d_categoria', 'T_stato_lavoraziones.d_stato_lavorazione', 'T_stato_consegnas.d_stato_consegna')
                                  ->join('T_Categoria_Prodottos', 'Commandarigas.categoria', '=', 'T_Categoria_Prodottos.id')
                                  ->join('T_stato_rigacommandas', 'Commandarigas.stato', '=', 'T_stato_rigacommandas.id')
                                  ->join('T_stato_lavoraziones', 'Commandarigas.flag_lavorazione', '=', 'T_stato_lavoraziones.id')
                                  ->join('T_stato_consegnas', 'Commandarigas.flag_consegna', '=', 'T_stato_consegnas.id')
                                  ->orderby('d_Categoria', 'asc')
                                  ->orderby('descrizione_prodotto', 'asc')
                                  ->get();

                $res['number'] = Commandariga::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato righe commanda';
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
            $User = new Commandariga();
            $success = true;
            $postData = $request->except('_method', 'd_Categoria', 'd_stato_riga_commanda', 'd_stato_lavorazione', 'd_stato_consegna');   // id lo imposto io e quindi deve essere visibile
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
     * @param  \App\Models\CommandaRiga  $commandaRiga
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
                           $res['data'] = Commandariga::select('Commandarigas.*', 'T_stato_rigacommandas.d_stato_riga_commanda', 'T_Categoria_Prodottos.d_categoria', 'T_stato_lavoraziones.d_stato_lavorazione', 'T_stato_consegnas.d_stato_consegna')
                                                        ->join('T_Categoria_Prodottos', 'Commandarigas.categoria', '=', 'T_Categoria_Prodottos.id')
                                                        ->join('T_stato_rigacommandas', 'Commandarigas.stato', '=', 'T_stato_rigacommandas.id')
                                                        ->join('T_stato_lavoraziones', 'Commandarigas.flag_lavorazione', '=', 'T_stato_lavoraziones.id')
                                                        ->join('T_stato_consegnas', 'Commandarigas.flag_consegna', '=', 'T_stato_consegnas.id')
                                                        ->findOrFail($id);
                         } catch (\Exception $e){
                            $res['message'] =  'Commanda  Inesistente !! ';            // $e->getMessage();
                        }
                        return $res;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CommandaRiga  $commandaRiga
     * @return \Illuminate\Http\Response
     */
    public function edit(CommandaRiga $commandaRiga)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CommandaRiga  $commandaRiga
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
                                  //  eseguo aggiornamento dell'utente

        // inizializzo i parametri per l'aggiornamento
        $data = [];
        $message = '';
        try {
            $User = Commandariga::findOrFail($id);
            $success = true;
            // salva sulla variabile data i dati dalla richiesta (request)
            // ad eccezzione del campo id e del campo di comodo _method

            $postData = $request->except('_method', 'd_categoria', 'd_stato_riga_commanda', 'd_stato_lavorazione', 'd_stato_consegna');
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
     * @param  \App\Models\CommandaRiga  $commandaRiga
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = [];
        $message = 'Cancellazione eseguita con successo !!';
        $success = true;
        try {
            $User = Commandariga::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'riga Commanda non trovata - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }

    public function getLastCommandarigaid(Request $request)  {

        $last = 9999;
        $res = [
             'data' =>[],
             'message' => 'Non effettuate rgolare lettura last id'
                 ];
             try{
                // $res['data'] = DB::table('Commandas')->orderBy('id', 'DESC')->where('id', '<', $last)->get();
                 $res['data'] = DB::table('Commandarigas')->orderBy('id','desc')->first();
                 $res['message'] = 'trovato  id dell Ultima  riga Commanda';
             } catch (\Exception $e){
                 $res['message'] = $e->getMessage();
             }
             return $res;

    }

    public function getProdottiforCommanda($id)

    {
        $res = [
            'data' =>[],
            'number' => 0,
            'message' => ''
                ];
            try{
                $res['data'] = Commandariga::select('Commandarigas.*', 'T_stato_rigacommandas.d_stato_riga_commanda', 'T_Categoria_Prodottos.d_categoria', 'T_stato_lavoraziones.d_stato_lavorazione', 'T_stato_consegnas.d_stato_consegna')
                                            ->join('T_Categoria_Prodottos', 'Commandarigas.categoria', '=', 'T_Categoria_Prodottos.id')
                                            ->join('T_stato_rigacommandas', 'Commandarigas.stato', '=', 'T_stato_rigacommandas.id')
                                            ->join('T_stato_lavoraziones', 'Commandarigas.flag_lavorazione', '=', 'T_stato_lavoraziones.id')
                                            ->join('T_stato_consegnas', 'Commandarigas.flag_consegna', '=', 'T_stato_consegnas.id')
                                            ->where('idCommanda',$id)
                                            ->orderby('idCommanda', 'asc')
                                            ->orderby('d_Categoria', 'asc')
                                            ->orderby('descrizione_prodotto', 'asc')
                                            ->get();

                $res['number'] = Commandariga::where('idCommanda',$id)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Prodotti ordinati per commanda';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;

    }

    public function getProdottidaLavorare($comp,$flag)

    {
        $res = [
            'data' =>[],
            'number' => 0,
            'message' => ''
                ];
            try{
                $res['data'] = Commandariga::select('Commandarigas.*', 'T_stato_rigacommandas.d_stato_riga_commanda', 'T_Categoria_Prodottos.d_categoria', 'T_stato_lavoraziones.d_stato_lavorazione', 'T_stato_consegnas.d_stato_consegna')
                                            ->join('T_Categoria_Prodottos', 'Commandarigas.categoria', '=', 'T_Categoria_Prodottos.id')
                                            ->join('T_stato_rigacommandas', 'Commandarigas.stato', '=', 'T_stato_rigacommandas.id')
                                            ->join('T_stato_lavoraziones', 'Commandarigas.flag_lavorazione', '=', 'T_stato_lavoraziones.id')
                                            ->join('T_stato_consegnas', 'Commandarigas.flag_consegna', '=', 'T_stato_consegnas.id')
                                            ->where('competenza', '=', $comp)->where('flag_lavorazione', '=', $flag)
                                            ->orderby('idCommanda', 'asc')
                                            ->orderby('d_Categoria', 'asc')
                                            ->orderby('descrizione_prodotto', 'asc')
                                            ->get();

                $res['number'] = Commandariga::where('competenza', '=', $comp)->where('flag_lavorazione', '=', $flag)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Prodotti da lavorare';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;

    }
   

    public function getProdottiCucinadaConsegnare($comp,$flagL,$flagC)

    {
        $res = [
            'data' =>[],
            'number' => 0,
            'message' => ''
                ];
            try{
                $res['data'] = Commandariga::select('Commandarigas.*', 'T_stato_rigacommandas.d_stato_riga_commanda', 'T_Categoria_Prodottos.d_categoria', 'T_stato_lavoraziones.d_stato_lavorazione', 'T_stato_consegnas.d_stato_consegna')
                                            ->join('T_Categoria_Prodottos', 'Commandarigas.categoria', '=', 'T_Categoria_Prodottos.id')
                                            ->join('T_stato_rigacommandas', 'Commandarigas.stato', '=', 'T_stato_rigacommandas.id')
                                            ->join('T_stato_lavoraziones', 'Commandarigas.flag_lavorazione', '=', 'T_stato_lavoraziones.id')
                                            ->join('T_stato_consegnas', 'Commandarigas.flag_consegna', '=', 'T_stato_consegnas.id')
                                            ->where('competenza', '=', $comp)->where('flag_lavorazione', '=', $flagL)->where('flag_consegna', '=', $flagC)
                                            ->orderby('idCommanda', 'asc')
                                            ->orderby('d_Categoria', 'asc')
                                            ->orderby('descrizione_prodotto', 'asc')
                                            ->get();

                $res['number'] = Commandariga::where('competenza', '=', $comp)->where('flag_lavorazione', '=', $flagL)->where('flag_consegna', '=', $flagC)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Prodotti Cucina da Consegnare';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;

    }


    public function getProdottiBevandedaConsegnare($comp,$flagC)

    {
        $res = [
            'data' =>[],
            'number' => 0,
            'message' => ''
                ];
            try{
                $res['data'] = Commandariga::select('Commandarigas.*', 'T_stato_rigacommandas.d_stato_riga_commanda', 'T_Categoria_Prodottos.d_categoria', 'T_stato_lavoraziones.d_stato_lavorazione', 'T_stato_consegnas.d_stato_consegna')
                                            ->join('T_Categoria_Prodottos', 'Commandarigas.categoria', '=', 'T_Categoria_Prodottos.id')
                                            ->join('T_stato_rigacommandas', 'Commandarigas.stato', '=', 'T_stato_rigacommandas.id')
                                            ->join('T_stato_lavoraziones', 'Commandarigas.flag_lavorazione', '=', 'T_stato_lavoraziones.id')
                                            ->join('T_stato_consegnas', 'Commandarigas.flag_consegna', '=', 'T_stato_consegnas.id')
                                            ->where('competenza', '=', $comp)->where('flag_consegna', '=', $flagC)
                                            ->orderby('idCommanda', 'asc')
                                            ->orderby('d_Categoria', 'asc')
                                            ->orderby('descrizione_prodotto', 'asc')
                                            ->get();

                $res['number'] = Commandariga::where('competenza', '=', $comp)->where('flag_consegna', '=', $flagC)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Prodotti Bevande da Consegnare';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;

    }
   

    public function getProdottiConsegnati($comp,$flagC)

    {
        $res = [
            'data' =>[],
            'number' => 0,
            'message' => ''
                ];
            try{
                $res['data'] = Commandariga::select('Commandarigas.*', 'T_stato_rigacommandas.d_stato_riga_commanda', 'T_Categoria_Prodottos.d_categoria', 'T_stato_lavoraziones.d_stato_lavorazione', 'T_stato_consegnas.d_stato_consegna')
                                            ->join('T_Categoria_Prodottos', 'Commandarigas.categoria', '=', 'T_Categoria_Prodottos.id')
                                            ->join('T_stato_rigacommandas', 'Commandarigas.stato', '=', 'T_stato_rigacommandas.id')
                                            ->join('T_stato_lavoraziones', 'Commandarigas.flag_lavorazione', '=', 'T_stato_lavoraziones.id')
                                            ->join('T_stato_consegnas', 'Commandarigas.flag_consegna', '=', 'T_stato_consegnas.id')
                                            ->where('competenza', '=', $comp)->where('flag_consegna', '=', $flagC)
                                            ->orderby('idCommanda', 'asc')
                                            ->orderby('d_Categoria', 'asc')
                                            ->orderby('descrizione_prodotto', 'asc')
                                            ->get();

                $res['number'] = Commandariga::where('competenza', '=', $comp)->where('flag_consegna', '=', $flagC)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Prodotti Consegnati';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;

    }
 
    public function getAllProdotti($comp)

    {
        $res = [
            'data' =>[],
            'number' => 0,
            'message' => ''
                ];
            try{
                $res['data'] = Commandariga::select('Commandarigas.*', 'T_stato_rigacommandas.d_stato_riga_commanda', 'T_Categoria_Prodottos.d_categoria', 'T_stato_lavoraziones.d_stato_lavorazione', 'T_stato_consegnas.d_stato_consegna')
                                            ->join('T_Categoria_Prodottos', 'Commandarigas.categoria', '=', 'T_Categoria_Prodottos.id')
                                            ->join('T_stato_rigacommandas', 'Commandarigas.stato', '=', 'T_stato_rigacommandas.id')
                                            ->join('T_stato_lavoraziones', 'Commandarigas.flag_lavorazione', '=', 'T_stato_lavoraziones.id')
                                            ->join('T_stato_consegnas', 'Commandarigas.flag_consegna', '=', 'T_stato_consegnas.id')
                                            ->where('competenza', '=', $comp)
                                            ->orderby('idCommanda', 'asc')
                                            ->orderby('d_Categoria', 'asc')
                                            ->orderby('descrizione_prodotto', 'asc')
                                            ->get();

                $res['number'] = Commandariga::where('competenza', '=', $comp)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Prodotti Consegnati';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;

    }

    public function getAllProdottiAct($comp)

    {
        $stato = 0;
        $res = [
            'data' =>[],
            'number' => 0,
            'message' => ''
                ];
            try{
                $res['data'] = Commandariga::select('Commandarigas.*', 'T_stato_rigacommandas.d_stato_riga_commanda', 'T_Categoria_Prodottos.d_categoria', 'T_stato_lavoraziones.d_stato_lavorazione', 'T_stato_consegnas.d_stato_consegna')
                                            ->join('T_Categoria_Prodottos', 'Commandarigas.categoria', '=', 'T_Categoria_Prodottos.id')
                                            ->join('T_stato_rigacommandas', 'Commandarigas.stato', '=', 'T_stato_rigacommandas.id')
                                            ->join('T_stato_lavoraziones', 'Commandarigas.flag_lavorazione', '=', 'T_stato_lavoraziones.id')
                                            ->join('T_stato_consegnas', 'Commandarigas.flag_consegna', '=', 'T_stato_consegnas.id')
                                            ->where('competenza', '=', $comp)->where('stato', '=', $stato)
                                            ->orderby('idCommanda', 'asc')
                                            ->orderby('d_Categoria', 'asc')
                                            ->orderby('descrizione_prodotto', 'asc')
                                            ->get();

                $res['number'] = Commandariga::where('competenza', '=', $comp)->where('stato', '=', $stato)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Prodotti Consegnati Attivi';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;

    }



}
