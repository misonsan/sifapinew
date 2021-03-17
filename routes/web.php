<?php


use App\Http\Controllers\UsersController;
use App\Http\Controllers\CommandaController;
use Illuminate\Support\Facades\Auth;     // inserito da Moreno per eliminare il problema
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Auth::routes();




// --------------------------------   Commanda
Route::resource('commanda', 'App\Http\Controllers\CommandaController');
Route::get('commanda/getCommandeByGiornataId/{id}', 'App\Http\Controllers\CommandaController@getCommandeByGiornataId');  //  ok   // [FedeleController::class, 'getFedeliByMessaId']
Route::get('commanda/getCommandeByGiornataIdFiltrato/{id}/tipo/{tipo}' ,'App\Http\Controllers\CommandaController@getCommandeByGiornataIdFiltrato');
Route::get('commandalast/lastid' , 'App\Http\Controllers\CommandaController@getCommandeLastId');
Route::get('commanda/getCommandeByGiornataeCompetenza/{id}/comp/{competenza}' ,'App\Http\Controllers\CommandaController@getCommandeByGiornataeCompetenza');
Route::get('commanda/getCommandeByGiornataeCompetenzaestato/{id}/comp/{competenza}/stato/{stato}' ,'App\Http\Controllers\CommandaController@getCommandeByGiornataeCompetenzaestato');
Route::delete('commandadlt/deleteAll' ,'App\Http\Controllers\CommandaController@deleteAll');


// --------------------------------   Commandariga
Route::resource('commandariga', 'App\Http\Controllers\CommandarigaController');
Route::get('commandarigalast/lastid' , 'App\Http\Controllers\CommandarigaController@getLastCommandarigaid');
Route::get('commandariga/commandarigaprodco/{id}' , 'App\Http\Controllers\CommandarigaController@getProdottiforCommanda');
Route::get('commandariga/getProdottidaLavorare/{competenza}/flagL/{flagLav}' , 'App\Http\Controllers\CommandarigaController@getProdottidaLavorare');
Route::get('commandariga/getProdottiCucinadaConsegnare/{competenza}/flagL/{flagLav}/flagC/{flagCon}' , 'App\Http\Controllers\CommandarigaController@getProdottiCucinadaConsegnare');
Route::get('commandariga/getProdottiBevandedaConsegnare/{competenza}/flagC/{flagCon}' , 'App\Http\Controllers\CommandarigaController@getProdottiBevandedaConsegnare');
Route::get('commandariga/getProdottiConsegnati/{competenza}/flagC/{flagCon}' , 'App\Http\Controllers\CommandarigaController@getProdottiConsegnati');
Route::get('commandariga/getAllProdotti/{competenza}' , 'App\Http\Controllers\CommandarigaController@getAllProdotti');
Route::get('commandariga/getAllProdottiAct/{competenza}' , 'App\Http\Controllers\CommandarigaController@getAllProdottiAct');

// ------------------------   commandaw  (per inserimento)
Route::resource('commandaw', 'App\Http\Controllers\CommandawController');
Route::delete('commandaw/{id}' ,'App\Http\Controllers\CommandawController@destroy');   // per cancellare la vecchia commanda di lavoro

// ------------------------   commandawriga  (per inserimento)
Route::resource('commandawriga', 'App\Http\Controllers\CommandawrigaController');
Route::get('commandawriga/getRigheCommandaw/{id}', 'App\Http\Controllers\CommandawrigaController@getRigheCommandaw');
Route::get('commandawrigatipo/getProdottiforTipologia/{tipo}' ,'App\Http\Controllers\CommandawrigaController@getProdottiforTipologia');
Route::post('commandawriga/{id}', 'App\Http\Controllers\CommandawrigaController@store');  // effettuare inserimento  da prodotti a commandarigaw
Route::get('commandawrigaord/getProdottiOrdinati/{id}', 'App\Http\Controllers\CommandawrigaController@getProdottiOrdinati');

// ------------------------   moneyw  (per gestire la cassa)
Route::resource('moneyw', 'App\Http\Controllers\MoneywController');
// ------------------------   moneyPayment  (per gestire log pagamenti)  da dismettere - sotituito da Moneypay
Route::resource('moneypayment', 'App\Http\Controllers\MoneypaymentController');
// ------------------------   moneypay  (per gestire log pagamenti)
Route::resource('moneypay', 'App\Http\Controllers\MoneypayController');
Route::get('moneypay/getMoneyforCommanda/{id}' , 'App\Http\Controllers\MoneypayController@getMoneyforCommanda');

