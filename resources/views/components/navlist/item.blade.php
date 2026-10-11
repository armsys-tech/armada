@props(['href' => null, 'icon' => null, 'current' => null, 'badge' => null, 'badgeTone' => null])
@php
    $current ??= $href && url()->current() === url($href);
    $tag = $href ? 'a' : 'button';
@endphp
<{{ $tag }} @if ($href) href="{{ $href }}" @else type="button" @endif
    {{ $attributes->class('arm-navlist__item')->merge([
        'aria-current' => $current ? 'page' : null,
        'data-badge' => $badge !== null ? true : null,
        'title' => trim($slot),
    ]) }}>
    @if ($icon)<armada:icon :name="$icon" />@endif
    <span class="arm-navlist__label">{{ $slot }}</span>
    @if ($badge !== null)<span class="arm-navlist__badge" @if ($badgeTone) data-tone="{{ $badgeTone }}" @endif>{{ $badge }}</span>@endif
</{{ $tag }}>
