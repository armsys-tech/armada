@props(['href' => null, 'icon' => 'circle', 'current' => null, 'badge' => null, 'fab' => false])
@php
    $current ??= $href && url()->current() === url($href);
    $tag = $href ? 'a' : 'button';
@endphp
<{{ $tag }} @if ($href) href="{{ $href }}" @else type="button" @endif
    {{ $attributes->class('arm-bottom-nav__item')->merge(['aria-current' => $current ? 'page' : null, 'aria-label' => $fab ? trim($slot) : null]) }}>
    @if ($fab)
        <span class="arm-bottom-nav__fab"><armada:icon :name="$icon" /></span>
    @else
        <span class="arm-bottom-nav__indicator">
            <armada:icon :name="$icon" />
            @if ($badge !== null)<span class="arm-bottom-nav__badge">{{ $badge }}</span>@endif
        </span>
        <span class="arm-bottom-nav__label">{{ $slot }}</span>
    @endif
</{{ $tag }}>
