@props([
    'label' => null, 'description' => null, 'name' => null,
    'value' => null,        // 'Y-m-d', or ['start' => …, 'end' => …] with mode="range"
    'mode' => 'single',     // single | range
    'min' => null, 'max' => null,
    'inline' => false,      // calendar only, no input
    'presets' => false,     // range shortcuts
    'placeholder' => null,
    'locale' => null,
    'required' => false,
])
@php
    [$name, $key, $id, $invalid] = \Armada\Armada::field($attributes, $name, $errors ?? null);
    $locale ??= str_replace('_', '-', app()->getLocale());
    $value ??= $mode === 'range' ? ['start' => null, 'end' => null] : null;
@endphp
<armada:field :label="$label" :description="$description" :name="$key" :required="$required">
    <div class="arm-combobox" {{ $attributes->whereStartsWith(['wire:model', 'x-model']) }}
        x-data="armDatePicker({ value: @js($value), mode: @js($mode), min: @js($min), max: @js($max), locale: @js($locale), placeholder: @js($placeholder ?? __('Pick a date')) })" x-modelable="value"
        x-on:keydown.escape="open = false" x-on:click.outside="open = false">
        @unless ($inline)
            <button type="button" {{ $attributes->only(['class', 'style'])->class(['arm-input', 'arm-combobox__trigger'])->merge(['data-invalid' => $invalid ?: null, 'id' => $id]) }} x-on:click="open = ! open" aria-haspopup="dialog">
                <armada:icon name="calendar" size="md" class="arm-input__icon" />
                <span class="arm-input__control"><span x-text="display()" x-bind:class="{ 'arm-combobox__placeholder': ! hasValue() }">{{ $placeholder ?? __('Pick a date') }}</span></span>
            </button>
            <template x-if="open"><div class="arm-popover-backdrop" x-on:click="open = false"></div></template>
        @endunless
        <div @class(['arm-popover arm-popover--sheet' => ! $inline]) @unless ($inline) x-show="open" x-cloak @endunless>
            <div @class(['arm-calendar', 'arm-calendar--inline' => $inline])>
                <div class="arm-calendar__head">
                    <armada:button variant="ghost" size="sm" icon="chevron-left" :aria-label="__('Previous month')" x-on:click="shift(-1)" />
                    <div class="arm-calendar__title" x-text="title()"></div>
                    <armada:button variant="ghost" size="sm" icon="chevron-right" :aria-label="__('Next month')" x-on:click="shift(1)" />
                </div>
                <div class="arm-calendar__grid">
                    <template x-for="d in weekdays()"><div class="arm-calendar__dow" x-text="d"></div></template>
                    <template x-for="day in days()" :key="day.iso">
                        <button type="button" class="arm-calendar__day" x-text="day.n" x-on:click="pick(day.iso)" x-bind:disabled="day.disabled"
                            x-bind:class="{ 'is-outside': day.outside, 'is-today': day.today, 'is-selected': isSelected(day.iso), 'is-in-range': inRange(day.iso) }"></button>
                    </template>
                </div>
            </div>
            @if ($presets && $mode === 'range')
                <div class="arm-calendar__presets">
                    <armada:button size="xs" x-on:click="preset(7)">{{ __('Last 7 days') }}</armada:button>
                    <armada:button size="xs" x-on:click="preset(30)">{{ __('Last 30 days') }}</armada:button>
                    <armada:button size="xs" x-on:click="preset(90)">{{ __('Last 90 days') }}</armada:button>
                </div>
            @endif
        </div>
        @if ($name)
            @if ($mode === 'range')
                <input type="hidden" name="{{ $name }}[start]" x-bind:value="value.start"><input type="hidden" name="{{ $name }}[end]" x-bind:value="value.end">
            @else
                <input type="hidden" name="{{ $name }}" x-bind:value="value">
            @endif
        @endif
    </div>
</armada:field>
