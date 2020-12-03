<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restriction tentatives</title>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
</head>
<body>

    <div id="flashMessage"></div>

    <form method="POST" id="login">
        <input id="email" type="email" placeholder="email">
        <input id="password" type="password" placeholder="mot de passe">
        <button type="submit"> Se connecter </button>
    </form>
    
    <script src="{{ asset('js/script.js')}}"> </script>
</body>
</html>