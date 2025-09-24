<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Load CSS first -->
    @vite('resources/css/app.css')
</head>
<body class="inertia-loading antialiased">
    @inertia

    <!-- Load JS after CSS -->
    @vite('resources/js/app.ts')
</body>
</html>
