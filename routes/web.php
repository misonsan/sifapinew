<?php


use App\Http\Controllers\UsersController;
use App\Http\Controllers\{CommandaController, SendConfirmPrenotazioneController,ConfirmedPrenotazioneController};
use Illuminate\Support\Facades\Auth;     // inserito da Moreno per eliminare il problema
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

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
Route::post('commandadlt/truncate','App\Http\Controllers\CommandaController@truncate');
Route::resource('commanda', 'App\Http\Controllers\CommandaController');
Route::get('commanda/getCommandeByGiornataId/{id}', 'App\Http\Controllers\CommandaController@getCommandeByGiornataId');  //  ok   // [FedeleController::class, 'getFedeliByMessaId']
Route::get('commanda/getCommandeByGiornataIdFiltrato/{id}/tipo/{tipo}' ,'App\Http\Controllers\CommandaController@getCommandeByGiornataIdFiltrato');
Route::get('commandalast/lastid' , 'App\Http\Controllers\CommandaController@getCommandeLastId');
Route::get('commanda/getCommandeByGiornataeCompetenza/{id}/comp/{competenza}' ,'App\Http\Controllers\CommandaController@getCommandeByGiornataeCompetenza');
Route::get('commanda/getCommandeByGiornataeCompetenzaestato/{id}/comp/{competenza}/stato/{stato}' ,'App\Http\Controllers\CommandaController@getCommandeByGiornataeCompetenzaestato');
Route::get('commanda/getCommandeByGiornataeCompetenzaerigacomma/{id}/comp/{competenza}' ,'App\Http\Controllers\CommandaController@getCommandeByGiornataeCompetenzaerigacomma');

//Route::delete('commandadlt/deleteAll' ,'App\Http\Controllers\CommandaController@deleteAll');



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
Route::post('commandarigadlt/truncate','App\Http\Controllers\CommandarigaController@truncate');

// ------------------------   commandaw  (per inserimento)
Route::resource('commandaw', 'App\Http\Controllers\CommandawController');
Route::delete('commandaw/{id}' ,'App\Http\Controllers\CommandawController@destroy');   // per cancellare la vecchia commanda di lavoro

// ------------------------   commandawriga  (per inserimento)
Route::resource('commandawriga', 'App\Http\Controllers\CommandawrigaController');
Route::get('commandawriga/getRigheCommandaw/{id}', 'App\Http\Controllers\CommandawrigaController@getRigheCommandaw');
Route::get('commandawrigatipo/getProdottiforTipologia/{tipo}' ,'App\Http\Controllers\CommandawrigaController@getProdottiforTipologia');
Route::post('commandawriga/{id}', 'App\Http\Controllers\CommandawrigaController@store');  // effettuare inserimento  da prodotti a commandarigaw
Route::get('commandawrigaord/getProdottiOrdinati/{id}', 'App\Http\Controllers\CommandawrigaController@getProdottiOrdinati');

Route::get('commandawrigatipo/getProdottibyTipologiaComma/{tipo}/ncomma/{id}' ,'App\Http\Controllers\CommandawrigaController@getProdottibyTipologiacomma');





// ------------------------   moneyw  (per gestire la cassa)
Route::resource('moneyw', 'App\Http\Controllers\MoneywController');
// ------------------------   moneyPayment  (per gestire log pagamenti)  da dismettere - sotituito da Moneypay
Route::resource('moneypayment', 'App\Http\Controllers\MoneypaymentController');
// ------------------------   moneypay  (per gestire log pagamenti)
Route::resource('moneypay', 'App\Http\Controllers\MoneypayController');
Route::get('moneypay/getMoneyforCommanda/{id}' , 'App\Http\Controllers\MoneypayController@getMoneyforCommanda');
Route::post('moneypay/truncate','App\Http\Controllers\MoneypayController@truncate');


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
Route::get('manife/getManifestazioneActive', 'App\Http\Controllers\ManifestazioneController@getManifestazioneActive');


