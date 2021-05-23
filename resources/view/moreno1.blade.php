
@component('mail::message')
# Cambio Password

Abbiamo ricevuto questa richiesta di cambio password.
Se hai effettuato tu questa richiesta,
clicca sul bottone per eseguire il cambio password

@component('mail::button',['url' => 'http:www.google.it'])
reset Password
@endcomponent

Grazie,<br>
{{ config('app.name') }}
