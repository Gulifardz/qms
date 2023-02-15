{{-- Dashboard --}}
@if ($resource === 'dashboard')
    <admin-dashboard />

{{-- Quarries --}}
@elseif ($resource === 'quarries')
    <admin-quarries />
@elseif ($resource === 'quarry-form')
    <admin-quarry-form quarry="{{ $quarry }}" />
@elseif ($resource === 'quarry-companies')
    <admin-quarry-companies quarry="{{ $quarry }}" />

{{-- Companies --}}
@elseif ($resource === 'companies')
    <admin-companies />
@elseif ($resource === 'company-form')
    <admin-company-form company="{{ $company }}" />

{{-- Truck Categories --}}
@elseif ($resource === 'truck-categories')
    <admin-truck-categories />

{{-- Products --}}
@elseif ($resource === 'products')
    <admin-products />

{{-- Checkers --}}
@elseif ($resource === 'checkers')
    <admin-checkers />
@elseif ($resource === 'checkers-form' && !isset($id))
    <admin-checker-form />
@elseif ($resource === 'checkers-form' && isset($id))
    <admin-checker-form id="{{ $id }}" checker="{{ $checker }}" />

{{-- Supermities --}}
@elseif ($resource === 'supermities')
    <admin-supermities />
@elseif ($resource === 'supermity-form' && !isset($id))
    <admin-supermity-form />
@elseif ($resource === 'supermity-form' && isset($id))
    <admin-supermity-form id="{{ $id }}" supermity="{{ $supermity }}" />
@endif