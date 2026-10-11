@props(['scroll' => false])
<div {{ $attributes->class(['arm-chip-group', 'arm-chip-group--scroll' => $scroll]) }}>{{ $slot }}</div>
