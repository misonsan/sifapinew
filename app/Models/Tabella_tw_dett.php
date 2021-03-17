<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabella_tw_dett extends Model
{
    protected $fillable = ['id', 'idtab', 'idelTab', 'descrizione', 'stato', 'key_utenti_operation' ];

    
  
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
