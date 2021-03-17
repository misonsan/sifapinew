<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $c = [



/*   tutte le rotte sono protette
        'commanda/*',
        'commandaw/*',
        'commandawriga/*',
        'giornata/*',
        'manif/*',
        'persone/*',
        'prodotto/*',
        'truoloday/*',
*/
    ];






}
