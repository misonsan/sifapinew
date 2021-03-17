<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commanda;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CommandaController extends Controller
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
                $res['data'] = DB::table('Commandas')
                                  ->join('T_Stato_Bevandes', 'Commandas.statoBevande', '=', 'T_Stato_Bevandes.id')
                                  ->join('T_Stato_Commandas', 'Commandas.stato', '=', 'T_Stato_Commandas.id')
                                  ->join('T_Stato_Contabiles', 'Commandas.statoContabile', '=', 'T_Stato_Contabiles.id')
                                  ->join('T_Stato_Cucinas', 'Commandas.statoCucina', '=', 'T_Stato_Cucinas.id')
                                  ->select('Commandas.*', 'T_Stato_Bevandes.d_stato_bevande', 'T_Stato_Commandas.d_stato_commanda', 'T_Stato_Contabiles.d_stato_Contabile', 'T_Stato_Cucinas.d_stato_Cucina')
                                  ->get();

                $res['number'] = Commanda::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
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
            $User = new Commanda();
            $success = true;
            $postData = $request->except('_method', 'd_stato_bevande', 'd_stato_commanda', 'd_stato_Contabile', 'd_stato_Cucina');  // id lo passo io e qui lo tolgo
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
                           $res['message'] = 'trovato Giornata';
                           $res['data'] = Commanda::select('Commandas.*', 'T_Stato_Bevandes.d_stato_bevande', 'T_Stato_Commandas.d_stato_commanda', 'T_Stato_Contabiles.d_stato_Contabile', 'T_Stato_Cucinas.d_stato_Cucina')
                                                           ->join('T_Stato_Bevandes', 'Commandas.statoBevande', '=', 'T_Stato_Bevandes.id')
                                                           ->join('T_Stato_Commandas', 'Commandas.stato', '=', 'T_Stato_Commandas.id')
                                                           ->join('T_Stato_Contabiles', 'Commandas.statoContabile', '=', 'T_Stato_Contabiles.id')
                                                           ->join('T_Stato_Cucinas', 'Commandas.statoCucina', '=', 'T_Stato_Cucinas.id')
                                                           ->findOrFail($id);
                         } catch (\Exception $e){
                            $res['message'] =  'Commanda  Inesistente !! per id selezionato ';            // $e->getMessage();
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
            $User = Commanda::findOrFail($id);
            $success = true;
            // salva sulla variabile data i dati dalla richiesta (request)
            // ad eccezzione del campo id e del campo di comodo _method

            $postData = $request->except('id','_method', 'd_stato_bevande', 'd_stato_commanda', 'd_stato_Contabile', 'd_stato_Cucina');
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
            $User = Commanda::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'Commanda non trovata - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }

    //   metodi di Moreno per gestire le giornate della manifestazione



        public function getCommandeByGiornataId($id)
        {

            $res = [
                'data' =>[],
                'number' => 0,
                'message' => 'Devo effettuare la ricerca delle giornate'
                    ];
                try{
                    $res['data'] = Commanda::select('Commandas.*', 'T_Stato_Bevandes.d_stato_bevande', 'T_Stato_Commandas.d_stato_commanda', 'T_Stato_Contabiles.d_stato_Contabile', 'T_Stato_Cucinas.d_stato_Cucina')
                                            ->join('T_Stato_Bevandes', 'Commandas.statoBevande', '=', 'T_Stato_Bevandes.id')
                                            ->join('T_Stato_Commandas', 'Commandas.stato', '=', 'T_Stato_Commandas.id')
                                            ->join('T_Stato_Contabiles', 'Commandas.statoContabile', '=', 'T_Stato_Contabiles.id')
                                            ->join('T_Stato_Cucinas', 'Commandas.statoCucina', '=', 'T_Stato_Cucinas.id')
                                            ->where('idGiornata',$id)->get();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                    $res['number'] = Commanda::where('idGiornata',$id)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                    $res['message'] = 'trovato Commande per Giornata filtrata';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;

        }

        public function getCommandeByGiornataIdFiltrato($id, $tipo)

        {

            $res = [
                'data' =>[],
                'number' => 0,
                'message' => ''
                    ];
                try{
                    $res['data'] = Commanda::select('Commandas.*', 'T_Stato_Bevandes.d_stato_bevande', 'T_Stato_Commandas.d_stato_commanda', 'T_Stato_Contabiles.d_stato_Contabile', 'T_Stato_Cucinas.d_stato_Cucina')
                                            ->join('T_Stato_Bevandes', 'Commandas.statoBevande', '=', 'T_Stato_Bevandes.id')
                                            ->join('T_Stato_Commandas', 'Commandas.stato', '=', 'T_Stato_Commandas.id')
                                            ->join('T_Stato_Contabiles', 'Commandas.statoContabile', '=', 'T_Stato_Contabiles.id')
                                            ->join('T_Stato_Cucinas', 'Commandas.statoCucina', '=', 'T_Stato_Cucinas.id')
                                            ->where('idGiornata',$id)->where('commandas.stato', '=', $tipo)->get();

                    $res['number'] = Commanda::where('idGiornata',$id)->where('commandas.stato', '=', $tipo)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                    $res['message'] = 'trovato Commande per Giornata filtrata filtrata';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;

        }

        public function getCommandeLastId(Request $request)

        {

           $last = 9999;
           $res = [
                'data' =>[],
                'message' => 'Non effettuate rgolare lettura last id',
                'Rc' => 'ko'
                    ];
                try{
                   // $res['data'] = DB::table('Commandas')->orderBy('id', 'DESC')->where('id', '<', $last)->get();
                    $res['data'] = DB::table('Commandas')->orderBy('id','desc')->first();
                    $res['message'] = 'trovato  id dell Ultima  Commande';
                    $res['Rc'] = 'Ok';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;
        }

        public function getCommandeByGiornataeCompetenza($id, $competenza)

        {
            $stato = 4;
            $res = [
                'data' =>[],
                'number' => 0,
                'message' => ''
                    ];
                try{
                    $res['data'] = Commanda::select('Commandas.*', 'T_Stato_Bevandes.d_stato_bevande', 'T_Stato_Commandas.d_stato_commanda', 'T_Stato_Contabiles.d_stato_Contabile', 'T_Stato_Cucinas.d_stato_Cucina')
                                            ->join('T_Stato_Bevandes', 'Commandas.statoBevande', '=', 'T_Stato_Bevandes.id')
                                            ->join('T_Stato_Commandas', 'Commandas.stato', '=', 'T_Stato_Commandas.id')
                                            ->join('T_Stato_Contabiles', 'Commandas.statoContabile', '=', 'T_Stato_Contabiles.id')
                                            ->join('T_Stato_Cucinas', 'Commandas.statoCucina', '=', 'T_Stato_Cucinas.id')
                                            ->join('commandarigas', 'Commandas.id', '=', 'commandarigas.idCommanda')
                                            ->where('idGiornata',$id)->where('competenza', '=', $competenza)->where('commandas.stato', '!=', $stato)->distinct()->get(['competenza']);

                 //   $res['number'] = Commanda::where('idGiornata',$id)->where('competenza', '=', $competenza)->distinct()->get(['competenza'])->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();

                    $res['number'] = Commanda::select('Commandas.*', 'T_Stato_Bevandes.d_stato_bevande', 'T_Stato_Commandas.d_stato_commanda', 'T_Stato_Contabiles.d_stato_Contabile', 'T_Stato_Cucinas.d_stato_Cucina')
                                            ->join('T_Stato_Bevandes', 'Commandas.statoBevande', '=', 'T_Stato_Bevandes.id')
                                            ->join('T_Stato_Commandas', 'Commandas.stato', '=', 'T_Stato_Commandas.id')
                                            ->join('T_Stato_Contabiles', 'Commandas.statoContabile', '=', 'T_Stato_Contabiles.id')
                                            ->join('T_Stato_Cucinas', 'Commandas.statoCucina', '=', 'T_Stato_Cucinas.id')
                                            ->join('commandarigas', 'Commandas.id', '=', 'commandarigas.idCommanda')
                                            ->where('idGiornata',$id)->where('competenza', '=', $competenza)->where('commandas.stato', '!=', $stato)->distinct()->get(['competenza'])->count();




                    $res['message'] = 'trovato Commande per Giornata filtrata su competenza';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;

        }

        public function getCommandeByGiornataeCompetenzaestato($id, $competenza, $stato)

        {

            $res = [
                'data' =>[],
                'number' => 0,
                'message' => ''
                    ];
                try{
                    $res['data'] = Commanda::select('Commandas.*', 'T_Stato_Bevandes.d_stato_bevande', 'T_Stato_Commandas.d_stato_commanda', 'T_Stato_Contabiles.d_stato_Contabile', 'T_Stato_Cucinas.d_stato_Cucina')
                                            ->join('T_Stato_Bevandes', 'Commandas.statoBevande', '=', 'T_Stato_Bevandes.id')
                                            ->join('T_Stato_Commandas', 'Commandas.stato', '=', 'T_Stato_Commandas.id')
                                            ->join('T_Stato_Contabiles', 'Commandas.statoContabile', '=', 'T_Stato_Contabiles.id')
                                            ->join('T_Stato_Cucinas', 'Commandas.statoCucina', '=', 'T_Stato_Cucinas.id')
                                            ->join('commandarigas', 'Commandas.id', '=', 'commandarigas.idCommanda')
                                            ->where('idGiornata',$id)->where('competenza', '=', $competenza)->where('Commandas.stato', '=', $stato)->distinct()->get(['competenza']);

                 //   $res['number'] = Commanda::where('idGiornata',$id)->where('competenza', '=', $competenza)->distinct()->get(['competenza'])->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();

                    $res['number'] = Commanda::select('Commandas.*', 'T_Stato_Bevandes.d_stato_bevande', 'T_Stato_Commandas.d_stato_commanda', 'T_Stato_Contabiles.d_stato_Contabile', 'T_Stato_Cucinas.d_stato_Cucina')
                                            ->join('T_Stato_Bevandes', 'Commandas.statoBevande', '=', 'T_Stato_Bevandes.id')
                                            ->join('T_Stato_Commandas', 'Commandas.stato', '=', 'T_Stato_Commandas.id')
                                            ->join('T_Stato_Contabiles', 'Commandas.statoContabile', '=', 'T_Stato_Contabiles.id')
                                            ->join('T_Stato_Cucinas', 'Commandas.statoCucina', '=', 'T_Stato_Cucinas.id')
                                            ->join('commandarigas', 'Commandas.id', '=', 'commandarigas.idCommanda')
                                            ->where('idGiornata',$id)->where('competenza', '=', $competenza)->where('Commandas.stato', '=', $stato)->distinct()->get(['competenza'])->count();




                    $res['message'] = 'trovato Commande per Giornata filtrata su competenza e stato';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;

        }

        public function deleteAll()
        {
            $res = [
                'data' =>[],
                'number' => 0,
                'message' => '',
                'rc' => 'KO'
                    ];
                try{


                   // $res['data'] = Manifestazione::all();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                   // lettura con join a tabelle correlate
                    // DB::table('Commandas')truncate()
                    Commanda::truncate();
              //      Commanda::All()->delete();
    
                    $res['number'] = Commanda::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                    $res['message'] = 'Cancellate tutte le Commande';
                    $res['rc'] = 'OK';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;
        }
 

}
