<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmPrenotazioneMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class SendConfirmPrenotazioneController extends Controller
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
       $telefono = $request->telefono;
       $persone = $request->persone;
       $password = $request->password;
       $datapren = $request->datapren;
       $email = $request->email;



       echo var_dump($persone) . "<br>";

       // per controllare il valore passato
      // dd($persone);


       $this->send($email, $cognome, $name, $telefono, $persone, $password, $datapren);
       return $this->successResponse();

   }

   public function send($email, $cognome, $name, $telefono, $persone, $password, $datapren) {


       $codpren = $this->createCodpren();

       $token = $this->createToken($email, $cognome, $name, $telefono, $persone, $codpren, $password, $datapren);

       $datapren = \Carbon\Carbon::parse($datapren)->format('d/m/y');
      // $personex = (string)$persone;
       $personex = strval($persone);
       Mail::to($email)->send(new ConfirmPrenotazioneMail($token, $codpren, $cognome, $name, $datapren, $personex));
   }


   public function createCodpren() {

        $codpren = Str::random(5);     // codice di prenotazione serata da inserire in mail
        return $codpren;

   }


   public function createToken($email, $cognome, $name, $telefono, $persone, $codpren, $password, $datapren) {

       $oldToken = db::table('prenotazione_confirmeds')->where('email',$email)->first();
       if($oldToken) {
           return $oldToken;
       }

       $token = Str::random(60);                     //str_random(60);

       $this->saveToken($token,$email,$cognome, $name, $telefono, $persone, $codpren, $password, $datapren);

       return $token;
   }


   public function saveToken($token, $email,$cognome, $name, $telefono, $persone, $codpren, $password, $datapren) {

       DB::table('prenotazione_confirmeds')->insert([
           'cognome' => $cognome,
           'name' => $name,
           'telefono' => $telefono,
           'datapren' => $datapren,
           'persone' => $persone,
           'password' => $password,
           'codpren' => $codpren,
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
           'error'=> 'email does\'t found on questo Database'
       ], Response:: HTTP_NOT_FOUND);
   }


   public function successResponse() {


       return response()->json([
           'data'=> 'inviata correttamente email per Conferma Prenotazione Pranzo a Sanfra, controlla la tua casella di posta'
       ], Response:: HTTP_OK);

   }



}
