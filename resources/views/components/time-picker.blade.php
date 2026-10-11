@props([
    'label' => null, 'description' => null, 'name' => null,
    'value' => null,        // 'HH:MM' (24h)
    'step' => 15,           // minute step in the list
    'min' => null, 'max' => null,   // 'HH:MM'
    'hour12' => false,      // show 2:30 PM instead of 14:30 (value stays 24h)
    'placeholder' => null,
    'required' => false,
])
@php [$name, $key, $id, $invalid] = \Armada\Armada::field($attributes, $name, $errors ?? null); @endphp
<armada:field :label="$label" :description="$description" :name="$key" :required="$required">
    <div class="arm-combobox" {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}
        x-data="armTimePicker({ value: @js($value), step: {{ (int) $step }}, min: @js($min), max: @js($max), hour12: @js($hour12) })" x-modelable="value"
        x-on:keydown.escape="open = false" x-on:click.outside="open = false">
        <button type="button" {{ $attributes->only(['class', 'style'])->class(['arm-input', 'arm-combobox__trigger'])->merge(['data-invalid' => $invalid ?: null, 'id' => $id]) }} x-on:click="toggle()" aria-haspopup="listbox">
            <armada:icon name="clock" size="md" class="arm-input__icon" />
            <span class="arm-input__control"><span x-text="value ? label(value) : @js($placeholder ?? __('Pick a time'))" x-bind:class="{ 'arm-combobox__placeholder': ! value }">{{ $placeholder ?? __('Pick a time') }}</span></span>
            <armada:icon name="chevrons-up-down" size="md" class="arm-input__icon" />
        </button>
        @if ($name)<input type="hidden" name="{{ $name }}" x-bind:value="value">@endif
        <template x-if="open"><div class="arm-popover-backdrop" x-on:click="open = false"></div></template>
        <div class="arm-popover arm-popover--sheet arm-time" x-show="open" x-cloak>
            <div class="arm-listbox arm-time__list" role="listbox" x-ref="list">
                <template x-for="t in options()" :key="t">
                    <button type="button" class="arm-option" role="option" x-bind:aria-selected="t === value" x-on:click="value = t; open = false">
                        <span x-text="label(t)"></span><armada:icon name="check" size="md" class="arm-option__check" />
                    </button>
                </template>
            </div>
        </div>
    </div>
</armada:field>
