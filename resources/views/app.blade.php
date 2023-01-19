<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Favicon --}}
        <link rel="icon" href="/img/oa-favicon.ico" type="image/ico">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- CSS --}}
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <title>{{ $title }}</title>
    </head>
    <body>
        <div class="main-content {{ $class }}" id="app">
            @if (!$dashboard)
                {{-- Login View --}}
                @if ($resource === 'login')
                    <login-page />

                {{-- Registration View --}}
                @elseif ($resource === 'registration')
                    <registration />
                @endif 
            @else
                {{-- Admin Views --}}
                @includeWhen(Auth::guard('web')->check(), 'routes.admin', ['resource' => $resource])

                {{-- Company Views --}}
                @includeWhen(Auth::guard('company')->check(), 'routes.company', ['resource' => $resource])

                {{-- Quarry Views --}}
                @includeWhen(Auth::guard('quarry')->check(), 'routes.quarry', ['resource' => $resource])

                {{-- Supermity Views --}}
                @includeWhen(Auth::guard('supermity')->check(), 'routes.supermity', ['resource' => $resource])

                {{-- Checker Views --}}
                @includeWhen(Auth::guard('checker')->check(), 'routes.checker', ['resource' => $resource])
            @endif
        </div>

        @include('session')

        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
