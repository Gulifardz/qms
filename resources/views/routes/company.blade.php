{{-- Drivers --}}
@if ($resource === 'drivers')
    <company-drivers />
@elseif ($resource === 'drivers-form' && !isset($id))
    <company-driver-form />
@elseif ($resource === 'drivers-form' && isset($id))
    <company-driver-form id="{{ $id }}" driver="{{ $driver }}" />
@elseif ($resource === 'drivers-qr')
    <company-driver-qr driver="{{ $driver }}" />

{{-- Trucks --}}
@elseif ($resource === 'trucks')
    <company-trucks />
@elseif ($resource === 'truck-form' && !isset($id))
    <company-truck-form />
@elseif ($resource === 'truck-form' && isset($id))
    <company-truck-form id="{{ $id }}" truck="{{ $truck }}" />

{{-- Logs --}}
@elseif ($resource === 'logs')
    <company-logs />
@endif