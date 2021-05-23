@component('mail::message')
# Conferma Creazione Utente

Sono state create le sue credenziali per l'accesso all'applicazione.
La preghiamo di confermare la registrazione cliccando sul bottone

@component('mail::button', ['url' => 'http://localhost:4200/responseConfirmAccount?token='.$token])
Conferma Registrazione
@endcomponent

Grazie,<br>
{{ config('app.name') }}
@endcomponent
