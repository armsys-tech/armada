@props(['icon' => 'plus', 'label' => null, 'extended' => false, 'tone' => 'primary', 'static' => false, 'position' => 'right', 'href' => null]) {{-- a slot of <armada:fab.action> items makes it a speed dial --}}
@php $dial = $slot->isNotEmpty(); $tag = $href && ! $dial ? 'a' : 'button'; @endphp
<div {{ $attributes->only(['class', 'style'])->class(['arm-fab', 'arm-fab--static' => $static, 'arm-fab--left' => $position === 'left']) }}
    @if ($dial) x-data="{ open: false }" x-on:click.outside="open = false" x-on:keydown.escape="open = false" @endif>
    @if ($dial)
        <div class="arm-fab__actions" x-show="open" x-cloak x-transition>{{ $slot }}</div>
    @endif
    <{{ $tag }} @if ($tag === 'a') href="{{ $href }}" @else type="button" @endif
        {{ $attributes->except(['class', 'style'])->class(['arm-btn', 'arm-btn--solid', 'arm-fab__button', 'arm-btn--extended' => $extended])->merge(['data-tone' => $tone, 'aria-label' => $label]) }}
        @if ($dial) x-on:click="open = ! open" x-bind:aria-expanded="open" @endif>
        <span class="arm-icon" aria-hidden="true" @if ($dial) x-bind:style="open && 'transform: rotate(45deg)'" style="transition: transform var(--arm-dur)" @endif>{!! \Armada\Armada::icon($icon) !!}</span>
        @if ($extended)<span>{{ $label }}</span>@endif
    </{{ $tag }}>
</div>
