@props(['value' => 0, 'max' => 5, 'readonly' => false, 'size' => null, 'icon' => 'star', 'showValue' => false])
<div {{ $attributes->whereDoesntStartWith('wire:model')->class('arm-rating')->merge(['data-readonly' => $readonly ?: null, 'style' => $size ? "--s: $size" : null]) }}
    x-data="{ value: {{ (float) $value }}, hover: 0 }" x-modelable="value" {{ $attributes->whereStartsWith('wire:model') }}
    role="{{ $readonly ? 'img' : 'radiogroup' }}" aria-label="{{ $value }} / {{ $max }}">
    @for ($i = 1; $i <= $max; $i++)
        <button type="button" class="arm-rating__star" @if ($readonly) tabindex="-1" disabled @endif
            x-bind:class="{ 'is-on': (hover || value) >= {{ $i }} }"
            @unless ($readonly) x-on:click="value = value === {{ $i }} ? 0 : {{ $i }}" x-on:mouseenter="hover = {{ $i }}" x-on:mouseleave="hover = 0" @endunless
            aria-label="{{ $i }}"><armada:icon :name="$icon" /></button>
    @endfor
    @if ($showValue)<span class="arm-rating__value" x-text="value">{{ $value }}</span>@endif
</div>