// ---------------------------  Persone
Route::resource('persone', 'App\Http\Controllers\PersonaController');
Route::get('persone/getpersoneforRuolo/{id}', 'App\Http\Controllers\PersonaController@getpersoneforRuolo');  //  ok   // [FedeleController::class, 'getFedeliByMessaId']
Route::get('persone/getpersoneforRuoloFiltrato/{val1}/ruolo/{val2}' ,'App\Http\Controllers\PersonaController@getpersoneforRuoloFiltrato');
Route::get('persone/getpersonebyRuoloFiltrato/{val1}' ,'App\Http\Controllers\PersonaController@getpersonebyRuoloFiltrato');
Route::get('personeact/getpersoneActive' ,'App\Http\Controllers\PersonaController@getpersoneActive');


Route::get('persone/getpersoneforTitolo/{val1}' ,'App\Http\Controllers\PersonaController@getpersoneforTitolo');
Route::get('persone/getpersoneforStato/{val1}' ,'App\Http\Controllers\PersonaController@getpersoneforStato');
Route::get('persone/getpersoneforLivello/{val1}' ,'App\Http\Controllers\PersonaController@getpersoneforLivello');
Route::get('personalast/lastid' , 'App\Http\Controllers\PersonaController@getPersonaLastId');
Route::post('persone/azzeraRuoloPersona', 'App\Http\Controllers\PersonaController@azzeraRuoloPersona');

// ------------------------------------   Prodotto
Route::resource('prodotto', 'App\Http\Controllers\ProdottoController');
Route::get('prodotto/getProdottiforMenu/{aMenu}', 'App\Http\Controllers\ProdottoController@getProdottiforMenu');   //     [Prodotto::class,'getProdottiforMenu']
Route::get('prodotto/getProdottiforStato/{stato}' ,'App\Http\Controllers\ProdottoController@getProdottiforStato');
Route::get('prodotto/getProdottiforTipologia/{tipo}' ,'App\Http\Controllers\ProdottoController@getProdottiforTipologia');
Route::get('prodotto/getProdottiforCompetenza/{tipo}' ,'App\Http\Controllers\ProdottoController@getProdottiforCompetenza');
Route::get('prodotto/getProdottiforCategoria/{tipo}' ,'App\Http\Controllers\ProdottoController@getProdottiforCategoria');
Route::get('prodottolast/lastid' , 'App\Http\Controllers\ProdottoController@getProdottoLastId');
// reimposto il carattere * su tutti i record per la tabella prodotto
Route::post('prodotto/updateamenuProdotto', 'App\Http\Controllers\ProdottoController@updateamenuProdotto');


// tabelle correlate
// ------------------------------------   Ruoli Giornalieri
Route::resource('truoloday_Old', 'App\Http\Controllers\T_RuoloDayController');   // da cancellare

// ------------------------------------   Ruoli Giornalieri  - nuova convenzione
Route::resource('truoloday', 'App\Http\Controllers\TRuolodayController');

// ------------------------------------   Ruoli Giornalieri  - nuova convenzione
Route::resource('truoloweb', 'App\Http\Controllers\T_RuoloWebController');

// ------------------------------------   Ruoli
Route::resource('truolo', 'App\Http\Controllers\T_RuoloController');

// ------------------------------------   Categoria Prodotti
Route::resource('tcategoriaprod', 'App\Http\Controllers\T_CategoriaProdottoController');

// ------------------------------------   Competenza Prodotti
Route::resource('tcompetenzaprod', 'App\Http\Controllers\T_CompetenzaProdottoController');

// ------------------------------------   Stato Prenotazione
Route::resource('tstatopren', 'App\Http\Controllers\T_StatoPrenotazioneController');

// ------------------------------------   Tipologia
Route::resource('ttipologia', 'App\Http\Controllers\T_TipologiaController');
Route::get('ttipologia/getTipologieforStato/{stato}' ,'App\Http\Controllers\T_TipologiaController@getTipologieforStato');

// ------------------------------------   Titolo
Route::resource('ttitolo', 'App\Http\Controllers\T_TitoloController');

// ------------------------------------   Users
Route::resource('user', 'App\Http\Controllers\UsersController');
Route::get('user/getbyemail/{id}', 'App\Http\Controllers\UsersController@getbyemail');
Route::get('users/getUserAnonimus', 'App\Http\Controllers\UsersController@getUserAnonimus');
Route::get('users/getUserSanfra', 'App\Http\Controllers\UsersController@getUserSanfra');




// tabella per richiesta conferma registrazione
Route::resource('regConfirm', 'App\Http\Controllers\RegisterConfirmedController');
Route::get('regConfirm/getbytoken/{token}', 'App\Http\Controllers\RegisterConfirmedController@getbytoken');

