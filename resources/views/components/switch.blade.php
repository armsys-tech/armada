@props(['label' => null, 'description' => null, 'name' => null, 'checked' => false, 'align' => 'start', 'tone' => null, 'size' => 'md']) {{-- align: start | end (label left, switch right) --}}
@php [$name, $key, $id, $invalid] = \Armada\Armada::field($attributes, $name, $errors ?? null); @endphp
<label {{ $attributes->only(['class', 'style'])->class(['arm-check', 'arm-check--switch', 'arm-check--end' => $align === 'end']) }} for="{{ $id }}">
    <input type="checkbox" role="switch" {{ $attributes->except(['class', 'style'])->class(['arm-switch', 'arm-switch--lg' => $size === 'lg'])->merge([
        'id' => $id, 'name' => $name, 'value' => '1', 'checked' => $checked, 'data-tone' => $tone,
    ]) }}>
    @if ($label || $description)
        <span class="arm-check__text">
            @if ($label)<span class="arm-check__label">{{ $label }}</span>@endif
            @if ($description)<span class="arm-check__description">{{ $description }}</span>@endif
        </span>
    @endif
</label>
