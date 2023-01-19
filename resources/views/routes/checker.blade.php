{{-- Logs --}}
@if ($resource === 'logs')
    <checker-logs />
@elseif ($resource === 'scanner')
    <checker-scanner />
@endif