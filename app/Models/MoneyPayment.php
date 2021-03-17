<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MoneyPayment extends Model
{
    //use HasFactory;
    protected $fillable = ['id', 'idGiornata', 'idCommanda', 'taglia', 'nro_pezzi_entrata', 'nro_pezzi_uscita', 'imp_entrata', 'imp_uscita', 'key_utenti_operation', 'd_taglia'];

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
