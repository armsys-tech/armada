@props(['label', 'icon' => null]) {{-- nested menu: flies out to the side on desktop, expands in place inside a phone sheet --}}
<div {{ $attributes->class('arm-menu__sub') }} x-data="{ sub: false }" x-on:mouseenter="if (matchMedia('(hover: hover)').matches) sub = true" x-on:mouseleave="if (matchMedia('(hover: hover)').matches) sub = false" x-on:keydown.escape.stop="sub = false">
    <button type="button" class="arm-menu__item" role="menuitem" aria-haspopup="menu" x-bind:aria-expanded="sub" data-keep-open
        x-on:click="sub = ! sub" x-on:keydown.right.prevent="sub = true; $nextTick(() => $refs.sub.querySelector('[role^=menuitem]')?.focus())">
        @if ($icon)<armada:icon :name="$icon" />@endif
        <span class="arm-menu__label">{{ $label }}</span>
        <armada:icon name="chevron-right" class="arm-menu__sub-chevron" />
    </button>
    <div class="arm-menu arm-menu--nested" role="menu" x-ref="sub" x-show="sub" x-cloak x-on:keydown.left.prevent.stop="sub = false; $el.previousElementSibling.focus()">{{ $slot }}</div>
</div>
