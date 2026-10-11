@props(['name' => null, 'options' => [], 'value' => null, 'block' => false]) {{-- options like <armada:select>; each may carry 'icon' --}}
@php [$name] = \Armada\Armada::field($attributes, $name, $errors ?? null); $name ??= 'arm-toggle-'.\Illuminate\Support\Str::random(6); @endphp
<div {{ $attributes->only(['class', 'style'])->class(['arm-toggle-group', 'arm-toggle-group--block' => $block]) }} role="radiogroup">
    @foreach (\Armada\Armada::options($options) as $opt)
        <label>
            <input type="radio" name="{{ $name }}" value="{{ $opt['value'] }}" @checked((string) $opt['value'] === (string) $value) {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}>
            @if (! empty($opt['icon']))<armada:icon :name="$opt['icon']" size="md" />@endif{{ $opt['label'] }}
        </label>
    @endforeach
</div>
