@props(['hint' => false]) {{-- hint: hidden on touch screens --}}
<kbd {{ $attributes->class(['arm-kbd', 'arm-kbd--hint' => $hint]) }}>{{ $slot }}</kbd>
