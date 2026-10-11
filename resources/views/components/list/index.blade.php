@props(['variant' => 'plain']) {{-- plain | divided | bordered --}}
<div {{ $attributes->class(['arm-list', 'arm-list--'.$variant => $variant !== 'plain']) }} role="list">{{ $slot }}</div>
