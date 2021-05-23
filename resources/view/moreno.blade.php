


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
    <p>ciao </p>

    <p>abbiamo ricevuto una richiesta di reset password per la email </p>
    <p>se non sei stato tu a richiederla, trascura questa mail e cortesemente</p>
    <p>informa il circolo di questa comunicazione.</p>
    <p>Qualora invece la richiesta sia stata effettuata da te, ti preghiamo di</p>
    <p>cliccare sul bottone sottostante per dare seguito alla richiesta</p>

    @component('mail::button', ['url' => 'http:www.google.it'])
        Reset Password
    @endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

</body>
</html>
