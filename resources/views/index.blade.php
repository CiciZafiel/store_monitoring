<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Store Monitoring</title>
    {{-- @vite('resources/sass/app.scss','resources/js/app.js') --}}
    @vite(['resources/sass/app.scss','resources/css/app.css', 'resources/js/app.js'])  
    </head>
    <body class="antialiased">
        <div id="app">

        </div>
    </body>
</html>
