<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Models\Password_resets;
use Illuminate\Support\Facades\Hash;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ChangePasswordController extends Controller
{
       // effettuo il reset della password
      //  public function resetPassword(ChangePasswordRequest $request)
        public function resetPassword(Request $request)
       {

/*    test
        $response['status'] = 99;
        $response['code'] = 200;
        $response['data'] = $request;
        $response['email'] = $request->email;
        $response['token'] = $request->token;
        $response['password'] = $request->password;
        $response['message'] = 'appena entrato in resetPassword';
        return response()->json($response);
*/

   // return $this->getPasswordResetTableRow($request)->count()> 0 ? $this->changePassword($request) : $this->tokenNotFoundResponse();


 //  $user = Password_resets::where(['email' => $request->email, 'token' => $request->token])->first();


   $user = Password_resets::select('password_resets.*')
                            ->where('email',$request->email)
                            ->where('token',$request->token)
                            ->first();


     //   $user = $this->getPasswordResetTableRow($request)->first();
        try {
            if ($user) {
                $this->changePassword($request);
                $this->getPasswordResetTableRow($request);
                $response['status'] = 1;
                $response['code'] = 200;
                $response['data'] = $user;
                $response['message'] = 'Password cambiata con successo';
                return response()->json($response);
            } else {
                $response['status'] = 0;
                $response['code'] = 202;
                $response['data_e'] = $request->email;
                $response['data_t'] = $request->token;
                $response['message'] = 'credenziali errate ';
                return response()->json($response);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

     }

       private function getPasswordResetTableRow($request)

       {
         // cancellazione funziona

              DB::table('password_resets')->where(['email' => $request->email])->delete();

              $response['status'] = 8;
              $response['code'] = 200;
              $response['data'] = null;
              $response['message'] = 'richiesta di cambio password cancellata';
              return response()->json($response);

       }

       private function changePassword($request)

       {
          // cambio password sembra funzioni

           $user = User::whereEmail($request->email)->first();

           $psw = ['password'=> Hash::make($request->password)];
         //  dd($psw);

           $user->update(['password'=> Hash::make($request->password)]);

           $response['status'] = 9;
           $response['code'] = 200;
           $response['psw'] = $psw;
           $response['data'] = $user;
           $response['message'] = 'cambio password cripata regolarmente';
       //    dd($response);
           return response()->json($response);

         //   $response['status'] = 1;
         //   $response['code'] = 200;
         //   $response['data'] = $user;
         //   $response['message'] = 'Password cambiata correttamente ';
         //   return response()->json($response);

         //  return response()->json(['data'=>'Password cambiata con successo'],
         //  Response::HTTP_CREATED);
       }

       private function tokenNotFoundResponse()

       {

        // const HTTP_UNPROCESSABLE_ENTITY = 422;

        $response['status'] = 0;
        $response['code'] = 422;
        $response['data'] = null;
        $response['message'] = 'Token o Email non corretti';
        return response()->json($response);

         //  return response()->json(['error' => 'Token o Email non corretti'],
         //  Response::422);

       }


       public function destroy($email)
       {
           $data = [];
           $message = 'Cancellazione eseguita con successo !!';
           $success = true;
           try {
               $User = Password_resets::findOrFail($email);
               $data = $User;
               $success = $User->delete();
           } catch (\Exception $e) {
               $success = false;
               $message = 'Utente non trovato - Cancellazione non possibile';
           }
           return compact('data','message','success');
       }

}
