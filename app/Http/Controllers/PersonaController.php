<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
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
                $res['data'] = DB::table('Personas')
                                  ->join('T_titolos', 'Personas.titolo', '=', 'T_Titolos.id')
                                  ->join('T_Stato_Personas', 'Personas.idstato', '=', 'T_Stato_Personas.id')
                                  ->join('T_Ruolos', 'Personas.idRuolo', '=', 'T_Ruolos.id')
                                  ->join('T_Ruolo_days', 'Personas.idRuolo_Day', '=', 'T_Ruolo_days.id')
                                  ->join('userLevels', 'Personas.UserLevel', '=', 'userLevels.id')
                                  ->select('Personas.*', 'T_titolos.d_Titolo', 'T_Stato_Personas.d_Stato_Persona', 'T_Ruolos.d_ruolo', 'T_Ruolo_days.d_ruolo_day', 'userLevels.UserLevelName')
                                  ->orderby('d_ruolo_day','asc')
                                  ->orderby('cognome','asc')
                                  ->orderby('nome', 'asc')
                                  ->get();

                $res['number'] = Persona::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Utente';
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
            $User = new Persona();
            $success = true;
            $postData = $request->except('id','_method', 'd_Titolo', 'd_Stato_Persona', 'd_ruolo', 'd_ruolo_day', 'UserLevelName');
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
        $res = [
            'data' =>[],
            'message' => ''
                ];
          try{
           $res['message'] = 'trovato Giornata';
           $res['data'] =  Persona::select('Personas.*', 'T_titolos.d_Titolo', 'T_Stato_Personas.d_Stato_Persona', 'T_Ruolos.d_ruolo', 'T_Ruolo_days.d_ruolo_day', 'userLevels.UserLevelName')
                                    ->join('T_titolos', 'Personas.titolo', '=', 'T_Titolos.id')
                                    ->join('T_Stato_Personas', 'Personas.idstato', '=', 'T_Stato_Personas.id')
                                    ->join('T_Ruolos', 'Personas.idRuolo', '=', 'T_Ruolos.id')
                                    ->join('T_Ruolo_days', 'Personas.idRuolo_Day', '=', 'T_Ruolo_days.id')
                                    ->join('userLevels', 'Personas.UserLevel', '=', 'userLevels.id')
                                ->findOrFail($id);
         } catch (\Exception $e){
            $res['message'] =  'Persona Inesistente !! per id selezionato ';            // $e->getMessage();
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
        $data = [];
        $message = '';
        $rc = 'KO';
        try {
            $User = Persona::findOrFail($id);
            $success = true;
            // salva sulla variabile data i dati dalla richiesta (request)
            // ad eccezzione del campo id e del campo di comodo _method

            $postData = $request->except('id','_method', 'd_Titolo', 'd_Stato_Persona', 'd_ruolo', 'd_ruolo_day', 'UserLevelName');
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
        return compact('data','message','success','rc');
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
            $User = Persona::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'Persona non trovata - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }

    //   metodi di Moreno per gestire le giornate della manifestazione

    public function getpersoneforRuolo($id)
        {

            $res = [
                'data' =>[],
                'number' => 0,
                'message' => 'Devo effettuare la ricerca delle persone'
                    ];
                try{
                $res['data'] =  Persona::select('Personas.*', 'T_titolos.d_Titolo', 'T_Stato_Personas.d_Stato_Persona', 'T_Ruolos.d_ruolo', 'T_Ruolo_days.d_ruolo_day', 'userLevels.UserLevelName')
                                            ->join('T_titolos', 'Personas.titolo', '=', 'T_Titolos.id')
                                            ->join('T_Stato_Personas', 'Personas.idstato', '=', 'T_Stato_Personas.id')
                                            ->join('T_Ruolos', 'Personas.idRuolo', '=', 'T_Ruolos.id')
                                            ->join('T_Ruolo_days', 'Personas.idRuolo_Day', '=', 'T_Ruolo_days.id')
                                            ->join('userLevels', 'Personas.UserLevel', '=', 'userLevels.id')
                                            ->where('idRuolo_Day',$id)
                                            ->orderby('d_ruolo_day','asc')
                                            ->orderby('cognome','asc')
                                            ->orderby('nome', 'asc')
                                            ->get();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                    $res['number'] = Persona::where('idRuolo_Day',$id)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                    $res['message'] = 'trovato Persone per Ruolo filtrata';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;

        }

        public function getpersoneforRuoloFiltrato($val1, $val2)
        {
            $res = [
                'data' =>[],
                'number' => 0,
                'message' => ''
                    ];
                try{
                $res['data'] =  Persona::select('Personas.*', 'T_titolos.d_Titolo', 'T_Stato_Personas.d_Stato_Persona', 'T_Ruolos.d_ruolo', 'T_Ruolo_days.d_ruolo_day', 'userLevels.UserLevelName')
                                            ->join('T_titolos', 'Personas.titolo', '=', 'T_Titolos.id')
                                            ->join('T_Stato_Personas', 'Personas.idstato', '=', 'T_Stato_Personas.id')
                                            ->join('T_Ruolos', 'Personas.idRuolo', '=', 'T_Ruolos.id')
                                            ->join('T_Ruolo_days', 'Personas.idRuolo_Day', '=', 'T_Ruolo_days.id')
                                            ->join('userLevels', 'Personas.UserLevel', '=', 'userLevels.id')
                                            ->where('idRuolo_Day', '>', 0)->where('idRuolo_Day', '<', $val2)
                                            
                                          /*  ->where([
                                                ['idRuolo_Day', '>', 0],
                                                ['idRuolo_Day', '<', $val1],
                                            ])  */
                                      
                                       ->orderby('d_ruolo_day','asc') 
                                        ->orderby('cognome','asc')
                                        ->orderby('nome', 'asc')  
                                        ->get();   
                    $res['number'] = Persona::where('idRuolo_Day', '>', $val1)->where('idRuolo_Day', '<', $val2)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                    $res['message'] = 'trovato Persone per Ruolo filtrato';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;
            }


            public function getpersonebyRuoloFiltrato($val1)
            {
                $res = [
                    'data' =>[],
                    'number' => 0,
                    'message' => ''
                        ];
                    try{
                    $res['data'] =  Persona::select('Personas.*', 'T_titolos.d_Titolo', 'T_Stato_Personas.d_Stato_Persona', 'T_Ruolos.d_ruolo', 'T_Ruolo_days.d_ruolo_day', 'userLevels.UserLevelName')
                                                ->join('T_titolos', 'Personas.titolo', '=', 'T_Titolos.id')
                                                ->join('T_Stato_Personas', 'Personas.idstato', '=', 'T_Stato_Personas.id')
                                                ->join('T_Ruolos', 'Personas.idRuolo', '=', 'T_Ruolos.id')
                                                ->join('T_Ruolo_days', 'Personas.idRuolo_Day', '=', 'T_Ruolo_days.id')
                                                ->join('userLevels', 'Personas.UserLevel', '=', 'userLevels.id')
                                                ->where('idRuolo_Day', '>', 0)->where('idRuolo_Day', '<', $val1)
                                                ->orderby('d_ruolo_day','asc')
                                            ->orderby('cognome','asc')
                                            ->orderby('nome', 'asc')
                                            ->get();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                        $res['number'] = Persona::where('idRuolo_Day', '>', 0)->where('idRuolo_Day', '<', $val1)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                        $res['message'] = 'trovato Persone per Ruolo filtrato';
                    } catch (\Exception $e){
                        $res['message'] = $e->getMessage();
                    }
                    return $res;
                }

                public function getpersoneActive()
                {
                    $inServizio ='S';
                    $utilCommanda = 'N';
                    $res = [
                        'data' =>[],
                        'number' => 0,
                        'message' => ''
                            ];
                        try{
                        $res['data'] =  Persona::select('Personas.*', 'T_titolos.d_Titolo', 'T_Stato_Personas.d_Stato_Persona', 'T_Ruolos.d_ruolo', 'T_Ruolo_days.d_ruolo_day', 'userLevels.UserLevelName')
                                                    ->join('T_titolos', 'Personas.titolo', '=', 'T_Titolos.id')
                                                    ->join('T_Stato_Personas', 'Personas.idstato', '=', 'T_Stato_Personas.id')
                                                    ->join('T_Ruolos', 'Personas.idRuolo', '=', 'T_Ruolos.id')
                                                    ->join('T_Ruolo_days', 'Personas.idRuolo_Day', '=', 'T_Ruolo_days.id')
                                                    ->join('userLevels', 'Personas.UserLevel', '=', 'userLevels.id')
                                                    ->where('inServizio', '=', $inServizio)->where('utilizzatoCommanda', '=', $utilCommanda)
                                                    ->orderby('d_ruolo_day','asc')
                                                ->orderby('cognome','asc')
                                                ->orderby('nome', 'asc')
                                                ->get();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                            $res['number'] = Persona::where('inServizio', '=', $inServizio)->where('utilizzatoCommanda', '=', $utilCommanda)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                            $res['message'] = 'trovato Persone attive';
                        } catch (\Exception $e){
                            $res['message'] = $e->getMessage();
                        }
                        return $res;
                    }


/*
       modo 1
$res = [
                'data' =>[],
                'number' => 0,
                'message' => ''
                    ];
                try{
                $res['data'] =  Persona::select('Personas.*', 'T_titolo.d_Titolo', 'T_Stato_Persona.d_Stato_Persona', 'T_Ruolo.d_ruolo', 'T_Ruolo_days.d_ruolo_day', 'userLevels.UserLevelName')
                                            ->join('T_titolo', 'Personas.titolo', '=', 'T_Titolo.id')
                                            ->join('T_Stato_Persona', 'Personas.idstato', '=', 'T_Stato_Persona.id')
                                            ->join('T_Ruolo', 'Personas.idRuolo', '=', 'T_Ruolo.id')
                                            ->join('T_Ruolo_days', 'Personas.idRuolo_Day', '=', 'T_Ruolo_days.id')
                                            ->join('userLevels', 'Personas.UserLevel', '=', 'userLevels.id')
                                        ->where('idRuolo_Day', '>', $val1)->where('idRuolo_Day', '<', $val2)
                                        ->orderby('d_ruolo_day','asc')
                                        ->orderby('cognome','asc')
                                        ->orderby('nome', 'asc')
                                        ->get();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                    $res['number'] = Persona::where('idRuolo_Day', '>', $val1)->where('idRuolo_Day', '<', $val2)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                    $res['message'] = 'trovato Persone per Ruolo filtrato';
                } catch (\Exception $e){
                    $res['message'] = $e->getMessage();
                }
                return $res;



*/


/*
            $users = DB::table('users')->where([
                ['status', '=', '1'],
                ['subscribed', '<>', '1'],
            ])->get();
*/

        }
