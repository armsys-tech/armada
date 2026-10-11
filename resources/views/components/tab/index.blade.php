@props(['name' => null, 'icon' => null, 'badge' => null, 'href' => null, 'current' => false])
@if ($href)
    <a href="{{ $href }}" {{ $attributes->class('arm-tab')->merge(['aria-current' => $current ? 'page' : null]) }}>
        @if ($icon)<armada:icon :name="$icon" size="md" />@endif{{ $slot }}@if ($badge !== null)<span class="arm-tab__badge">{{ $badge }}</span>@endif
    </a>
@else
    <button type="button" role="tab" data-name="{{ $name }}" {{ $attributes->class('arm-tab') }}
        x-bind:aria-selected="tab === @js($name)" x-bind:tabindex="tab === @js($name) ? 0 : -1"
        x-on:click="tab = @js($name); $el.scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: 'smooth' })">
        @if ($icon)<armada:icon :name="$icon" size="md" />@endif{{ $slot }}@if ($badge !== null)<span class="arm-tab__badge">{{ $badge }}</span>@endif
    </button>
@endif
