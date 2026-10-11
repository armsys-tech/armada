@props([
    'label' => null, 'description' => null, 'name' => null,
    'min' => 0, 'max' => 100, 'step' => 1,
    'value' => null,      // number, or [from, to] with range
    'range' => false,
    'showValue' => true,
    'suffix' => '',
    'ticks' => null,      // e.g. [0, 25, 50, 75, 100]
])
@php
    [$name, $key, $id] = \Armada\Armada::field($attributes, $name, $errors ?? null);
    $value ??= $range ? [$min, $max] : ($min + $max) / 2;
@endphp
<armada:field :description="$description" :name="$key">
    <div style="display:grid;gap:8px" {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}
        x-data="{ value: @js($value), min: {{ $min }}, max: {{ $max }}, pct(v) { return (v - this.min) / (this.max - this.min) * 100 + '%' } }" x-modelable="value">
        @if ($label || $showValue)
            <div style="display:flex;justify-content:space-between;gap:8px">
                @if ($label)<armada:label :for="$id">{{ $label }}</armada:label>@endif
                @if ($showValue)<span class="arm-slider__value" x-text="{{ $range ? "value[0] + '$suffix – ' + value[1] + '$suffix'" : "value + '$suffix'" }}"></span>@endif
            </div>
        @endif
        @if ($range)
            <div class="arm-slider arm-slider--range" x-bind:style="`--from: ${pct(value[0])}; --value: ${pct(value[1])}`">
                <div class="arm-slider__track"><div class="arm-slider__fill"></div></div>
                <input type="range" id="{{ $id }}" min="{{ $min }}" max="{{ $max }}" step="{{ $step }}" x-bind:value="value[0]" aria-label="{{ __('Minimum') }}"
                    x-on:input="value = [Math.min(+$event.target.value, value[1]), value[1]]; $event.target.value = value[0]" @if ($name) name="{{ $name }}[]" @endif>
                <input type="range" min="{{ $min }}" max="{{ $max }}" step="{{ $step }}" x-bind:value="value[1]" aria-label="{{ __('Maximum') }}"
                    x-on:input="value = [value[0], Math.max(+$event.target.value, value[0])]; $event.target.value = value[1]" @if ($name) name="{{ $name }}[]" @endif>
            </div>
        @else
            <div class="arm-slider" x-bind:style="`--value: ${pct(value)}`">
                <div class="arm-slider__track"><div class="arm-slider__fill"></div></div>
                <input type="range" id="{{ $id }}" min="{{ $min }}" max="{{ $max }}" step="{{ $step }}" x-model.number="value" @if ($name) name="{{ $name }}" @endif {{ $attributes->except(['class', 'style'])->whereDoesntStartWith(['wire:model', 'x-model']) }}>
            </div>
        @endif
        @if ($ticks)
            <div class="arm-slider__ticks">@foreach ($ticks as $t)<span>{{ $t }}{{ $suffix }}</span>@endforeach</div>
        @endif
    </div>
</armada:field>
