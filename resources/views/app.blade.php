<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ env('APP_NAME') }}</title>

    @vite('resources/css/app.css')
</head>

<body class="antialiased" id="app">
    @vite('resources/js/app.js')
</body>

</html>
