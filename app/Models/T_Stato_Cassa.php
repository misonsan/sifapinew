<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class T_Stato_Cassa extends Model
{
         //  use HasFactory;

   protected $fillable = ['id', 'd_stato_cassa', 'tappo', 'key_utenti_operation' ];

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
