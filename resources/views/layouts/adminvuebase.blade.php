<!DOCTYPE html>
<html lang="cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{url('/favicon.ico')}}">

    @yield('styles')
    @yield('base')
    <title>Yeric Study</title>
</head>
<body class="w-100">
    @yield('body')
    @yield('scripts')
</body>
</html>
