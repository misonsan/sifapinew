<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable implements JWTSubject
{
     use HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'cognome',
        'nome',
        'idStato',
        'username',
        'password',
        'email',
        'idRuolo',
        'idRuolo_Day',
        'idruoloweb',
        'noteUtente',
        'photo',
        'd_ruolo',
        'd_ruolo_day',
        'd_Stato_Utente',
        'd_ruolo_web',
        'key_utenti_operation'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
       'email_verified_at' => 'datetime',
       'created_at' => 'datetime',
       'updated_at' => 'datetime',
    ];

 /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     * ------  campi aggiuntivi che mi faccio rilasciare quando effettuo la chiamata a jwt per autenticazione
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return ['username' => $this->username,
                'cognome' => $this->cognome,
                'email' => $this->email,
                'ruolo' => $this->idRuolo_Day,
                'id' => $this->id
                ];

    }

}
