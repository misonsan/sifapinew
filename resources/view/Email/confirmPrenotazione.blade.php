@component('mail::message')
# Conferma prenotazione serata a Sanfra In Festa

Preg. Sig/Sig.ra  {{$cognome}}  {{$name}}


Abbiamo ricevuto una sua cortese prenotazione per una serata presso il
Sanfra in Festa.
Se la richiesta è stata da Lei inoltrata, La preghiamo di provvedere a confermarla
cliccando sul bottone sottostante.
Qualora non fosse stata da Lei inviata La preghiamo di non dare seguito e eliminare questa mail,
informando eventualmente i responsabili della manifestazione.

Data prenotazione: {{$datapren}}

N.ro persone: {{$persone}}


il codice di prenotazione {{$codpren}} va inserito nella pagina di conferma.


@component('mail::button', ['url' => 'http://localhost:4200/responseConfirmPrenotazione?token='.$token])
Conferma Prenotazione
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent




