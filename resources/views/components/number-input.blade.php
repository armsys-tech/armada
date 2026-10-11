@props(['label' => null, 'description' => null, 'name' => null, 'value' => 0, 'min' => null, 'max' => null, 'step' => 1, 'size' => 'md', 'required' => false])
@php [$name, $key, $id, $invalid] = \Armada\Armada::field($attributes, $name, $errors ?? null); @endphp
<armada:field :label="$label" :description="$description" :for="$id" :name="$key" :required="$required">
    <div @class(['arm-input', 'arm-number', 'arm-input--'.$size => $size !== 'md']) @if ($invalid) data-invalid @endif style="max-width: 180px"
        {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}
        x-data="{ value: {{ (float) $value }}, set(v) { @if ($min !== null) v = Math.max({{ $min }}, v); @endif @if ($max !== null) v = Math.min({{ $max }}, v); @endif this.value = +v.toFixed(6) } }" x-modelable="value">
        <button type="button" class="arm-number__btn" x-on:click="set(value - {{ $step }})" aria-label="{{ __('Decrease') }}" @if ($min !== null) x-bind:disabled="value <= {{ $min }}" @endif><armada:icon name="minus" size="md" /></button>
        <input type="number" inputmode="decimal" x-bind:value="value" x-on:change="set(+$event.target.value || 0)"
            {{ $attributes->except(['class', 'style'])->whereDoesntStartWith(['wire:model', 'x-model'])->class('arm-input__control')->merge(['id' => $id, 'name' => $name, 'min' => $min, 'max' => $max, 'step' => $step]) }}>
        <button type="button" class="arm-number__btn" x-on:click="set(value + {{ $step }})" aria-label="{{ __('Increase') }}" @if ($max !== null) x-bind:disabled="value >= {{ $max }}" @endif><armada:icon name="plus" size="md" /></button>
    </div>
</armada:field>
