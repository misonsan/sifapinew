<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;




class Cassaw extends Model
{
   // use HasFactory;
   protected $fillable = ['id', 'idDay', 'dtGiornata', 'statoCassa', 'i100', 'i050', 'i020', 'i010', 'i005', 'monete', 'i100Valore', 'i050Valore', 'i020Valore', 'i010Valore', 'i005Valore', 'totale'];

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
