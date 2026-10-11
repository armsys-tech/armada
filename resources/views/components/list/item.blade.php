@props([
    'title' => null,
    'subtitle' => null,
    'icon' => null,
    'iconTone' => null,
    'avatar' => null,     // image URL or a name
    'href' => null,
    'leading' => null,    // slot: anything left of the text (overrides avatar/icon)
    'trailing' => null,   // slot or text
    'chevron' => null,    // default: on for links
    'active' => false,
])
@php
    $tag = $href ? 'a' : ($attributes->has('wire:click') || $attributes->has('x-on:click') ? 'button' : 'div');
    $chevron ??= (bool) $href;
@endphp
<{{ $tag }} @if ($href) href="{{ $href }}" @elseif ($tag === 'button') type="button" @endif
    {{ $attributes->class(['arm-list__item', 'is-active' => $active]) }} role="listitem">
    @if ($leading)
        <span class="arm-list__leading">{{ $leading }}</span>
    @elseif ($avatar)
        <span class="arm-list__leading"><armada:avatar :src="str_contains($avatar, '/') ? $avatar : null" :name="str_contains($avatar, '/') ? null : $avatar" /></span>
    @elseif ($icon)
        <span class="arm-list__leading"><span class="arm-list__icon-box" @if ($iconTone) data-tone="{{ $iconTone }}" @endif><armada:icon :name="$icon" size="lg" /></span></span>
    @endif
    <span class="arm-list__content">
        @if ($title)<span class="arm-list__title">{{ $title }}</span>@endif
        @if ($subtitle)<span class="arm-list__subtitle">{{ $subtitle }}</span>@endif
        {{ $slot }}
    </span>
    @if ($trailing || $chevron)
        <span class="arm-list__trailing">{{ $trailing }}@if ($chevron)<armada:icon name="chevron-right" size="md" />@endif</span>
    @endif
</{{ $tag }}>
