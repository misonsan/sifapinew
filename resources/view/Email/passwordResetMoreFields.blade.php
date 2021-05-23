@component('mail::message')
# Introduction

The body of your message.





<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Reset Password con molte variabili</title>
  <base href="/">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="favicon.ico">

</head>
<body>
    <p>ciao <strong>{{$datamail['cognome']}}&nbsp;{{$datamail['nome']}}</strong></p>

    <p>abbiamo ricevuto una richiesta di reset password per la email {{$datamail['email']}}</p>
    <p>se non sei stato tu a richiederla, trascura questa mail e cortesemente</p>
    <p>informa il circolo di questa comunicazione.</p>
    <p>Qualora invece la richiesta sia stata effettuata da te, ti preghiamo di</p>
    <p>cliccare sul bottone sottostante per dare seguito alla richiesta</p>

    @component('mail::button', ['url' => 'http://localhost:4200/responseReset?token='.$datamail['token']])
        Reset Password
    @endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

</body>
</html>
