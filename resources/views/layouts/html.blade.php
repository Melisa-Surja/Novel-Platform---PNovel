<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    @if(isset($title) && !empty($title))<title>{{$title}} - {{ config('app.name') }}</title>@endif
    @stack('seo')

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('head_scripts_before')
    
    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600&display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet"> 
    {{-- <link rel="stylesheet" href="https://rsms.me/inter/inter.css"> --}}
    {{-- CSS --}}
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

    @stack('head_scripts')
</head>

<body @isset($body_class) class="{{ $body_class }}" @endisset>
    <div id="app">
            @yield('layout_content')
        </container>
    </div>

    @stack('footer_scripts_before')

    <script src="{{ mix('/js/app.js') }}"></script>
    
    @stack('footer_scripts')
</body>

</html>
