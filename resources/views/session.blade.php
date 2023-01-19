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
@elseif (Auth::guard('supermity')->check())
    <script>
        var user = {!! json_encode(Auth::guard('supermity')->user()) !!}
        user.type = 'Supermity';
    </script>
@endif