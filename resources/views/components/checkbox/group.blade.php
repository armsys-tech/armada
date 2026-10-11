@props(['label' => null, 'description' => null, 'name' => null, 'variant' => 'default', 'horizontal' => false]) {{-- default | cards --}}
<fieldset {{ $attributes->class('arm-fieldset') }}>
    @if ($label)<legend>{{ $label }}</legend>@endif
    @if ($description)<p class="arm-fieldset__description">{{ $description }}</p>@endif
    <div @class(['arm-choice-group' => $variant !== 'cards', 'arm-choice-group--horizontal' => $horizontal, 'arm-choice-cards' => $variant === 'cards'])>{{ $slot }}</div>
    @if ($name)<armada:error :name="$name" />@endif
</fieldset>
