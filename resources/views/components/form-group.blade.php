@props(['label' => null, 'name' => null, 'required' => false, 'error' => null])

<div {{ $attributes->merge(['class' => 'mb-4']) }}>
    @if($label)
        <x-input-label :for="$name" :value="$label . ($required ? ' *' : '')" />
    @endif
    
    <div class="mt-1">
        {{ $slot }}
    </div>
    
    @if($error || ($name && $errors->has($name)))
        <x-input-error :messages="$error ?? $errors->get($name)" class="mt-2" />
    @endif
</div>
