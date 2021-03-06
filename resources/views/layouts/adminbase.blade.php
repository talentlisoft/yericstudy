<!DOCTYPE html>
<html lang="cn" ng-app="studyapp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{url('/favicon.ico')}}">
    @yield('scripts')
    <script src="{{url('/js/angular-locale_zh-cn.js')}}"></script>
    @yield('styles')
    @yield('base')
    <title update-title>Yeric Study</title>
</head>
<body class="w-100">
    @yield('body')
    <load-indicator></load-indicator>
</body>
</html>
