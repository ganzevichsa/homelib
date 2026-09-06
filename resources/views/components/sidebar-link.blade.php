@props(['active' => false])

@php
$classes = $active
    ? 'flex items-center rounded-lg px-3 py-2 text-sm font-medium bg-gray-900 text-white dark:bg-white/10 dark:text-white'
    : 'flex items-center rounded-lg px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-100';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
