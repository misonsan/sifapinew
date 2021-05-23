<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function sendEmail(Request $request) {

        //   return $request->all();
       if(!$this->validateEmail($request->email)) {
           return $this->failedResponse();
       }


       $this->send($request->email);
       return $this->successResponse();

   }

   public function send($email) {

       $token = $this->createToken($email);
       // dd($token);
       Mail::to($email)->send(new ResetPasswordMail($token));
   }

   public function createToken($email) {

       $oldToken = db::table('password_resets')->where('email',$email)->first();
       if($oldToken) {
           return $oldToken;
       }


       $token = Str::random(60);                     //str_random(60);

        //  dd($token);

       $this->saveToken($token,$email);

       return $token;
   }


   public function saveToken($token, $email) {

       DB::table('password_resets')->insert([
           'email' => $email,
           'token' => $token,
           'created_at' => Carbon::now()
       ]);
   }


   public function validateEmail($email) {
       return !!User::where('email', $email)->first();

   }

   public function failedResponse() {

       return response()->json([
           'error'=> 'email non presente nel Database'
       ], Response:: HTTP_NOT_FOUND);
   }


   public function successResponse() {


       return response()->json([
           'data'=> 'inviata correttamente email per reset Password, controlla la tua casella di posta'
       ], Response:: HTTP_OK);

   }

// metodi per gestire la personalizzazione di molti campi nella blade di comunicazione
// recuperati dalla lettura del record per email

   public function sendEmailMoreFields(Request $request) {

    $datamail = [
        'email' => '',
        'cognome' => '',
        'nome' => '',
        'token' => ''
    ];

    $email = $request->email;
// leggo utente e preparo i campi che devono essere utilizzati nella blade di comunicazione

    $res = [
        'data' =>'',
        'number' => 0,
        'message' => 'Devo effettuare la ricerca per email'
            ];
        try{
            $res['data'] =  User::select('users.*',  'T_Stato_Utentes.d_Stato_Utente', 'T_Ruolos.d_ruolo', 'T_Ruolo_days.d_ruolo_day', 'T_ruolo_webs.d_ruolo_web')
                                ->join('T_Stato_Utentes', 'users.idstato', '=', 'T_Stato_Utentes.id')
                                ->join('T_Ruolos', 'users.idRuolo', '=', 'T_Ruolos.id')
                                ->join('T_Ruolo_days', 'users.idRuolo_Day', '=', 'T_Ruolo_days.id')
                                ->join('T_ruolo_webs', 'users.idruoloweb', '=', 'T_ruolo_webs.id')
                                ->where('email',$email)->first();
            $res['number'] = User::where('email',$email)->count();
            $res['message'] = 'trovato Utente per email';


            $datamail['email'] = $request->email;
            $datamail['cognome'] = $res['data']->cognome;
            $datamail['nome'] = $res['data']->nome;

        } catch (\Exception $e){
            $res['message'] = $e->getMessage();
        }



       // dd($datamail);


    //   return $request->all();
   if(!$this->validateEmail($request->email)) {
       return $this->failedResponse();
   }

   $this->sendMoreFields($datamail);
   return $this->successResponse();

}

public function sendMoreFields($datamail) {

    $email = $datamail->email;

    $token = $this->createToken($email);
    $datamail['data_token'] = $token;
    // dd($token);
    Mail::to($email)->send(new ResetPasswordMailMoreFields($datamail));
}





}
