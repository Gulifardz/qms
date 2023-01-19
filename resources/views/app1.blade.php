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
                @if ($resource === 'login')
                    <login-page />
                @elseif ($resource === 'registration')
                    <registration />
                @endif 
            @else
                @if ($resource === 'quarries')
                    <quarries />
                @elseif ($resource === 'quarry-companies')
                    <quarry-companies quarry_id="{{ $quarry_id }}" />
                @elseif ($resource === 'quarry-products')
                    <quarry-products quarry_id="{{ $quarry_id }}" />

                @elseif ($resource === 'companies')
                    <companies />

                @elseif ($resource === 'products')
                    <products />

                @elseif ($resource === 'checkers')
                    <checkers />
                @elseif ($resource === 'checkers-form' && !isset($id))
                    <checkers-form />
                @elseif ($resource === 'checkers-form' && isset($id))
                    <checkers-form id="{{ $id }}" checker="{{ $checker }}" />
                @elseif ($resource === 'checkers-records')
                    <checkers-records id="{{ $id }}" checker="{{ $checker }}" />

                @elseif ($resource === 'drivers')
                    <drivers />
                @elseif ($resource === 'drivers-form' && !isset($id))
                    <drivers-form />
                @elseif ($resource === 'drivers-form' && isset($id))
                    <drivers-form id="{{ $id }}" driver="{{ $driver }}" />
                @elseif ($resource === 'drivers-qr')
                    <drivers-qr driver="{{ $driver }}" />

                @elseif ($resource === 'trucks')
                    <trucks />
                @elseif ($resource === 'trucks-form' && !isset($id))
                    <trucks-form />
                @elseif ($resource === 'trucks-form' && isset($id))
                    <trucks-form id="{{ $id }}" truck="{{ $truck }}" />

                @elseif ($resource === 'logs')
                    <logs />
                @elseif ($resource === 'logs-scan')
                    <logs-scan />
                @endif  
            @endif
        </div>

        @if (Auth::guard('web')->check())
            <script>
                var user = {!! json_encode(Auth::guard('web')->user()) !!}
                user.type = 'Admin';
            </script>
        @elseif (Auth::guard('company')->check())
            <script>
                var user = {!! json_encode(Auth::guard('company')->user()) !!}
                user.type = 'Company';
            </script>
        @elseif (Auth::guard('quarry')->check())
            <script>
                var user = {!! json_encode(Auth::guard('quarry')->user()) !!}
                user.type = 'Quarry';
            </script>
        @elseif (Auth::guard('checker')->check())
            <script>
                var user = {!! json_encode(Auth::guard('checker')->user()) !!}
                user.type = 'Checker';
            </script>
        @endif

        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
