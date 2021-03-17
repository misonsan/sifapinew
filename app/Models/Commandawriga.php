<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commandawriga extends Model
{

    public $timestamps = false;


	protected $fillable = ['id', 'idCommanda', 'idProdotto', 'qta', 'descrizione_prodotto', 'tipologia', 'categoria', 'competenza', 'disponibile_Day', 'scorta_minima', 'prezzo_day', 'photo', 'd_categoria'];

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
     'prezzo_day' => 'float',
    ];

}
