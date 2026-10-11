@props(['size' => null]) {{-- sm | md | prose | full --}}
<div {{ $attributes->class(['arm-container', 'arm-container--'.$size => $size]) }}>{{ $slot }}</div>
