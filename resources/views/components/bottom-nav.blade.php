@props([
    'variant' => 'default',   // default | floating
    'labels' => 'always',     // always | active | none
    'always' => false,        // show on every screen size, not only phones
    'static' => false,        // render in the flow (previews, phone mock-ups)
    'hideOnScroll' => false,
])
<nav {{ $attributes->class([
    'arm-bottom-nav',
    'arm-bottom-nav--floating' => $variant === 'floating',
    'arm-bottom-nav--labels-active' => $labels === 'active',
    'arm-bottom-nav--icons' => $labels === 'none',
    'arm-bottom-nav--always' => $always,
    'arm-bottom-nav--static' => $static,
]) }}
    @if ($hideOnScroll)
        x-data="{ hidden: false, y: 0 }"
        x-on:scroll.window.throttle.100ms="hidden = scrollY > y && scrollY > 80; y = scrollY"
        x-bind:class="{ 'is-hidden': hidden }"
    @endif
>{{ $slot }}</nav>
