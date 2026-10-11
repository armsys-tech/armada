@props([
    'src' => null,
    'name' => null,     // initials + a stable colour from the name
    'icon' => null,
    'size' => 'md',     // xs | sm | md | lg | xl | 2xl
    'square' => false,
    'status' => null,   // online | busy | away | offline
    'ring' => false,
    'badge' => null,
])
@php
    $initials = collect(preg_split('/\s+/', trim((string) $name)))->filter()->take(2)->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->join('');
    $hues = ['#4d7cfe', '#8b5cf6', '#10b981', '#f97316', '#f43f5e', '#06b6d4', '#d946ef', '#eab308'];
    $bg = $name && ! $src ? $hues[crc32($name) % count($hues)] : null;
@endphp
<span {{ $attributes->class([
    'arm-avatar',
    'arm-avatar--'.$size => $size !== 'md',
    'arm-avatar--square' => $square,
    'arm-avatar--ring' => $ring,
])->merge(['style' => $bg ? "--avatar-bg: $bg" : null, 'role' => 'img', 'aria-label' => $name]) }}>
    @if ($src)<img src="{{ $src }}" alt="" loading="lazy">
    @elseif ($initials !== '')<span aria-hidden="true">{{ $initials }}</span>
    @else<armada:icon :name="$icon ?? 'user'" />@endif
    @if ($status)<span class="arm-avatar__status" data-status="{{ $status }}"></span>@endif
    @if ($badge !== null)<span class="arm-avatar__badge"><armada:badge variant="solid" tone="danger" size="sm">{{ $badge }}</armada:badge></span>@endif
</span>
