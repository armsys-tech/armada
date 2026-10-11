@props(['content', 'position' => 'top', 'kbd' => null]) {{-- top | bottom | left | right --}}
<span {{ $attributes->class('arm-tooltip') }} x-data="armTooltip" x-on:mouseenter="place()" x-on:focusin="place()">
    {{ $slot }}
    <span class="arm-tooltip__content" role="tooltip" data-position="{{ $position }}" x-ref="tip">{{ $content }}@if ($kbd)<kbd class="arm-kbd">{{ $kbd }}</kbd>@endif</span>
</span>
