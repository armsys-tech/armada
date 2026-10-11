@props(['vertical' => false, 'text' => null])
@if ($text)
    <div {{ $attributes->class('arm-separator arm-separator--text') }} role="separator">{{ $text }}</div>
@else
    <hr {{ $attributes->class(['arm-separator', 'arm-separator--vertical' => $vertical]) }} @if ($vertical) aria-orientation="vertical" @endif>
@endif
