{{-- Logs --}}
@if ($resource === 'logs')
    <supermity-logs />
@elseif ($resource === 'scanner')
    <supermity-scanner />
@endif