// ------------------------   Giornata
Route::resource('giornata', 'App\Http\Controllers\GiornataController');
Route::get('giornata/getGiornateByManifId/{idmanif}', 'App\Http\Controllers\GiornataController@getGiornateByManifId');  //  ok   // [FedeleController::class, 'getFedeliByMessaId']
Route::get('giornata/getGiornateByManifIdFiltrato/{idmanif}/tipo/{tipo}' ,'App\Http\Controllers\GiornataController@getGiornateByManifIdFiltrato');  // ok
Route::get('giornataact/getGiornataactive' ,'App\Http\Controllers\GiornataController@getGiornataactive');
Route::get('giornataManif/lastid' ,'App\Http\Controllers\GiornataController@getGiornataLastId');
Route::get('giornata/getLastGiornataByManifId/{id}', 'App\Http\Controllers\GiornataController@getLastGiornataByManifId');



// ------------------------   Manifestazione
Route::resource('manif', 'App\Http\Controllers\ManifestazioneController');
Route::get('manifestazione/lastid' , 'App\Http\Controllers\ManifestazioneController@getManifestazioneLastId');
Route::get('manif/getManifestazionebyStato/{stato}', 'App\Http\Controllers\ManifestazioneController@getManifestazionebyStato'); 



// ---------------------------  Persone
Route::resource('persone', 'App\Http\Controllers\PersonaController');
Route::get('persone/getpersoneforRuolo/{id}', 'App\Http\Controllers\PersonaController@getpersoneforRuolo');  //  ok   // [FedeleController::class, 'getFedeliByMessaId']
Route::get('persone/getpersoneforRuoloFiltrato/{val1}/ruolo/{val2}' ,'App\Http\Controllers\PersonaController@getpersoneforRuoloFiltrato');
Route::get('persone/getpersonebyRuoloFiltrato/{val1}' ,'App\Http\Controllers\PersonaController@getpersonebyRuoloFiltrato');
Route::get('personeact/getpersoneActive' ,'App\Http\Controllers\PersonaController@getpersoneActive');

// ------------------------------------   Prodotto
Route::resource('prodotto', 'App\Http\Controllers\ProdottoController');
Route::get('prodotto/getProdottiforMenu/{aMenu}', 'App\Http\Controllers\ProdottoController@getProdottiforMenu');   //     [Prodotto::class,'getProdottiforMenu']
Route::get('prodotto/getProdottiforStato/{stato}' ,'App\Http\Controllers\ProdottoController@getProdottiforStato');
Route::get('prodotto/getProdottiforTipologia/{tipo}' ,'App\Http\Controllers\ProdottoController@getProdottiforTipologia');
Route::get('prodotto/getProdottiforCompetenza/{tipo}' ,'App\Http\Controllers\ProdottoController@getProdottiforCompetenza');
Route::get('prodotto/getProdottiforCategoria/{tipo}' ,'App\Http\Controllers\ProdottoController@getProdottiforCategoria');


// tabelle correlate
// ------------------------------------   Ruoli Giornalieri
Route::resource('truoloday_Old', 'App\Http\Controllers\T_RuoloDayController');   // da cancellare

// ------------------------------------   Ruoli Giornalieri  - nuova convenzione
Route::resource('truoloday', 'App\Http\Controllers\TRuolodayController');

// ------------------------------------   Categoria Prodotti
Route::resource('tcategoriaprod', 'App\Http\Controllers\T_CategoriaProdottoController');

// ------------------------------------   Competenza Prodotti
Route::resource('tcompetenzaprod', 'App\Http\Controllers\T_CompetenzaProdottoController');


// ------------------------------------   Tipologia
Route::resource('ttipologia', 'App\Http\Controllers\T_TipologiaController');
Route::get('ttipologia/getTipologieforStato/{stato}' ,'App\Http\Controllers\T_TipologiaController@getTipologieforStato');
// ------------------------------------   Users
Route::resource('user', 'App\Http\Controllers\UsersController');

// ------------------------------------   Cassaw
Route::resource('cassaw', 'App\Http\Controllers\CassawController');

// ------------------------------------  Tabella_t
Route::resource('tabellat', 'App\Http\Controllers\Tabella_tController');
// ------------------------------------  Tabella_tw
Route::resource('tabellatw', 'App\Http\Controllers\Tabella_twController');
// ------------------------------------  Tabella_twDett
Route::resource('tabellatwdett', 'App\Http\Controllers\Tabella_twDettController');

