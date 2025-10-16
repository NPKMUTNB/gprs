@props(['disabled' => false, 'rows' => 4])

<textarea rows="{{ $rows }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm']) !!}>{{ $slot }}</textarea>
