<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commandaw extends Model
{
         //  use HasFactory;

     public $timestamps = false;

	protected $fillable = ['id', 'idSanfra', 'anagrafica_cliente', 'idGiornata', 'stato', 'buonoPasto', 'numTavolo', 'numPersone', 'numProdotti', 'importoProdotti', 'importoCoperto', 'importodaPagare', 'dtCommanda', 'importoPagato', 'resto', 'noteCommanda', 'stampaEseguita', 'd_stato_commanda' ];

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
