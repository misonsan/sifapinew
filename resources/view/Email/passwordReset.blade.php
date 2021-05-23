@component('mail::message')
# Cambio Password

Abbiamo ricevuto questa richiesta di cambio password.
Se hai effettuato tu questa richiesta,
clicca sul bottone per eseguire il cambio password

@component('mail::button',['url' => 'http://localhost:4200/responseReset?token='.$token])
reset Password
@endcomponent

Grazie,<br>
{{ config('app.name') }}
@endcomponent
