<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Moneyw extends Model
{
   // use HasFactory;
   protected $fillable = ['id', 'i100', 'i100Valore', 'i050', 'i050Valore', 'i020', 'i202Valore', 'i010', 'i010Valore', 'i005', 'i005Valore', 'icontante', 'incasso', 'r100', 'r100Valore', 'r050', 'r050Valore', 'r020', 'r020Valore', 'r010', 'r010Valore', 'r005', 'r005Valore', 'rcontante', 'resto', 'residuo'
   ];

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
