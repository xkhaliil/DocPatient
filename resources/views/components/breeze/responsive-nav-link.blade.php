@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-blue-600 text-start text-base font-semibold text-blue-700 bg-blue-50 focus-ring transition'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-semibold text-slate-600 hover:text-blue-700 hover:bg-blue-50 hover:border-blue-200 focus-ring transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
