@props(['type' => 'button', 'variant' => 'primary'])

@php
    $classes = match($variant) {
        'primary' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 text-white',
        'secondary' => 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500 text-white',
        'success' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500 text-white',
        'danger' => 'bg-red-600 hover:bg-red-700 focus:ring-red-500 text-white',
        'warning' => 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500 text-white',
        'outline' => 'bg-white hover:bg-gray-50 border border-gray-300 text-gray-700',
        default => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 text-white',
    };
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 {$classes}"]) }}>
    {{ $slot }}
</button>