//Route::get('regConfirm/getbytokenemailpsw', 'App\Http\Controllers\RegisterConfirmedController@getRegConfirmbyTokenEmailPass');
// test

Route::get('regConfirm/getbytokenemailpsw/{token}/{email}/{password}', 'App\Http\Controllers\HelpController@getRegConfirmbyTokenEmailPass');







// ------------------------------------   Cassaw
Route::resource('cassaw', 'App\Http\Controllers\CassawController');

// ------------------------------------   Stato Persona
Route::resource('tstatopersona', 'App\Http\Controllers\T_StatoPersonaController');
// ------------------------------------   Stato Utente (User)
Route::resource('tstatouser', 'App\Http\Controllers\T_StatoUtenteController');
// ------------------------------------   Stato Prodotto
Route::resource('tstatoprodotto', 'App\Http\Controllers\T_StatoProdottoController');

// ------------------------------------  Tabella_t
Route::resource('tabellat', 'App\Http\Controllers\Tabella_tController');
// ------------------------------------  Tabella_tw
Route::resource('tabellatw', 'App\Http\Controllers\Tabella_twController');
// ------------------------------------  Tabella_twDett
Route::resource('tabellatwdett', 'App\Http\Controllers\Tabella_twDettController');
Route::get('tabellatwd/lastid' , 'App\Http\Controllers\Tabella_twDettController@getelemLastId');

// ------------------------------------  UserLevels
Route::resource('userlevel', 'App\Http\Controllers\UserLevelsController');

// -------------------------------------------  email
//Route::view('TestEmail', 'emails.TestMail', ['morenix'=>'Moreno']);

// --------------------  invio email

// funziona
Route::get('TestEmail', function () {
   /*
    $details = [
        'title' => 'mail from Misonsan mail',
        'body' => 'Questo è il test Mailing da SMTP  '
    ]; */
    Mail::to('ghisellini.moreno@libero.it')->send(new \App\Mail\TestEmail());
    echo "email inviata regolarmente !!!!";

});


Route::get('/hidran', function () {
     Mail::to('ghisellini.moreno@libero.it')->send(new \App\Mail\Moreno());
     echo "email inviata regolarmente !!";

 });




  // invio email per prenotazione serata a Sanfra
  Route::post('sendConfirmedPrenotazioneLink', [SendConfirmPrenotazioneController::class,'sendEmail']);
  Route::post('prenotazioneConfirmed', [ConfirmedPrenotazioneController::class,'prenotazione']);
  Route::get('prenotConfirm/getbyemaildatapre/{email}/{datapren}', [ConfirmedPrenotazioneController::class,'getbyemaildatapre']);    // 'App\Http\Controllers\ConfirmedPrenotazioneController@getbyemaildatapre'
  Route::get('prenotConfirm/getbyemail/{email}', [ConfirmedPrenotazioneController::class,'getbyemail']);
  Route::get('prenotConfirm/getbytoken/{token}', [ConfirmedPrenotazioneController::class,'getbytoken']);
  Route::get('prenotConfirm/getbytokencodpre/{token}/{codpren}', [ConfirmedPrenotazioneController::class,'getbytokencodpre']);
  Route::delete('prenotConfirm/destroyToken/{token}', [ConfirmedPrenotazioneController::class,'destroyToken']);
  Route::resource('prenotConfirm', 'App\Http\Controllers\ConfirmedPrenotazioneController');



  // prenotazioni
  Route::resource('prenotazione', 'App\Http\Controllers\PrenotazioneController');
  Route::get('prenot/getPrenotazionidaEvadere', 'App\Http\Controllers\PrenotazioneController@getPrenotazionidaEvadere');   // [PrenotazioneController::class,'getPrenotazinidaEvadere'
  Route::get('prenot/getPrenotazionidaEvaderebyday/{day}', 'App\Http\Controllers\PrenotazioneController@getPrenotazionidaEvaderebyday');
  Route::get('prenot/getPrenotazionibyStato/{stato}', 'App\Http\Controllers\PrenotazioneController@getPrenotazionibyStato');
  Route::get('prenot/getPrenotazionibyemail/{email}', 'App\Http\Controllers\PrenotazioneController@getPrenotazionibyemail');

