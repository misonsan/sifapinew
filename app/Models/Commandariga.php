<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commandariga extends Model
{
    //use HasFactory;

    public $timestamps = false;

	protected $fillable = ['id', 'idCommanda', 'idProdotto', 'stato', 'prezzo', 'categoria', 'competenza', 'descrizione_prodotto', 'qta_ord', 'flag_lavorazione', 'flag_consegna', 'note_riga', 'd_stato_riga_commanda', 'd_Categoria', 'key_utenti_operation', 'd_stato_lavorazione', 'd_stato_consegna'
     , 'delayLavorazione', 'semaphoreLavorazione', 'delayConsegna', 'semaphoreConsegna'];


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
     'ora_inizio' => 'datetime',
     'ora_lavorazione' => 'datetime',
     'ora_consegna' => 'datetime',

    ];
}
