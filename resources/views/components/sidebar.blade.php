@props(['brand' => null, 'footer' => null, 'alt' => false])
<aside {{ $attributes->class(['arm-sidebar', 'arm-sidebar--alt' => $alt]) }}
    x-bind:class="{ 'is-open': open }"
    x-on:click="if ($event.target.closest('a[href]')) open = false">
    @if ($brand)
        <div class="arm-sidebar__header">{{ $brand }}</div>
    @endif
    <div class="arm-sidebar__body">{{ $slot }}</div>
    @if ($footer)
        <div class="arm-sidebar__footer">{{ $footer }}</div>
    @endif
</aside>
