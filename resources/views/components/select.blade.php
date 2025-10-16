@props(['disabled' => false, 'options' => [], 'selected' => null, 'placeholder' => null])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm']) !!}>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    
    @if(!empty($options))
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    @else
        {{ $slot }}
    @endif
</select>
