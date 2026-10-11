@props([
    'variant' => 'soft',  // soft | solid | outline
    'tone' => 'neutral',
    'size' => 'md',       // sm | md | lg
    'icon' => null,
    'dot' => false,
    'pulse' => false,
    'square' => false,
])
<span {{ $attributes->class([
    'arm-badge',
    'arm-badge--'.$variant => $variant !== 'soft',
    'arm-badge--'.$size => $size !== 'md',
    'arm-badge--square' => $square,
    'arm-badge--pulse' => $pulse,
])->merge(['data-tone' => $tone]) }}>
    @if ($dot)<span class="arm-badge__dot"></span>@endif
    @if ($icon)<armada:icon :name="$icon" />@endif
    {{ $slot }}
</span>
