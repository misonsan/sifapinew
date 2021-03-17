<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giornata extends Model
{
    protected $fillable = ['id', 'dtGiornata', 'idManifestazione', 'stato', 'statoMagazzino', 'statoCassa', 'statoUtenti', 'operationCassa', 'i100', 'i050', 'i020', 'i010', 'i005', 'icontante', 'a100', 'a050', 'a020', 'a010', 'a005', 'acontante', 'f100', 'f050', 'f020', 'f010', 'f005', 'fcontfnte'
    , 'cassaInizio', 'cassaAttuale', 'cassaFinale', 'numTavoli', 'numUtenti', 'numCommande', 'impCommande', 'impCoperti', 'note', 'd_stato_utenti', 'd_operation_cassa', 'd_stato_cassa', 'd_stato_giornata', 'd_stato_magazzino' , 'key_utenti_operation' ];

/**
* The attributes that should be hidden for arrays.
*
* @var array
*/

protected $hidden = [

//  --- al momento non ci sono campi hidden
//  'password',
//  'remember_token',


];

/**
* The attributes that should be cast to native types.
*
* @var array
*/
protected $casts = [

'created_at' => 'datetime',
'updated_at' => 'datetime',
];

}
