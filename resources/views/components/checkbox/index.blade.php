@props(['label' => null, 'description' => null, 'name' => null, 'value' => null, 'checked' => false, 'indeterminate' => false, 'card' => false, 'icon' => null])
@php [$name, $key, $id, $invalid] = \Armada\Armada::field($attributes, $name, $errors ?? null); @endphp
<label @class(['arm-check' => ! $card, 'arm-choice-card' => $card]) for="{{ $id }}{{ $value !== null ? '-'.\Illuminate\Support\Str::slug((string) $value) : '' }}">
    @if ($card && $icon)<armada:icon :name="$icon" />@endif
    <input type="checkbox" {{ $attributes->class('arm-checkbox')->merge([
        'id' => $id.($value !== null ? '-'.\Illuminate\Support\Str::slug((string) $value) : ''),
        'name' => $name, 'value' => $value, 'checked' => $checked, 'aria-invalid' => $invalid ? 'true' : null,
    ]) }} @if ($indeterminate) x-data x-init="$el.indeterminate = true" @endif>
    @if ($label || $description)
        <span class="arm-check__text">
            @if ($label)<span class="arm-check__label">{{ $label }}</span>@endif
            @if ($description)<span class="arm-check__description">{{ $description }}</span>@endif
        </span>
    @endif
</label>
