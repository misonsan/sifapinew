<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodotto;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class ProdottoController extends Controller
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
               $res['data']  = DB::table('Prodottos')
                                  ->join('T_Stato_Prodottos', 'Prodottos.stato', '=', 'T_Stato_Prodottos.id')
                                  ->join('T_Tipologias', 'Prodottos.tipologia', '=', 'T_Tipologias.id')
                                  ->join('T_Categoria_Prodottos', 'Prodottos.categoria', '=', 'T_Categoria_Prodottos.id')
                                  ->join('T_Competenza_Prodottos', 'Prodottos.competenza', '=', 'T_Competenza_Prodottos.id')
                                  ->select('Prodottos.*', 'T_Stato_Prodottos.d_stato_prodotto', 'T_Tipologias.d_Tipologia', 'T_Categoria_Prodottos.d_Categoria', 'T_Competenza_Prodottos.d_Competenza')
                                  ->orderby('d_Tipologia','asc')
                                  ->orderby('d_Categoria', 'asc')
                                  ->orderby('descrizione_prodotto', 'asc')
                                  ->get();

                $res['number'] = Prodotto::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Prodotti';
                $success = true;
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
                $success = false;
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
            $User = new Prodotto();
            $success = true;
            $postData = $request->except('id','_method', 'd_stato_prodotto', 'd_competenza', 'd_categoria', 'd_tipologia');
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
                    'message' => 'Prodotto Trovato'
                        ];
                  try{

                   $res['data'] =  Prodotto::select('Prodottos.*', 'T_Stato_Prodottos.d_stato_prodotto', 'T_Tipologias.d_Tipologia', 'T_Categoria_Prodottos.d_Categoria', 'T_Competenza_Prodottos.d_Competenza')
                                            ->join('T_Stato_Prodottos', 'Prodottos.stato', '=', 'T_Stato_Prodottos.id')
                                            ->join('T_Tipologias', 'Prodottos.tipologia', '=', 'T_Tipologias.id')
                                            ->join('T_Categoria_Prodottos', 'Prodottos.categoria', '=', 'T_Categoria_Prodottos.id')
                                            ->join('T_Competenza_Prodottos', 'Prodottos.competenza', '=', 'T_Competenza_Prodottos.id')
                                            ->findOrFail($id);
                  $success = true;
                 } catch (\Exception $e){
                    $res['message'] =  'Prodotto  Inesistente !! per id selezionato ';            // $e->getMessage();
                    $success = false;
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
        $rc = 'KO';
        try {
            $User = Prodotto::findOrFail($id);
            $success = true;
            // salva sulla variabile data i dati dalla richiesta (request)
            // ad eccezzione del campo id e del campo di comodo _method

            $postData = $request->except('id','_method', 'd_stato_prodotto', 'd_competenza', 'd_categoria', 'd_tipologia');
            // imposto la crittografia alla password  (questo temporraneo)
            // $postData['password'] =  Hash::make($postData['password'] ?? 'password');  // bcrypt('test');   -->   non abbiamo password
            // eseguo l'aggiornamento
            $success = $User->update($postData);
            $data = $User;
            $message = 'Aggiornamento eseguito con successo';
            $rc = 'OK';
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
            $User = Prodotto::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'Prodotto non trovato - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }

        // metodi creati da moreno

        public function getProdottiforStato($stato)
        {
            //
                $res = [
                'data' =>[],
                'number' => 0,
                'message' => ''
                    ];
                try{
                // $res['data'] = Manifestazione::all();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                // lettura con join a tabelle correlate
                $res['data'] = DB::table('Prodottos')
                                    ->join('T_Stato_Prodottos', 'Prodottos.stato', '=', 'T_Stato_Prodottos.id')
                                    ->join('T_Tipologias', 'Prodottos.tipologia', '=', 'T_Tipologias.id')
                                    ->join('T_Categoria_Prodottos', 'Prodottos.categoria', '=', 'T_Categoria_Prodottos.id')
                                    ->join('T_Competenza_Prodottos', 'Prodottos.competenza', '=', 'T_Competenza_Prodottos.id')
                                    ->select('Prodottos.*', 'T_Stato_Prodottos.d_stato_prodotto', 'T_Tipologias.d_Tipologia', 'T_Categoria_Prodottos.d_Categoria', 'T_Competenza_Prodottos.d_Competenza')
                                    ->where('Prodottos.stato',$stato)
                                    ->get();

                    $res['number'] = DB::table('Prodottos')->where('Prodottos.stato',$stato)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                    $res['message'] = 'trovato Prodotti per lo stato Selezionato';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;
        }

        public function getProdottiforMenu($aMenu)
        {
            //
                $res = [
                'data' =>[],
                'number' => 0,
                'message' => ''
                    ];
                try{
                // $res['data'] = Manifestazione::all();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                // lettura con join a tabelle correlate
                    $res['data'] = DB::table('Prodottos')
                                    ->join('T_Stato_Prodottos', 'Prodottos.stato', '=', 'T_Stato_Prodottos.id')
                                    ->join('T_Tipologias', 'Prodottos.tipologia', '=', 'T_Tipologias.id')
                                    ->join('T_Categoria_Prodottos', 'Prodottos.categoria', '=', 'T_Categoria_Prodottos.id')
                                    ->join('T_Competenza_Prodottos', 'Prodottos.competenza', '=', 'T_Competenza_Prodottos.id')
                                    ->select('Prodottos.*', 'T_Stato_Prodottos.d_stato_prodotto', 'T_Tipologias.d_Tipologia', 'T_Categoria_Prodottos.d_Categoria', 'T_Competenza_Prodottos.d_Competenza')
                                    ->where('aMenu',$aMenu)
                                    ->get();

                    $res['number'] = Prodotto::where('aMenu',$aMenu)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                    $res['message'] = 'trovato Prodotti per lo stato Selezionato';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;
        }

        public function getProdottiforTipologia($tipo)
        {
            //
                $res = [
                'data' =>[],
                'number' => 0,
                'message' => ''
                    ];
                try{
                // $res['data'] = Manifestazione::all();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                // lettura con join a tabelle correlate
                    $res['data'] = DB::table('Prodottos')
                                    ->join('T_Stato_Prodottos', 'Prodottos.stato', '=', 'T_Stato_Prodottos.id')
                                    ->join('T_Tipologias', 'Prodottos.tipologia', '=', 'T_Tipologias.id')
                                    ->join('T_Categoria_Prodottos', 'Prodottos.categoria', '=', 'T_Categoria_Prodottos.id')
                                    ->join('T_Competenza_Prodottos', 'Prodottos.competenza', '=', 'T_Competenza_Prodottos.id')
                                    ->select('Prodottos.*', 'T_Stato_Prodottos.d_stato_prodotto', 'T_Tipologias.d_Tipologia', 'T_Categoria_Prodottos.d_Categoria', 'T_Competenza_Prodottos.d_Competenza')
                                    ->where('tipologia',$tipo)
                                    ->orderby('d_Tipologia','asc')
                                    ->get();

                    $res['number'] = Prodotto::where('tipologia',$tipo)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                    $res['message'] = 'trovato Prodotti per la tipologia Selezionata';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;
        }

        public function getProdottiforCategoria($tipo)
        {
            //
                $res = [
                'data' =>[],
                'number' => 0,
                'message' => ''
                    ];
                try{
                // $res['data'] = Manifestazione::all();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                // lettura con join a tabelle correlate
                    $res['data'] = DB::table('Prodottos')
                                    ->join('T_Stato_Prodottos', 'Prodottos.stato', '=', 'T_Stato_Prodottos.id')
                                    ->join('T_Tipologias', 'Prodottos.tipologia', '=', 'T_Tipologias.id')
                                    ->join('T_Categoria_Prodottos', 'Prodottos.categoria', '=', 'T_Categoria_Prodottos.id')
                                    ->join('T_Competenza_Prodottos', 'Prodottos.competenza', '=', 'T_Competenza_Prodottos.id')
                                    ->select('Prodottos.*', 'T_Stato_Prodottos.d_stato_prodotto', 'T_Tipologias.d_Tipologia', 'T_Categoria_Prodottos.d_Categoria', 'T_Competenza_Prodottos.d_Competenza')
                                    ->where('categoria',$tipo)
                                    ->orderby('d_Categoria','asc')
                                    ->get();

                    $res['number'] = Prodotto::where('categoria',$tipo)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                    $res['message'] = 'trovato Prodotti per la categoria Selezionata';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;
        }


        public function getProdottiforCompetenza($tipo)
        {
            //
                $res = [
                'data' =>[],
                'number' => 0,
                'message' => ''
                    ];
                try{
                // $res['data'] = Manifestazione::all();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                // lettura con join a tabelle correlate
                    $res['data'] = DB::table('Prodottos')
                                    ->join('T_Stato_Prodottos', 'Prodottos.stato', '=', 'T_Stato_Prodottos.id')
                                    ->join('T_Tipologias', 'Prodottos.tipologia', '=', 'T_Tipologias.id')
                                    ->join('T_Categoria_Prodottos', 'Prodottos.categoria', '=', 'T_Categoria_Prodottos.id')
                                    ->join('T_Competenza_Prodottos', 'Prodottos.competenza', '=', 'T_Competenza_Prodottos.id')
                                    ->select('Prodottos.*', 'T_Stato_Prodottos.d_stato_prodotto', 'T_Tipologias.d_Tipologia', 'T_Categoria_Prodottos.d_Categoria', 'T_Competenza_Prodottos.d_Competenza')
                                    ->where('competenza',$tipo)
                                    ->orderby('d_Competenza','asc')
                                    ->get();

                    $res['number'] = Prodotto::where('competenza',$tipo)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                    $res['message'] = 'trovato Prodotti per la competenza Selezionata';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;
        }
     
}

