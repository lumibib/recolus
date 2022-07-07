@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-accent-focus text-sm font-medium leading-5 text-accent-focus focus:outline-none focus:border-accent-focus transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-neutral-content hover:text-accent-focus hover:border-accent-focus focus:outline-none focus:text-accent-focus focus:border-accent-focus transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
