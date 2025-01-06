<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        @vite(['resources/css/reset.css', 'resources/css/app.css'])
    </head>
    <body class="login">
        <form class="login__box" action="">
            <label for="user">Utilisateur</label>
            <select name="" id="">
                <option value="1">client@exemple.be</option>
                <option value="2">vip@exemple.be</option>
                <option value="3">grossiste@exemple.be</option>
            </select>
            <button class="button">Se connecter</button>
        </form>
    </body>
</html>
