<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commanda extends Model
{
       //  use HasFactory;

    protected $fillable = ['id', 'idSanfra', 'anagrafica_cliente', 'idGiornata', 'buonoPasto', 'numTavolo', 'numPersone', 'cassaAttuale', 'numProdotti', 'importoProdotti', 'importoCoperto', 'importodaPagare', 'importoPagato', 'resto', 'noteCommanda', 'stampaEseguita', 'stato', 'statoContabile', 'statoCucina', 'statoBevande', 'key_utenti_operation'
    , 'semaphore', 'delay' , 'd_stato_bevande', 'd_stato_commanda', 'd_stato_Contabile', 'd_stato_Cucina' ];

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
'dtCommanda' => 'datetime'
];

}
