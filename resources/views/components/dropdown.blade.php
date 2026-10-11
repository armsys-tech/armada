@props(['trigger', 'align' => 'start', 'position' => 'bottom', 'width' => null]) {{-- align: start | end · position: bottom | top --}}
<div {{ $attributes->class('arm-dropdown') }} x-data="armDropdown" x-on:keydown.escape.stop="close(true)" x-on:click.outside="close()">
    <div style="display: contents" x-on:click="toggle()" x-ref="trigger">{{ $trigger }}</div>
    <template x-if="open"><div class="arm-popover-backdrop" x-on:click="close()"></div></template>
    <div class="arm-menu arm-menu--sheet" role="menu" x-ref="menu" x-show="open" x-cloak
        data-align="{{ $align }}" data-position="{{ $position }}" @if ($width) style="min-width: {{ $width }}" @endif
        x-on:click="if ($event.target.closest('.arm-menu__item:not([data-keep-open])')) close()"
        x-on:keydown.down.prevent="focusNext(1)" x-on:keydown.up.prevent="focusNext(-1)">
        {{ $slot }}
    </div>
</div>
