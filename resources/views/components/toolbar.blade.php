@props(['flat' => false])
<div {{ $attributes->class(['arm-toolbar', 'arm-toolbar--flat' => $flat]) }} role="toolbar">{{ $slot }}</div>
