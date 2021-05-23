<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Prenotazione_confirmed extends Model implements JWTSubject
{
     // use HasFactory;

   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cognome',
        'name',
        'telefono',
        'datapren',
        'persone',
        'email',
        'codpren',
        'password',
        'token',
        ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

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
     *
     *  * ------  campi aggiuntivi che mi faccio rilasciare quando effettuo la chiamata a jwt per autenticazione
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
                'email' => $this->email,
                'token' => $this->token,
                'created_at' => $this->created_at
        ];
    }

}
