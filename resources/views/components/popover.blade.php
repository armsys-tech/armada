@props(['trigger', 'align' => 'start', 'position' => 'bottom', 'width' => null])
<div {{ $attributes->class('arm-dropdown') }} x-data="armDropdown" x-on:keydown.escape.stop="close(true)" x-on:click.outside="close()">
    <div style="display: contents" x-on:click="toggle()" x-ref="trigger">{{ $trigger }}</div>
    <template x-if="open"><div class="arm-popover-backdrop" x-on:click="close()"></div></template>
    <div @class(['arm-popover', 'arm-popover--padded', 'arm-popover--sheet', 'arm-popover--end' => $align === 'end', 'arm-popover--top' => $position === 'top'])
        x-ref="menu" x-show="open" x-cloak role="dialog" @if ($width) style="width: {{ $width }}" @endif>{{ $slot }}</div>
</div>
