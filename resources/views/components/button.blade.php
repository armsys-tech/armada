@props([
    'variant' => 'outline', // solid | soft | outline | ghost | plain
    'tone' => 'neutral',    // neutral | primary | success | info | warning | danger | purple | pink | orange | yellow | teal
    'size' => 'md',         // xs | sm | md | lg | xl
    'icon' => null,
    'iconTrailing' => null,
    'href' => null,
    'type' => 'button',
    'badge' => null,
    'loading' => false,     // Livewire adds data-loading by itself while a wire:click / wire:submit request runs
    'square' => null,       // null = automatic when the button has only an icon
    'pill' => false,
    'block' => false,
])
@php
    $square ??= $slot->isEmpty() && ($icon || $iconTrailing);
    $iconSize = $size === 'xl' ? 'lg' : $size;
    $tag = $href ? 'a' : 'button';
@endphp
<{{ $tag }}
    @if ($href) href="{{ $href }}" @else type="{{ $type }}" @endif
    {{ $attributes->class([
        'arm-btn',
        'arm-btn--'.$variant,
        'arm-btn--'.$size => $size !== 'md',
        'arm-btn--square' => $square,
        'arm-btn--pill' => $pill,
        'arm-btn--block' => $block,
    ])->merge(['data-tone' => $tone, 'data-loading' => $loading ?: null]) }}
>
    @if ($icon)<armada:icon :name="$icon" :size="$iconSize" />@endif
    @if ($slot->isNotEmpty())<span>{{ $slot }}</span>@endif
    @if ($badge !== null)<span class="arm-btn__badge">{{ $badge }}</span>@endif
    @if ($iconTrailing)<armada:icon :name="$iconTrailing" :size="$iconSize" />@endif
    <span class="arm-btn__spinner arm-spinner" aria-hidden="true"></span>
</{{ $tag }}>
