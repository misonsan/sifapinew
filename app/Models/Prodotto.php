<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodotto extends Model
{
      // use HasFactory;

   protected $fillable = ['id', 'descrizione_prodotto', 'stato', 'tipologia', 'categoria', 'competenza', 'disponibile', 'disponibile_Day', 'scorta_minima', 'aMenu',  'prezzo', 'prezzo_day', 'qta_vendute', 'residuo', 'path_image', 'key_utenti_operation' , 'd_stato_prodotto', 'd_competenza', 'd_categoria', 'd_tipologia'];


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
   'prezzo' => 'float',
   'prezzo_day' => 'float',
   ];

}
