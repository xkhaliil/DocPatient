@props(['active'])

@php
$classes = ($active ?? false)
            ? 'nav-link nav-link-active focus-ring'
            : 'nav-link focus-ring';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
