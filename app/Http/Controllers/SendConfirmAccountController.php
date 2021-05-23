<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmAccountMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class SendConfirmAccountController extends Controller
{
    public function sendEmail(Request $request) {

        // eliminato il controllo dell'esistenza utente dato che lo sto creando
        //   return $request->all();
        /*
       if(!$this->validateEmail($request->email)) {
           return $this->failedResponse();
       }  */

       $cognome = $request->cognome;
       $name = $request->name;
       $username = $request->username;
       $password = $request->password;

       $this->send($request->email, $cognome, $name, $username, $password);
       return $this->successResponse();

   }

   public function send($email,$cognome, $name, $username, $password) {

       $token = $this->createToken($email, $cognome, $name, $username, $password);

       Mail::to($email)->send(new ConfirmAccountMail($token));
   }

   public function createToken($email, $cognome, $name, $username, $password) {

       $oldToken = db::table('register_confirmeds')->where('email',$email)->first();
       if($oldToken) {
           return $oldToken;
       }


       $token = Str::random(60);                     //str_random(60);



       $this->saveToken($token,$email,$cognome, $name, $username, $password);

       return $token;
   }


   public function saveToken($token, $email,$cognome, $name, $username, $password) {

       DB::table('register_confirmeds')->insert([
           'cognome' => $cognome,
           'name' => $name,
           'username' => $username,
           'password' => $password,
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
           'error'=> 'email non presente in questo Database'
       ], Response:: HTTP_NOT_FOUND);
   }


   public function successResponse() {


       return response()->json([
           'data'=> 'inviata correttamente email per Conferma creazione Account, controlla la tua casella di posta'
       ], Response:: HTTP_OK);

   }

   }
