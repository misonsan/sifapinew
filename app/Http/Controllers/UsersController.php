<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
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
                   $res['data'] = DB::table('users')
                                  ->join('T_Stato_Utentes', 'users.idstato', '=', 'T_Stato_Utentes.id')
                                  ->join('T_Ruolos', 'users.idRuolo', '=', 'T_Ruolos.id')
                                  ->join('T_Ruolo_days', 'users.idRuolo_Day', '=', 'T_Ruolo_days.id')
                                  ->join('T_ruolo_webs', 'users.idruoloweb', '=', 'T_ruolo_webs.id')
                                  ->select('users.*',  'T_Stato_Utentes.d_Stato_Utente', 'T_Ruolos.d_ruolo', 'T_Ruolo_days.d_ruolo_day', 'T_ruolo_webs.d_ruolo_web')
                                  ->orderby('d_ruolo_web','asc')
                                  ->orderby('d_ruolo_day','asc')
                                  ->orderby('cognome','asc')
                                  ->orderby('nome', 'asc')
                                  ->get();
                $res['number'] = User::All()->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Users';
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
            $User = new User();
            $success = true;
            $postData = $request->except('_method', 'd_ruolo', 'd_ruolo_day', 'd_Stato_Utente', 'd_ruolo_web');  // id lo creo io e quindo non posso filtrarlo
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
                    'message' => 'User Trovato'
                        ];
                  try{

                   $res['data'] =  User::select('users.*',  'T_Stato_Utentes.d_Stato_Utente', 'T_Ruolos.d_ruolo', 'T_Ruolo_days.d_ruolo_day', 'T_ruolo_webs.d_ruolo_web')
                                        ->join('T_Stato_Utentes', 'users.idstato', '=', 'T_Stato_Utentes.id')
                                        ->join('T_Ruolos', 'users.idRuolo', '=', 'T_Ruolos.id')
                                        ->join('T_Ruolo_days', 'users.idRuolo_Day', '=', 'T_Ruolo_days.id')
                                        ->join('T_ruolo_webs', 'users.idruoloweb', '=', 'T_ruolo_webs.id')
                                        ->findOrFail($id);
                  $success = true;
                 } catch (\Exception $e){
                    $res['message'] =  'User  Inesistente !!  ';
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
            $User = User::findOrFail($id);
            $success = true;
            // salva sulla variabile data i dati dalla richiesta (request)
            // ad eccezzione del campo id e del campo di comodo _method

            $postData = $request->except('id','_method',  'd_ruolo', 'd_ruolo_day', 'd_Stato_Utente', 'd_ruolo_web');
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
            $User = User::findOrFail($id);
            $data = $User;
            $success = $User->delete();
        } catch (\Exception $e) {
            $success = false;
            $message = 'User non trovato - Cancellazione non possibile';
        }
        return compact('data','message','success');
    }

    public function getbyemail($email) {

        $res = [
            'data' =>[],
            'number' => 0,
            'message' => 'Devo effettuare la ricerca per email'
                ];
            try{
                $res['data'] =  User::select('users.*',  'T_Stato_Utentes.d_Stato_Utente', 'T_Ruolos.d_ruolo', 'T_Ruolo_days.d_ruolo_day', 'T_ruolo_webs.d_ruolo_web')
                                     ->join('T_Stato_Utentes', 'users.idstato', '=', 'T_Stato_Utentes.id')
                                     ->join('T_Ruolos', 'users.idRuolo', '=', 'T_Ruolos.id')
                                     ->join('T_Ruolo_days', 'users.idRuolo_Day', '=', 'T_Ruolo_days.id')
                                     ->join('T_ruolo_webs', 'users.idruoloweb', '=', 'T_ruolo_webs.id')
                                     ->where('email',$email)->get();   // $res['data'] = Fedele::where('idmessa',$idmessa->input('idmessa'))->get();
                $res['number'] = User::where('email',$email)->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Utente per email';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }

    public function getUserAnonimus() {

        $ruolo = 0;
        $res = [
            'data' =>[],
            'number' => 0,
            'message' => 'Devo effettuare la ricerca per anonimi'
                ];
            try{
                $res['data'] =  User::select('users.*',  'T_Stato_Utentes.d_Stato_Utente', 'T_Ruolos.d_ruolo', 'T_Ruolo_days.d_ruolo_day', 'T_ruolo_webs.d_ruolo_web')
                                     ->join('T_Stato_Utentes', 'users.idstato', '=', 'T_Stato_Utentes.id')
                                     ->join('T_Ruolos', 'users.idRuolo', '=', 'T_Ruolos.id')
                                     ->join('T_Ruolo_days', 'users.idRuolo_Day', '=', 'T_Ruolo_days.id')
                                     ->join('T_ruolo_webs', 'users.idruoloweb', '=', 'T_ruolo_webs.id')
                                    ->where('idruoloweb',$ruolo)->WherenotNull('email')->get();
                $res['number'] = User::where('idruoloweb',$ruolo)->WherenotNull('email')->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Users Anonimi';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }


    public function getUserSanfra() {

        $ruolo = 0;
        $res = [
            'data' =>[],
            'number' => 0,
            'message' => 'Devo effettuare la ricerca per Sanfra'
                ];
            try{
                $res['data'] = User::select('users.*')
                                    ->where('idruoloweb', '!=',$ruolo)->wherenotNull('email')->get();
                $res['number'] = User::where('idruoloweb', '!=',$ruolo)->wherenotNull('email')->count();  // Fedele::where('idmessa',$idmessa->input('idmessa'))->count();
                $res['message'] = 'trovato Users per Sanfra';
            } catch (\Exception $e){
                $res['message'] = $e->getMessage();
            }
            return $res;
    }

}
