@props(['variant' => 'underline', 'block' => false, 'vertical' => false]) {{-- underline | pill | segment --}}
<div {{ $attributes->class([
    'arm-tabs',
    'arm-tabs--'.$variant => $variant !== 'underline',
    'arm-tabs--block' => $block,
    'arm-tabs--vertical' => $vertical,
]) }} role="tablist" @if ($vertical) aria-orientation="vertical" @endif
    x-data="{ fade: false, measure() { this.fade = $el.scrollWidth - $el.clientWidth > 1 && $el.scrollLeft + $el.clientWidth < $el.scrollWidth - 1 } }"
    x-init="measure(); new ResizeObserver(() => measure()).observe($el)" x-on:scroll.passive="measure()" x-bind:data-fade="fade ? '' : null"
    x-on:keydown.right.prevent="$event.target.nextElementSibling?.click(); $event.target.nextElementSibling?.focus()"
    x-on:keydown.down.prevent="$event.target.nextElementSibling?.click(); $event.target.nextElementSibling?.focus()"
    x-on:keydown.left.prevent="$event.target.previousElementSibling?.click(); $event.target.previousElementSibling?.focus()"
    x-on:keydown.up.prevent="$event.target.previousElementSibling?.click(); $event.target.previousElementSibling?.focus()">{{ $slot }}</div>
