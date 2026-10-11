@props(['content', 'position' => 'top', 'kbd' => null]) {{-- top | bottom | left | right --}}
<span {{ $attributes->class('arm-tooltip') }}>
    {{ $slot }}
    <span class="arm-tooltip__content" role="tooltip" data-position="{{ $position }}">{{ $content }}@if ($kbd)<kbd class="arm-kbd">{{ $kbd }}</kbd>@endif</span>
</span>
