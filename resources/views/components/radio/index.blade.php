@props(['label' => null, 'description' => null, 'name' => null, 'value' => null, 'checked' => false, 'card' => false, 'icon' => null])
@php
    [$name, $key, $id, $invalid] = \Armada\Armada::field($attributes, $name, $errors ?? null);
    $id .= '-'.\Illuminate\Support\Str::slug((string) $value);
@endphp
<label @class(['arm-check' => ! $card, 'arm-choice-card' => $card]) for="{{ $id }}">
    @if ($card && $icon)<armada:icon :name="$icon" />@endif
    <input type="radio" {{ $attributes->class('arm-radio')->merge(['id' => $id, 'name' => $name, 'value' => $value, 'checked' => $checked, 'aria-invalid' => $invalid ? 'true' : null]) }}>
    @if ($label || $description)
        <span class="arm-check__text">
            @if ($label)<span class="arm-check__label">{{ $label }}</span>@endif
            @if ($description)<span class="arm-check__description">{{ $description }}</span>@endif
        </span>
    @endif
</label>
