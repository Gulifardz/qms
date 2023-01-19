{{-- Products --}}
@if ($resource === 'products')
    <quarry-products />
@elseif ($resource === 'product-form' && !isset($id))
    <quarry-product-form />
@elseif ($resource === 'product-form' && isset($id))
    <quarry-product-form id="{{ $id }}" product="{{ $product }}" />

{{-- Checkers --}}
@elseif ($resource === 'checkers')
    <quarry-checkers />

{{-- Supermities --}}
@elseif ($resource === 'supermities')
    <quarry-supermities />

{{-- Logs --}}
@elseif ($resource === 'logs')
    <quarry-logs />
@endif