<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\AuthController;
use App\Http\Controllers\{UsersController,AuthController,SendConfirmAccountController,ConfirmedAccountController,
                          ResetPasswordController,ChangePasswordController,FileController};



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*  ----------  originale
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

*/

//    devo aggiungere poi il changepassword
// ----------------------   per versioni predenti alla 8 di laravell   (php artisan --version)

/*
Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});
*/


// per versioni di laravel dalla 8 o superiori

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class,'login']);
    Route::post('signup', [AuthController::class,'signup']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
    // per gestire il camcio password
    Route::post('sendPasswordResetLink', [ResetPasswordController::class,'sendEmail']);
    // per gestire una mail con molti campi di personalizzazione
    Route::post('sendPasswordResetLinkMoreFields', [ResetPasswordController::class,'sendEmailMoreFields']);

    Route::post('resetPassword', [ChangePasswordController::class,'resetPassword']);
     // per gestire la conferma creazione utente
     Route::post('sendAccountConfirmedLink', [SendConfirmAccountController::class,'sendEmail']);
     Route::post('signupConfirmedAccount', [ConfirmedAccountController::class,'signup']);

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// per upload file immagini
Route::post('file', [FileController::class,'file']);
