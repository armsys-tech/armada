@props(['variant' => 'default']) {{-- default | underline --}}
<nav {{ $attributes->class(['arm-navbar', 'arm-navbar--'.$variant => $variant !== 'default']) }}>{{ $slot }}</nav>
