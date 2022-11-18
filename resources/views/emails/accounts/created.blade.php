<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Mail</title>

    </head>
    <body>
        <h1>Your name is: {{ $name }}</h1>
        <h1>Your password is: {{ $password }}</h1>
    </body>
</html>
